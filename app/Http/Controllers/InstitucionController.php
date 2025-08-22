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
use Maatwebsite\Excel\Facades\Excel;
use Normalizer;   // <<< أضف هذا السطر

use App\Imports\InstitucionSheetImport;

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

        // نفرض قيمة insurance_agent_id حسب الدور قبل الفالديشن
        $forcedAgentId = null;

        if ($user->hasRole('insurance-manager')) {
            // ثابتة حسب طلبك
            $forcedAgentId = 94;
        } elseif ($user->hasRole('Wakeel')) {
            // أول وكيل مرتبط بالمستخدم (علاقة many-to-many)
            $forcedAgentId = $user->insuranceAgents()->pluck('insurance_agents.id')->first();

            if (!$forcedAgentId) {
                return back()
                    ->withErrors(['insurance_agent_id' => 'لا يوجد وكيل تأميني مرتبط بحسابك.'])
                    ->withInput();
            }
        }
        // Admin: ما نفرضش قيمة — ياخذها من الفورم

        if (!is_null($forcedAgentId)) {
            // ندمج القيمة المفروضة في الطلب حتى تدخل في الفالديشن والإنشاء
            $request->merge(['insurance_agent_id' => $forcedAgentId]);
        }

        // قاعدة الفالديشن لحقل الوكيل:
        // للأدمن: مطلوب (لتفادي أخطاء NOT NULL)
        // لغيره: موجودة تلقائيًا من الدمج فوق وتحتاج بس exists
        $agentRule = $user->hasRole('admin')
            ? 'required|exists:insurance_agents,id'
            : 'exists:insurance_agents,id';

        $validated = $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'commercial_number'  => ['nullable', 'string', 'max:255', 'unique:institucions,commercial_number'],
            'work_categories_id' => ['required', 'exists:work_categories,id'],
            // ✅ تصحيح اسم الجدول: كان subscriptions_id يتحقق على subscription33 بالغلط
             'subscriptions_id'   => ['required', 'exists:subscription33,id'],
            'insurance_agent_id' =>$agentRule,
            'status'             => ['nullable', 'in:0,1'],

            'license_number'     => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'commercial_record'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'insurance_agent_id.required' => 'يجب اختيار وكيل تأميني.',
        ]);

        $data = $validated;

      
        if (auth()->user()->hasRole('Wakeel')) {
            $data['status'] = 0;
        } else {
            $data['status'] = array_key_exists('status', $data)
                ? (int) (bool) $data['status']   // يحوّل "on"/"1" لرقم 1، و"0"/null لرقم 0
                : 1;
        }

        // التأكد من وجود مجلد التخزين
        $uploadPath = public_path('institucions_files');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        // حفظ ملف الترخيص
        if ($request->hasFile('license_number')) {
            $file = $request->file('license_number');
            $fileName = time() . '_license_' . $file->getClientOriginalName();
            $file->move($uploadPath, $fileName);
            $data['license_number'] = 'institucions_files/' . $fileName;
        }

        // حفظ ملف السجل التجاري
        if ($request->hasFile('commercial_record')) {
            $file = $request->file('commercial_record');
            $fileName = time() . '_record_' . $file->getClientOriginalName();
            $file->move($uploadPath, $fileName);
            $data['commercial_record'] = 'institucions_files/' . $fileName;
        }

        $model = Institucion::create($data);


         if ($request->hasFile('excel_sheet')) {
            try {
                Excel::import(new InstitucionSheetImport($model->id), $request->file('excel_sheet'));
            } catch (\Throwable $e) {
                return redirect()->route('institucions.show', $model)
                    ->with('warning', 'تم إنشاء جهة العمل، لكن حدث خطأ أثناء استيراد ملف الإكسل: ' . $e->getMessage());
            }
        }
        return redirect()
            ->route('institucions.show', $model)
            ->with('success', 'تمت إضافة جهة العمل بنجاح');
    }


    public function show(Institucion $institucion)
    {
        return view('institucions.show', compact('institucion'));
    }


    public function update(Request $request, Institucion $institucion)
    {
        $validated = $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'work_categories_id' => ['required', 'exists:work_categories,id'],
            'subscriptions_id'   => ['required', 'exists:subscription33,id'],
            'insurance_agent_id' => ['nullable', 'exists:insurance_agents,id'],
            'status'             => ['nullable', 'integer'],

            'commercial_number'  => [
                'nullable','string','max:255',
                Rule::unique('institucions', 'commercial_number')->ignore($institucion->id),
            ],
            'license_number'     => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
            'commercial_record'  => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
        ]);

        $data = $validated;

        // استبدال الملفات عند الرفع (بدون إجبار – الفيو يحدد متى تظهر)
        if ($request->hasFile('license_number')) {
            // حذف القديم إن وجد
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

        return redirect()->route('institucions.show', $institucion)
            ->with('success', 'تم تعديل جهة العمل بنجاح');
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


public function toggleStatus(\App\Models\Institucion $institucion, \Illuminate\Http\Request $request)
{
    // لو الجهة موقوفة (status=0) ونحن "بنفعّل" → نفحص التشابه
    if (!$request->boolean('force') && (int)$institucion->status === 0) {

        $baseNorm   = $this->normalizeName($institucion->name);
        $baseTokens = $this->nameTokens($baseNorm);
        $head       = $baseTokens[0] ?? null;

        $conflicts = [];

        if ($head && mb_strlen($head) >= 2) {
            // Prefilter مرشحين من القاعدة
            $candidates = \App\Models\Institucion::query()
                ->where('id', '!=', $institucion->id)
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
                ->select('id','name')
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
                    ? ($prefixMatch || $contained)                // مثال "مدار" → "المدار الجديد"
                    : ($contained || $overlap >= 80 || $jaccard >= 60);

                if ($similar) {
                    $conflicts[] = [
                        'id'      => $row->id,
                        'name'    => $row->name,
                        'percent' => round(max($overlap, $jaccard), 2),
                    ];
                }
            }
        }

        if (!empty($conflicts)) {
            return back()
                ->with('similar_warning', 'هناك جهات مسجّلة بأسماء مشابهة')
                ->with('similar_conflicts', $conflicts);
        }
    }

    // لو وصلنا هنا: يا إما مافيش تشابه، أو العملية هي "إيقاف"، أو فيه force=1
    $institucion->status = $institucion->status ? 0 : 1;
    $institucion->save();

    // مسح أي رسائل تشابه قديمة من الجلسة (علشان ما تعلّق في الصفحة)
    session()->forget(['similar_warning', 'similar_conflicts']);

    return back()->with('success', $institucion->status ? 'تم تفعيل الجهة' : 'تم إيقاف الجهة');
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




