<?php

namespace App\Http\Controllers;

use App\Models\Institucion;
use App\Models\WorkCategory;
use App\Models\Subscription;  
use App\Models\insuranceAgents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\AddedServiceService;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InstitucionSheetImport;
use RealRashid\SweetAlert\Facades\Alert;

use Normalizer;   // <<< أضف هذا السطر


class InstitucionController extends Controller
{
  public function index(Request $request)
    {
        $user = Auth::user();

        $query = Institucion::query()->with('insuranceAgent');

        // فلترة الحالة إذا حابب
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', 1);
            } elseif ($request->status === 'inactive') {
                $query->where('status', 0);
            }
        }

        if ($user->hasRole('insurance-manager')) {
            // يشوف كل شيء
        } elseif ($user->hasRole('Wakeel')) {
                    $agentId = $user->insuranceAgents->pluck('id')->toArray();
            $query->where('insurance_agent_id', $agentId);
        } else {
            // ممكن ترجعه فاضي أو تحط منطق آخر
            $query->whereRaw('1=0');
        }

        $items = $query->get(); // بدون باجينيت

        return view('institucions.index', compact('items'));
    }


  
    public function create()
    {
        $user = Auth::user();

        $workCategories  = WorkCategory::orderBy('name')->get();
        $subscriptions   = Subscription::orderBy('id', 'desc')->get();
        $agents          = collect(); // للأدمن فقط
        $requiresDocsIds = [20, 21];

        $showAgentSelect    = false; // هل نعرض السيلكت؟
        $preselectedAgentId = null;  // القيمة التي سنرسلها للواجهة

        if ($user->hasRole('admin')) {
            $showAgentSelect    = true;
            $agents             = InsuranceAgents::select('id','name')->orderBy('name')->get();
            $preselectedAgentId = old('insurance_agent_id'); // مفرد
        } elseif ($user->hasRole('Wakeel')) {
            $preselectedAgentId = $user->insuranceAgents()->pluck('insurance_agents.id')->first();
        } elseif ($user->hasRole('insurance-manager')) {
            $preselectedAgentId = 94; // المطلوب
        } else {
            abort(403, 'ليس لديك صلاحية لإضافة جهة عمل.');
        }

        return view('institucions.create', compact(
            'workCategories','subscriptions','agents','requiresDocsIds',
            'showAgentSelect','preselectedAgentId'
        ));
    }

   
    public function store(Request $request)
{
    $user = auth()->user();

    // ✅ تحديد الوكيل حسب الدور
    $forcedAgentId = null;

    if ($user->hasRole('insurance-manager')) {
        $forcedAgentId = 94;
    } elseif ($user->hasRole('Wakeel')) {
        $forcedAgentId = $user->insuranceAgents()->pluck('insurance_agents.id')->first();
        if (!$forcedAgentId) {
            return back()->withErrors(['insurance_agent_id' => 'لا يوجد وكيل تأميني مرتبط بحسابك.'])->withInput();
        }
    }

    if (!is_null($forcedAgentId)) {
        $request->merge(['insurance_agent_id' => $forcedAgentId]);
    }

    // ✅ تعيين الاشتراك حسب work_categories_id
    $wcId = (int) $request->input('work_categories_id');
    $autoMap = [
        19 => 10,
        21 => 11,
        20 => 10,
    ];
    if (isset($autoMap[$wcId])) {
        $request->merge(['subscriptions_id' => $autoMap[$wcId]]);
    }

    $agentRule = $user->hasRole('admin')
        ? 'required|exists:insurance_agents,id'
        : 'exists:insurance_agents,id';

    // ✅ الفالديشن
    $validated = $request->validate([
        'name'               => ['required', 'string', 'max:255'],
        'commercial_number'  => ['nullable', 'string', 'max:255', 'unique:institucions,commercial_number'],
        'work_categories_id' => ['required', 'exists:work_categories,id'],
        'subscriptions_id'   => ['required', 'exists:subscription33,id'],
        'insurance_agent_id' => $agentRule,
        'status'             => ['nullable', 'in:0,1'],

        'license_number'     => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        'commercial_record'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        'code'               => ['nullable','string','max:50'], 
        'excel_sheet'        => ['nullable', 'file', 'mimes:xlsx,xls,csv', 'max:51200'],
    ], [
        'insurance_agent_id.required' => 'يجب اختيار وكيل تأميني.',
    ]);

    $data = $validated;

    // ✅ الحالة حسب الدور
    $data['status'] = $user->hasRole('Wakeel') ? 0 : (array_key_exists('status', $data) ? (int)(bool)$data['status'] : 1);

    // ✅ رفع الملفات
    $uploadPath = public_path('institucions_files');
    if (!file_exists($uploadPath)) mkdir($uploadPath, 0775, true);

    if ($request->hasFile('license_number')) {
        $f = $request->file('license_number');
        $name = time().'_license_'.$f->getClientOriginalName();
        $f->move($uploadPath, $name);
        $data['license_number'] = 'institucions_files/'.$name;
    }

    if ($request->hasFile('commercial_record')) {
        $f = $request->file('commercial_record');
        $name = time().'_record_'.$f->getClientOriginalName();
        $f->move($uploadPath, $name);
        $data['commercial_record'] = 'institucions_files/'.$name;
    }

    // ✅ إنشاء الجهة
    $model = \App\Models\Institucion::create($data);

    // ✅ تسجيل الخدمة (service_id = 1 ثابت)
    \App\Models\ServiceLog::create([
        'user_id'        => $user->id,
        'service_id'     => 1,
        'institucion_id' => $model->id,
        'customer_id'    => null,
    ]);

    // ✅ استيراد الإكسل لو موجود
    if ($request->hasFile('excel_sheet')) {
        try {
            // 1️⃣ استيراد مباشرة
            Excel::import(new InstitucionSheetImport($model->id), $request->file('excel_sheet'));

            // 2️⃣ حفظ نسخة في الفولدر
            $f = $request->file('excel_sheet');
            $name = time().'_excel_'.$f->getClientOriginalName();
            $f->move($uploadPath, $name);

            $model->update([
                'excel_path' => 'institucions_files/'.$name
            ]);

        } catch (\Throwable $e) {
            return redirect()->route('institucions.show', $model)
                ->with('warning', 'تم إنشاء جهة العمل، لكن حدث خطأ أثناء استيراد ملف الإكسل: '.$e->getMessage());
        }
    }

    // ✅ رجوع بالنجاح
    return redirect()->route('institucions.show', $model)
        ->with('success', 'تمت إضافة جهة العمل بنجاح');
}



public function show(Institucion $institucion)
{
    $otherInstitucions = Institucion::where('id', '!=', $institucion->id)
        ->pluck('name', 'id');

    // عدد المشتركين
    $customersCount = $institucion->customers()->count();

    // قائمة المشتركين (مثلاً الاسم والرقم الوطني)
    $customers = $institucion->customers()
        ->select('id','fullnamea','nationalID','phone')
        ->get();

    return view('institucions.show', compact('institucion','otherInstitucions','customersCount','customers'));
}

  



    public function edit(Institucion $institucion)
{
    $user = Auth::user();

    $workCategories  = WorkCategory::orderBy('name')->get();
    $subscriptions   = Subscription::orderBy('id', 'desc')->get();
    $agents          = collect(); // يظهر فقط للأدمن
    $requiresDocsIds = [20, 21];

    $showAgentSelect    = false; 
    $preselectedAgentId = null;

    if ($user->hasRole('admin')) {
        $showAgentSelect    = true;
        $agents             = InsuranceAgents::select('id','name')->orderBy('name')->get();
        $preselectedAgentId = old('insurance_agent_id', $institucion->insurance_agent_id);
    } elseif ($user->hasRole('Wakeel')) {
        $preselectedAgentId = $user->insuranceAgents()->pluck('insurance_agents.id')->first();
    } elseif ($user->hasRole('insurance-manager')) {
        $preselectedAgentId = 94; 
    } else {
        abort(403, 'ليس لديك صلاحية لتعديل جهة عمل.');
    }

    return view('institucions.edit', compact(
        'institucion',
        'workCategories',
        'subscriptions',
        'agents',
        'requiresDocsIds',
        'showAgentSelect',
        'preselectedAgentId'
    ));
}



    // public function update(Request $request, Institucion $institucion)
    // {
    //     $validated = $request->validate([
    //         'name'               => ['required', 'string', 'max:255'],
    //         'work_categories_id' => ['required', 'exists:work_categories,id'],
    //         'subscriptions_id'   => ['required', 'exists:subscription33,id'],
    //         'insurance_agent_id' => ['nullable', 'exists:insurance_agents,id'],
    //         'status'             => ['nullable', 'integer'],

    //         'commercial_number'  => [
    //             'nullable','string','max:255',
    //             Rule::unique('institucions', 'commercial_number')->ignore($institucion->id),
    //         ],
    //         'license_number'     => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
    //         'commercial_record'  => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
    //     ]);

    //     $data = $validated;

    //     $wcId = (int) $request->input('work_categories_id');
    //     $autoMap = [
    //         19 => 10, // 19 أو 21 → 10
    //         21 => 11,
    //         20 => 10, // 20 → 11
    //     ];

    //     // استبدال الملفات عند الرفع (بدون إجبار – الفيو يحدد متى تظهر)
    //     if ($request->hasFile('license_number')) {
    //         // حذف القديم إن وجد
    //         if ($institucion->license_number && Storage::exists($institucion->license_number)) {
    //             Storage::delete($institucion->license_number);
    //         }
    //         $data['license_number'] = $request->file('license_number')
    //                                          ->store('public/institucions_files');
    //     }

    //     if ($request->hasFile('commercial_record')) {
    //         if ($institucion->commercial_record && Storage::exists($institucion->commercial_record)) {
    //             Storage::delete($institucion->commercial_record);
    //         }
    //         $data['commercial_record'] = $request->file('commercial_record')
    //                                             ->store('public/institucions_files');
    //     }

    //     $institucion->update($data);

    //     return redirect()->route('institucions.show', $institucion)
    //         ->with('success', 'تم تعديل جهة العمل بنجاح');
    // }

    public function update(Request $request, Institucion $institucion)
        {
            $validated = $request->validate([
                'name'               => ['required', 'string', 'max:255'],
                'work_categories_id' => ['required', 'exists:work_categories,id'],
                // 'subscriptions_id'   => ['required', 'exists:subscription33,id'],
                'insurance_agent_id' => ['nullable', 'exists:insurance_agents,id'],
                'status'             => ['nullable', 'integer'],

                'commercial_number'  => [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('institucions', 'commercial_number')->ignore($institucion->id),
                ],
                'license_number'     => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
                'commercial_record'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
                'excel_sheet'        => ['nullable', 'file', 'mimes:xlsx,xls,csv', 'max:51200'],
            ]);

            $data = $validated;

            // استبدال الملفات عند الرفع
            if ($request->hasFile('license_number')) {
                if ($institucion->license_number && Storage::exists($institucion->license_number)) {
                    Storage::delete($institucion->license_number);
                }
                $data['license_number'] = $request->file('license_number')
                    ->store('public/institucions_files');
            }

            if ($request->hasFile('commercial_record')) {
                if ($institucion->commercial_record && Storage::exists($institucion->commercial_record)) {
                    Storage::delete($institucion->commercial_record);
                }
                $data['commercial_record'] = $request->file('commercial_record')
                    ->store('public/institucions_files');
            }

            $institucion->update($data);

            if ($request->hasFile('excel_sheet')) {
                try {
                    $importer = new \App\Imports\InstitucionSheetImport($institucion->id);
                    Excel::import($importer, $request->file('excel_sheet'));

                    // حفظ نسخة من الملف
                    $f = $request->file('excel_sheet');
                    $name = time() . '_excel_' . $f->getClientOriginalName();
                    $f->move(public_path('institucions_files'), $name);

                    // $institucion->update([
                    //     'excel_path' => 'institucions_files/' . $name
                    // ]);

                    $msg = "تم تعديل جهة العمل بنجاح.<br>
                    <strong>تمت إضافة {$importer->inserted} مشترك جديد</strong><br>
                    <strong>وتحديث {$importer->updated} مشترك موجود</strong>";

                    Alert::html('نجاح', $msg, 'success');



                    return redirect()->route('institucions.show', $institucion);
                } catch (\Throwable $e) {
                    Alert::warning(
                        'تنبيه',
                        'تم تعديل جهة العمل، لكن حدث خطأ أثناء استيراد ملف الإكسل:<br>' . e($e->getMessage())
                    )->html();

                    return redirect()->route('institucions.show', $institucion);
                }
            }

            // لا يوجد ملف إكسل — نجاح عادي
            Alert::success('تم التعديل', 'تم تعديل جهة العمل بنجاح');
            return redirect()->route('institucions.show', $institucion);
        }
    public function destroy(Institucion $institucion)
    {
        // حذف الملفات المرتبطة (إن وجدت)
        foreach (['license_number', 'commercial_record'] as $f) {
            $p = $institucion->{$f};
            if ($p && Storage::exists($p)) {
                Storage::delete($p);
            }
        }

        $institucion->delete();

        return redirect()->route('institucions.index')
            ->with('success', 'تم حذف جهة العمل بنجاح');
    }




    
     public function storefromsubscriberview(Request $request)
    {
        $user = auth()->user();

        // ✅ تحديد الوكيل حسب الدور
        $forcedAgentId = null;

        if ($user->hasRole('insurance-manager')) {
            $forcedAgentId = 94;
        } elseif ($user->hasRole('Wakeel')) {
            $forcedAgentId = $user->insuranceAgents()->pluck('insurance_agents.id')->first();
            if (!$forcedAgentId) {
                return back()->withErrors(['insurance_agent_id' => 'لا يوجد وكيل تأميني مرتبط بحسابك.'])->withInput();
            }
        }

        if (!is_null($forcedAgentId)) {
            $request->merge(['insurance_agent_id' => $forcedAgentId]);
        }

        // ✅ تعيين الاشتراك تلقائي حسب work_categories_id
        $wcId = (int) $request->input('work_categories_id');
        $autoMap = [
            19 => 10,
            21 => 11,
            20 => 10,
        ];
        if (isset($autoMap[$wcId])) {
            $request->merge(['subscriptions_id' => $autoMap[$wcId]]);
        }

        $agentRule = $user->hasRole('admin')
            ? 'required|exists:insurance_agents,id'
            : 'exists:insurance_agents,id';

        // ✅ الفالديشن
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'work_categories_id' => 'required|exists:work_categories,id',
            'subscriptions_id'   => 'required|exists:subscription33,id',
            'insurance_agent_id' => $agentRule,
        ]);

        // --- تحقق التشابه قبل الحفظ ---
        $baseNorm   = $this->normalizeName($validated['name']);
        $baseTokens = $this->nameTokens($baseNorm);
        $head       = $baseTokens[0] ?? null;

        $conflicts = [];

        if ($head && mb_strlen($head) >= 2) {
            $candidates = \App\Models\Institucion::query()
                ->where(function ($q) use ($head) {
                    $q->orWhere('name', 'like', "{$head} %")
                        ->orWhere('name', 'like', "% {$head} %")
                        ->orWhere('name', 'like', "% {$head}")
                        ->orWhere('name', 'like', "%{$head}%")
                        ->orWhere('name', 'like', "%ال{$head}%")
                        ->orWhere('name', 'like', "%لل{$head}%")
                        ->orWhere('name', 'like', "%و{$head}%")
                        ->orWhere('name', 'like', "%ف{$head}%")
                        ->orWhere('name', 'like', "%ب{$head}%")
                        ->orWhere('name', 'like', "%ك{$head}%")
                        ->orWhere('name', 'like', "%ل{$head}%");
                })
                ->select('id', 'name')
                ->limit(200)
                ->get();

            $isSingleWordBase = count($baseTokens) === 1;

            foreach ($candidates as $row) {
                $candNorm   = $this->normalizeName($row->name);
                $candTokens = $this->nameTokens($candNorm);

                $noSpaceBase = str_replace(' ', '', $baseNorm);
                $noSpaceCand = str_replace(' ', '', $candNorm);
                $contained = (mb_strlen($noSpaceBase) >= 4 && mb_strlen($noSpaceCand) >= 4) &&
                    (mb_strpos($noSpaceBase, $noSpaceCand) !== false ||
                        mb_strpos($noSpaceCand, $noSpaceBase) !== false);

                $jaccard = $this->jaccardSimilarity(array_values(array_unique($baseTokens)), array_values(array_unique($candTokens)));
                $overlap = $this->overlapCoefficient(array_values(array_unique($baseTokens)), array_values(array_unique($candTokens)));

                $candHead    = $candTokens[0] ?? null;
                $prefixMatch = $head && $candHead && $head === $candHead;

                $similar = $isSingleWordBase
                    ? ($prefixMatch || $contained)
                    : ($contained || $overlap >= 80 || $jaccard >= 60);

              $threshold = mb_strlen($baseNorm) <= 6 ? 70 : 90;

                // ✅ لو الاسم كلمة وحدة ومتطابق تمامًا → اعتبره تشابه 100%
                if ($isSingleWordBase && $baseNorm === $candNorm) {
                    $conflicts[] = [
                        'id'      => $row->id,
                        'name'    => $row->name,
                        'percent' => 100,
                    ];
                }

                // ✅ وإلا طبق العتبة الديناميكية
                elseif ($similar && max($overlap, $jaccard) >= $threshold) {
                    $conflicts[] = [
                        'id'      => $row->id,
                        'name'    => $row->name,
                        'percent' => round(max($overlap, $jaccard), 2),
                    ];
                }
            }
        }

        if (!empty($conflicts)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'تم العثور على جهات مشابهة',
                    'conflicts' => $conflicts,
                ], 422);
            }

            return back()
                ->withErrors(['name' => 'الاسم مشابه لجهات أخرى'])
                ->withInput()
                ->with('similar_conflicts', $conflicts);
        }

        // --- مافيش تشابه عالي، نكمل الحفظ ---
        $data = $validated;

        // ✅ حالة الوكيل
        $data['status'] = $user->hasRole('Wakeel') ? 0 : 1;

        $inst = \App\Models\Institucion::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'id'   => $inst->id,
                'name' => $inst->name,
            ]);
        }

        return redirect()->back()->with('success', 'تم الحفظ بنجاح');
    }




// App\Http\Controllers\InstitucionController.php

// public function transferCustomers(Request $request, Institucion $institucion)
// {
//     $toId = $request->input('from_id'); // هي الوجهة اللي بننقل لها

//     if (!$toId) {
//         return back()->withErrors(['from_id' => 'يجب اختيار جهة عمل للنقل إليها.']);
//     }

//     $to = Institucion::find($toId);
//     if (!$to) {
//         return back()->withErrors(['from_id' => 'جهة العمل الهدف غير موجودة.']);
//     }

//     // 👈 ننقل مشتركين الجهة المفتوحة ($institucion) إلى الجهة الهدف ($to)
//     $affected = \App\Models\Customer::where('institucion_id', $institucion->id)
//         ->update(['institucion_id' => $to->id]);

//     return back()->with('success', "✅ تم نقل {$affected} مشترك من '{$institucion->name}' إلى '{$to->name}'");
// }


// صفحة عرض الجهات لاختيار النقل
public function transferView(Institucion $institucion)
{
    // كل الجهات الأخرى (مع عدد المشتركين)
    $others = Institucion::where('id', '!=', $institucion->id)
        ->withCount('customers')
        ->get();

    return view('institucions.transfer', compact('institucion', 'others'));
}

// تنفيذ النقل
public function transferStore(Request $request, Institucion $institucion)
{
    $request->validate([
        'to_id' => 'required|exists:institucions,id'
    ]);

    $to = Institucion::findOrFail($request->to_id);

    // ننقل مشتركين الجهة الحالية إلى الجهة الجديدة
    $affected = \App\Models\Customer::where('institucion_id', $institucion->id)
        ->update(['institucion_id' => $to->id]);

    return redirect()->route('institucions.show', $institucion->id)
        ->with('success', "✅ تم نقل {$affected} مشترك من '{$institucion->name}' إلى '{$to->name}'");
}







// public function toggleStatus(Institucion $institucion, Request $request)
// {
//     // ✅ لو الجهة نشطة → إيقاف مباشر
//     if ($institucion->status === 1) {
//         $institucion->status = 0;
//         $institucion->save();

//         return back()->with('success', 'تم إيقاف الجهة');
//     }

//     $conflicts = [];
//     if (!$request->boolean('force')) {
//         $baseRaw    = $institucion->name;
//         $baseNorm   = $this->normalizeName($baseRaw);
//         $baseTokens = $this->nameTokens($baseNorm);

//         if (!empty($baseTokens)) {
//             $query = Institucion::where('id', '!=', $institucion->id);
//             $query->where(function ($qq) use ($baseTokens) {
//                 foreach (array_slice($baseTokens, 0, 3) as $kw) {
//                     $qq->orWhere('name', 'like', "%{$kw}%");
//                 }
//             });

//             $others = $query->select('id', 'name')->get();

//             $baseHead = $baseTokens[0] ?? null;
//             $isSingleWordBase = count($baseTokens) === 1;

//             foreach ($others as $row) {
//                 if ($row->id == $institucion->id) continue;

//                 $candNorm   = $this->normalizeName($row->name);
//                 $candTokens = $this->nameTokens($candNorm);
//                 $candHead   = $candTokens[0] ?? null;

//                 preg_match_all('/\d+/', $baseNorm, $baseNums);
//                 preg_match_all('/\d+/', $candNorm, $candNums);

//                 $baseNums = $baseNums[0] ?? [];
//                 $candNums = $candNums[0] ?? [];
//                 $numbersMatch = !empty($baseNums) && !empty($candNums) &&
//                                 count(array_intersect($baseNums, $candNums)) > 0;

//                 $noSpaceBase = str_replace(' ', '', $baseNorm);
//                 $noSpaceCand = str_replace(' ', '', $candNorm);
//                 $contained = (mb_strlen($noSpaceBase) >= 4 && mb_strlen($noSpaceCand) >= 4) &&
//                              (mb_strpos($noSpaceBase, $noSpaceCand) !== false ||
//                               mb_strpos($noSpaceCand, $noSpaceBase) !== false);

//                 $uniqBase = array_values(array_unique($baseTokens));
//                 $uniqCand = array_values(array_unique($candTokens));
//                 $jaccard  = $this->jaccardSimilarity($uniqBase, $uniqCand);
//                 $overlap  = $this->overlapCoefficient($uniqBase, $uniqCand);

//                 $prefixMatch = $baseHead && $candHead && $baseHead === $candHead;

//                 $similar = false;
//                 if ($isSingleWordBase) {
//                     $similar = $prefixMatch || $contained || $numbersMatch;
//                 } else {
//                     $similar = $contained || $overlap >= 80 || $jaccard >= 60 || $numbersMatch;
//                 }

//                 if ($similar) {
//                     $countCustomers = \App\Models\Customer::where('institucion_id', $row->id)->count();

//                     $conflicts[] = [
//                         'id'      => $row->id,
//                         'name'    => $row->name,
//                         'percent' => round(max($overlap, $jaccard), 2),
//                         'count'   => $countCustomers,
//                     ];
//                 }
//             }

//             if (!empty($conflicts)) {
//                 // 🔸 أرجع تحذير فقط لو مش "force"
//                 return back()
//                     ->with('similar_warning', 'هناك جهات مسجّلة بأسماء مشابهة')
//                     ->with('similar_conflicts', $conflicts);
//             }
//         }
//     }

//     // ✅ لو وصلنا هنا → تفعيل
//     $institucion->status = 1;
//     $institucion->save();

//     // ✅ نقل المشتركين أوتوماتيكلي
//     if (!empty($conflicts) || $request->boolean('force')) {
//         // في حالة force نستعمل الـ session أو نرجع نبحث تاني
//         $conflicts = $conflicts ?: session('similar_conflicts', []);

//         foreach ($conflicts as $dup) {
//             \App\Models\Customer::where('institucion_id', $dup['id'])
//                 ->update(['institucion_id' => $institucion->id]);
//         }
//     }

//     return back()->with('success', 'تم تفعيل الجهة ونقل المشتركين (إن وجدوا)');
// }

public function toggleStatus(Institucion $institucion, Request $request)
{
    // ✅ لو الجهة نشطة → إيقاف مباشر
    if ((int) $institucion->status === 1) {
        $institucion->status = 0;
        $institucion->save();
        return redirect()->route('institucions.show', $institucion)
            ->with('success', 'تم إيقاف الجهة');
    }

    // ---------- دالة مساعدة داخلية (بدون إضافة ميثود جديد للكلاس) ----------
    $buildConflicts = function () use ($institucion) {
        $baseRaw    = $institucion->name;
        $baseNorm   = $this->normalizeName($baseRaw);
        $baseTokens = $this->nameTokens($baseNorm);

        $conflicts = [];
        if (empty($baseTokens)) return $conflicts;

        $baseHead         = $baseTokens[0] ?? null;
        $isSingleWordBase = count($baseTokens) === 1;
        $noSpaceBase      = str_replace(' ', '', $baseNorm);

        // أرقام الأساس
        preg_match_all('/\d+/', $baseNorm, $baseNums);
        $baseNums = $baseNums[0] ?? [];

        $others = \App\Models\Institucion::where('id', '!=', $institucion->id)
            ->select('id','name')->get();

        foreach ($others as $row) {
            $candNorm   = $this->normalizeName($row->name);
            $candTokens = $this->nameTokens($candNorm);
            $candHead   = $candTokens[0] ?? null;

            $noSpaceCand = str_replace(' ', '', $candNorm);

            // أرقام المرشّح
            preg_match_all('/\d+/', $candNorm, $candNums);
            $candNums = $candNums[0] ?? [];
            $numbersMatch = !empty($baseNums) && !empty($candNums) &&
                            count(array_intersect($baseNums, $candNums)) > 0;

            // احتواء نصي
            $contained = (mb_strlen($noSpaceBase) >= 4 && mb_strlen($noSpaceCand) >= 4) &&
                         (mb_strpos($noSpaceBase, $noSpaceCand) !== false ||
                          mb_strpos($noSpaceCand, $noSpaceBase) !== false);

            // تشابه مجموعات
            $uniqBase = array_values(array_unique($baseTokens));
            $uniqCand = array_values(array_unique($candTokens));
            $jaccard  = $this->jaccardSimilarity($uniqBase, $uniqCand);
            $overlap  = $this->overlapCoefficient($uniqBase, $uniqCand);

            $prefixMatch = $baseHead && $candHead && $baseHead === $candHead;

            $similar = $isSingleWordBase
                ? ($prefixMatch || $contained || $numbersMatch)
                : ($contained || $overlap >= 80 || $jaccard >= 60 || $numbersMatch);

            if ($similar) {
                $countCustomers = \App\Models\Customer::where('institucion_id', $row->id)->count();
                $conflicts[] = [
                    'id'      => $row->id,
                    'name'    => $row->name,
                    'percent' => round(max($overlap, $jaccard), 2),
                    'count'   => $countCustomers,
                ];
            }
        }

        // ترتيب أجمل (أعلى نسبة ثم أعلى عدد)
        usort($conflicts, function ($a, $b) {
            return ($b['percent'] <=> $a['percent']) ?: ($b['count'] <=> $a['count']);
        });

        return $conflicts;
    };
    // -----------------------------------------------------------------------

    // ✅ أول ضغط "تفعيل" بدون force → فحص تشابه وإظهار القائمة
    if (!$request->boolean('force')) {
        $conflicts = $buildConflicts();
        if (!empty($conflicts)) {
            return redirect()->route('institucions.show', $institucion)
                ->with('similar_warning', 'هناك جهات مسجّلة بأسماء مشابهة')
                ->with('similar_conflicts', $conflicts);
        }
    } else {
        // في حالة "تفعيل رغم التشابه" لو أرسلتِ code نحفظه لو مش موجود
        if (!$institucion->code && $request->filled('code')) {
            $institucion->code = trim($request->input('code'));
        }
    }

    // ✅ التفعيل
    $institucion->status = 1;
    $institucion->save();

    // ✅ "تفعيل رغم التشابه" → نحسب التشابه الآن وننقل المشتركين فعلياً
    if ($request->boolean('force')) {
        $conflicts = $buildConflicts();
        if (!empty($conflicts)) {
            $ids = array_column($conflicts, 'id');
            \App\Models\Customer::whereIn('institucion_id', $ids)
                ->update(['institucion_id' => $institucion->id]);
        }
        return redirect()->route('institucions.show', $institucion)
            ->with('success', 'تم التفعيل ونُقل المشتركين من الجهات المشابهة');
    }

    return redirect()->route('institucions.show', $institucion)
        ->with('success', 'تم تفعيل الجهة');
}








    /**
     * normalizeName: توحيد/تنظيف عربي بدون Normalizer
     */
    private function normalizeName(string $name): string
    {
        $s = mb_strtolower($name, 'UTF-8');

        // إزالة أي نص داخل أقواس
        $s = preg_replace('/\(.+?\)/u', ' ', $s);

        // توحيد بعض الحروف
        $map = [
            'أ'=>'ا','إ'=>'ا','آ'=>'ا',
            'ى'=>'ي','ئ'=>'ي',
            'ؤ'=>'و',
            'ة'=>'ه',
            'ـ'=>'', // تطويل
        ];
        $s = strtr($s, $map);

        // إزالة التشكيل (حركات عربية)
        $s = preg_replace('/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06DC}\x{06DF}-\x{06E8}\x{06EA}-\x{06ED}]/u', '', $s);

        // تحويل الأرقام الهندية لعربية
        $nums = ['٠'=>'0','١'=>'1','٢'=>'2','٣'=>'3','٤'=>'4','٥'=>'5','٦'=>'6','٧'=>'7','٨'=>'8','٩'=>'9'];
        $s = strtr($s, $nums);

        // إزالة الرموز إلى مسافة، ثم توحيد المسافات
        $s = preg_replace('/[^\p{Arabic}\p{L}\p{N}\s]+/u', ' ', $s);
        $s = preg_replace('/\s+/u', ' ', $s);

        return trim($s);
    }

    /**
     * nameTokens: ترجع كلمات مُرتبة بعد تنظيف بادئات/تعريف وإزالة كلمات عامة/كيانات.
     */
    private function nameTokens(string $normalized): array
    {
        $stop = [
            // أشكال الكيان:
            'شركة','شركه','مصحة','مصحه','مؤسسة','مؤسسه','مركز','مجمع','مكتب','عيادة','عياده','مصرف','بنك',
            // كلمات عامة:
            'ليبيا','الليبيه','الليبية','العربيه','العربية','الدولي','الدولية','الوطني','الوطنيه','للخدمات','للعلاج','للطب','العلاج','الخدمات',
            // أدوات وربط:
            'ال','و','في','على','من','الى','إلى','بن','ابن','ذات','قسم','فرع','اداره','إدارة','لل',
            // اختصارات:
            'ذمم','ذ.م.م','ltd','co','inc'
        ];

        $parts = preg_split('/\s+/u', $normalized, -1, PREG_SPLIT_NO_EMPTY);
        $tokens = [];

        foreach ($parts as $w) {
            // إزالة بادئات عربية ملتصقة: بال/وال/فال/كال/لل
            $w = preg_replace('/^(بال|وال|فال|كال)/u', '', $w);
            // إزالة (و|ف|ب|ك|ل)?ال
            $w = preg_replace('/^(و|ف|ب|ك|ل)?ال/u', '', $w);
            // احتياط: إزالة حرف بادئ منفصل
            $w = preg_replace('/^[وفبكل]/u', '', $w);

            if (mb_strlen($w) < 2) continue;
            if (in_array($w, $stop, true)) continue;

            $tokens[] = $w; // نحافظ على الترتيب
        }

        return $tokens;
    }

    /** Jaccard similarity (0..100) */
    private function jaccardSimilarity(array $a, array $b): float
    {
        if (empty($a) && empty($b)) return 100.0;
        if (empty($a) || empty($b))  return 0.0;

        $setA = array_fill_keys($a, true);
        $setB = array_fill_keys($b, true);

        $intersect = array_intersect_key($setA, $setB);
        $union     = $setA + $setB;

        return (count($intersect) / max(1, count($union))) * 100.0;
    }

    /** Overlap coefficient (0..100) */
    private function overlapCoefficient(array $a, array $b): float
    {
        if (empty($a) || empty($b)) return 0.0;

        $setA = array_fill_keys($a, true);
        $setB = array_fill_keys($b, true);

        $intersect = array_intersect_key($setA, $setB);

        return (count($intersect) / min(count($a), count($b))) * 100.0;
    }
}




