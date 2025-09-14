<?php

namespace App\Http\Controllers;
use App\Models\beneficiariesCategories;   
use Illuminate\Validation\Rule; 
 use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;




class beneficiariesCategoriesController extends Controller
{


    // قائمة الفئات
    public function index()
    {
        // بصفحات بسيطة؛ عدّل الرقم حسب حاجتك
        $items = beneficiariesCategories::orderByDesc('id')->paginate(15);
        return view('beneficiariescategory.index', compact('items'));
    }

    // شاشة الإضافة
    public function create()
    {
        return view('beneficiariescategory.create');
    }

    // حفظ سجل جديد
     public function store(Request $request)
    {
        $data = $request->validate([
        'name'   => ['required','string','max:255'],
        'code'   => [
        'required',
        'string',
        'regex:/^\d{1,5}$/',
        'max:5',
        'unique:beneficiaries_categories,code'
         ],
           'status' => ['required', Rule::in([0,1])],],  
        [
        'code.regex'  => 'الكود يجب أن يكون أرقامًا فقط حتى 5 خانات.',
        'code.unique' => 'هذا الكود مستخدم بالفعل، من فضلك أدخل كود آخر.',
        'name.required' => 'الاسم مطلوب.',
        'code.required' => 'الرمز مطلوب.',
        'status.required' => 'الحالة مطلوبة.',
        ]);

            //  $result = $this->postWorkCategoryToApi($request->name, 1);


        beneficiariesCategories::create($data);

       Alert::success('تمت العملية', 'تم إضافة الفئة بنجاح.');

        return redirect()->route('beneficiariescategory.index');
    }






    //   public function postWorkCategoryToApi($id, $name, $status)
    // {
    //     $payload = [
    //         'id'     => $id,
    //         'name'   => $name,
    //         'status' => (int) $status,
    //     ];

    //     $response = Http::withHeaders([
    //         'accept' => 'application/json',
    //         'Authorization' => 'Basic ' . base64_encode('admin:admin'),
    //         'Content-Type' => 'application/json',
    //     ])->post('http://192.168.81.17:6060/admin/WorkCategorys', $payload);

    //     if ($response->successful()) {
    //         return $response->json();
    //     }

    //     return [
    //         'success' => false,
    //         'status'  => $response->status(),
    //         'error'   => $response->body(),
    //         'sent'    => $payload, // نطبع البيانات المرسلة للديباغ
    //     ];
    // }







    //    public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'name'   => ['required','string','max:255'],
    //         'code'   => ['required','string','regex:/^\d{1,5}$/', 'max:5',
    //                      'unique:beneficiaries_categories,code'],
    //         'status' => ['required', Rule::in([0,1])],
    //     ], [
    //         'code.regex' => 'الكود يجب أن يكون أرقامًا فقط حتى 5 خانات.',
    //     ]);


    //     $benef = beneficiariesCategories::create($data);
    //   return  $result = $this->postWorkCategoryToApi(
    //     $benef->id,        // id من قاعدة البيانات
    //     $benef->name,      // الاسم
    //     $benef->status     // الحالة
    // );


    //     return redirect()
    //         ->route('beneficiariescategory.index')
    //         ->with('success', 'تم إضافة الفئة بنجاح.');
    // }
    //  public function postWorkCategoryToApi($name, $status = 0)
    // {
    //     $response = Http::withHeaders([
    //         'accept' => 'application/json',
    //         'Authorization' => 'Basic ' . base64_encode('admin:admin'),
    //         'Content-Type' => 'application/json',
    //     ])->post('http://192.168.81.17:6060/admin/WorkCategorys', [
            
    //         'name' => $name,
    //         'status' => $status,
    //     ]);

    //     if ($response->successful()) {
    //         return $response->json(); // أو true إذا ما تحتاج البيانات
    //     }

    //     // لعرض الخطأ إذا فشل الطلب
    //     return [
    //         'success' => false,
    //         'status' => $response->status(),
    //         'error' => $response->body(),
    //     ];
    // }

    // عرض سجل واحد (اختياري)
    public function show($id)
    {
        $item = beneficiariesCategories::findOrFail($id);
        return view('beneficiariescategory.show', compact('item'));
    }

    // شاشة التعديل
    public function edit($id)
    {
        $item = beneficiariesCategories::findOrFail($id);
        return view('beneficiariescategory.edit', compact('item'));
    }

    // تحديث السجل
    public function update(Request $request, $id)
    {
        $item = beneficiariesCategories::findOrFail($id);

        $data = $request->validate([
            'name'   => ['required','string','max:255'],
            'code'   => [
                'required','string','regex:/^\d{1,5}$/','max:5',
                Rule::unique('beneficiaries_categories','code')->ignore($item->id),
            ],
            'status' => ['required', Rule::in([0,1])],
        ], [
            'code.regex' => 'الكود يجب أن يكون أرقامًا فقط حتى 5 خانات.',
        ]);

        $item->update($data);

        return redirect()
            ->route('beneficiariescategory.index')
            ->with('success', 'تم تحديث الفئة بنجاح.');
    }

    // حذف السجل
    public function destroy($id)
    {
        $item = beneficiariesCategories::findOrFail($id);
        $item->delete();

        return redirect()
            ->route('beneficiariescategory.index')
            ->with('success', 'تم حذف الفئة بنجاح.');
    }
}



