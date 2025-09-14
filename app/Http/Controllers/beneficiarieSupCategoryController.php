<?php

namespace App\Http\Controllers;
use App\Models\beneficiariesCategories;
use App\Models\beneficiariesSupCategories;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; 
use RealRashid\SweetAlert\Facades\Alert;



class beneficiarieSupCategoryController extends Controller
{

    public function index()
    {
        $q = request('q');
        $items = beneficiariesSupCategories::with('category')
            ->when($q, fn($qr)=>$qr->where('name','like',"%$q%"))
            ->orderByDesc('id')->get();

        return view('beneficiaries_sup_categories.index', compact('items'));
    }

    public function create()
    {
        $mainCategories = beneficiariesCategories::orderBy('name')->get();
        return view('beneficiaries_sup_categories.create', compact('mainCategories'));
    }

   
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name'   => ['required','string','max:255'],
                'type'   => ['required','string','max:100'],
                'code'   => [
                    'required',
                    'string',
                    'regex:/^\d{1,5}$/',
                    'max:5',
                    'unique:beneficiaries_sup_categories,code'
                ],
                'beneficiaries_categories_id' => ['required','exists:beneficiaries_categories,id'],
                'status' => ['required', Rule::in([0,1])],
            ], [
                'name.required'   => 'اسم الفئة مطلوب.',
                'type.required'   => 'نوع الفئة مطلوب.',
                'code.required'   => 'الرمز مطلوب.',
                'code.regex'      => 'الرمز يجب أن يكون أرقامًا فقط حتى 5 خانات.',
                'code.unique'     => 'هذا الرمز مستخدم بالفعل، يرجى إدخال رمز آخر.',
                'beneficiaries_categories_id.required' => 'الفئة الرئيسية مطلوبة.',
                'beneficiaries_categories_id.exists'   => 'الفئة الرئيسية غير موجودة.',
                'status.required' => 'الحالة مطلوبة.',
                'status.in'       => 'الحالة يجب أن تكون إما مفعلة أو غير مفعلة.',
            ]);
    
            beneficiariesSupCategories::create($data);
    
            // ✅ أليرت نجاح
            Alert::success('تمت العملية بنجاح', 'تم إضافة الفئة الفرعية.');
    
            return redirect()->route('beneficiaries-sup-categories.index');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // ✅ أليرت خطأ بالرسائل العربية
            Alert::error('خطأ في الإدخال', implode(' | ', $e->validator->errors()->all()));
            return back()->withErrors($e->validator)->withInput();
        }
    }

    public function show($id)
    {
        $item = beneficiariesSupCategories::with('category')->findOrFail($id);
        return view('beneficiaries_sup_categories.show', compact('item'));
    }

    public function edit($id)
    {
        $item = beneficiariesSupCategories::findOrFail($id);
        $mainCategories = beneficiariesCategories::orderBy('name')->get();
        return view('beneficiaries_sup_categories.edit', compact('item','mainCategories'));
    }

    public function update(Request $request, $id)
    {
        $item = beneficiariesSupCategories::findOrFail($id);

        $data = $request->validate([
            'name'   => ['required','string','max:255'],
            'type'   => ['required','string','max:100'],
            'code'   => [
                'required','string','regex:/^\d{1,5}$/','max:5',
                Rule::unique('beneficiaries_sup_categories','code')->ignore($item->id),
            ],
            'beneficiaries_categories_id' => ['required','exists:beneficiaries_categories,id'],
            'status' => ['required', Rule::in([0,1])],
        ], [
            'code.regex' => 'الكود يجب أن يكون أرقامًا فقط حتى 5 خانات.',
        ]);

        $item->update($data);

        return redirect()->route('beneficiaries-sup-categories.index')
            ->with('success','تم تحديث الفئة الفرعية بنجاح.');
    }

    public function destroy($id)
    {
        $item = beneficiariesSupCategories::findOrFail($id);
        $item->delete();

        return redirect()->route('beneficiaries-sup-categories.index')
            ->with('success','تم حذف الفئة الفرعية بنجاح.');
    }
}


