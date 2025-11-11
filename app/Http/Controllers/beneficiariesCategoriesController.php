<?php

namespace App\Http\Controllers;
use App\Models\beneficiariesCategories;   
use Illuminate\Validation\Rule; 
 use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;




class beneficiariesCategoriesController extends Controller
{


    public function index()
    {
        $items = beneficiariesCategories::orderByDesc('id')->paginate(15);
        return view('beneficiariescategory.index', compact('items'));
    }

    public function create()
    {
        return view('beneficiariescategory.create');
    }

    // حفظ سجل جديد
    //  public function store(Request $request)
    // {
    //     $data = $request->validate([
    //     'name'   => ['required','string','max:255'],
    //     'code'   => [
    //     'required',
    //     'string',
    //     'regex:/^\d{1,5}$/',
    //     'max:5',
    //     'unique:beneficiaries_categories,code'
    //      ],
    //        'status' => ['required', Rule::in([0,1])],],  
    //     [
    //     'code.regex'  => 'الكود يجب أن يكون أرقامًا فقط حتى 5 خانات.',
    //     'code.unique' => 'هذا الكود مستخدم بالفعل، من فضلك أدخل كود آخر.',
    //     'name.required' => 'الاسم مطلوب.',
    //     'code.required' => 'الرمز مطلوب.',
    //     'status.required' => 'الحالة مطلوبة.',
    //     ]);

    //         //  $result = $this->postWorkCategoryToApi($request->name, 1);


    //     beneficiariesCategories::create($data);

    //    Alert::success('تمت العملية', 'تم إضافة الفئة بنجاح.');

    //     return redirect()->route('beneficiariescategory.index');
    // }





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
        'status' => ['required', Rule::in([0,1])],
    ], [
        'code.regex'  => 'الكود يجب أن يكون أرقامًا فقط حتى 5 خانات.',
        'code.unique' => 'هذا الكود مستخدم بالفعل، من فضلك أدخل كود آخر.',
        'name.required' => 'الاسم مطلوب.',
        'code.required' => 'الرمز مطلوب.',
        'status.required' => 'الحالة مطلوبة.',
    ]);

    $category = beneficiariesCategories::create($data);

    $this->postWorkCategoryToApi($category->id, $category->name, $category->status);


    Alert::success('تمت العملية', 'تم إضافة الفئة بنجاح.');

    return redirect()->route('beneficiariescategory.index');
}



   
    public function postWorkCategoryToApi($id, $name, $status)
    {
        $payload = [
            'id'     => (int) $id,
            'name'   => $name,
            'status' => (int) $status,
        ];

        $response = Http::withBasicAuth('admin', 'admin')
            ->acceptJson()
            ->post('http://192.168.81.17:6060/admin/WorkCategorys', $payload);

        if ($response->successful()) {
            \Log::info("✅ WorkCategory #{$id} sent successfully to external API.");
            return ['success' => true, 'status' => $response->status(), 'data' => $response->json()];
        }


        \Log::warning("⚠️ WorkCategory #{$id} send failed, status: " . $response->status());
        return [
            'success' => false,
            'status'  => $response->status(),
            'error'   => $response->body(),
            'sent'    => $payload,
        ];

    }





      public function show($id)
    {
        $item = beneficiariesCategories::findOrFail($id);
        return view('beneficiariescategory.show', compact('item'));
    }

    public function edit($id)
    {
        $item = beneficiariesCategories::findOrFail($id);
        return view('beneficiariescategory.edit', compact('item'));
    }

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
     try {
        $this->updateWorkCategoryToApi($item->id, $item->name, $item->status);
    } catch (\Throwable $e) {
        \Log::error("❌ WorkCategory update to API failed for ID {$item->id}: " . $e->getMessage());
    }

        return redirect()
            ->route('beneficiariescategory.index')
            ->with('success', 'تم تحديث الفئة بنجاح.');
    }


public function updateWorkCategoryToApi($id, $name, $status)
{
    $payload = [
        'id'     => $id,
        'name'   => $name,
        'status' => (int) $status,
    ];

    $url = "http://192.168.81.17:6060/admin/WorkCategorys/{$id}";

    $response = Http::withBasicAuth('admin', 'admin')
        ->withHeaders([
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
        ->put($url, $payload);

    if ($response->successful()) {
        \Log::info("✅ WorkCategory #{$id} updated successfully in external API.");
        return [
            'success' => true,
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }

    \Log::warning("⚠️ WorkCategory #{$id} update failed in API. Status: {$response->status()}");
    return [
        'success' => false,
        'status' => $response->status(),
        'error' => $response->body(),
        'sent' => $payload,
    ];
}



    public function destroy($id)
    {
        $item = beneficiariesCategories::findOrFail($id);
        $item->delete();

        return redirect()
            ->route('beneficiariescategory.index')
            ->with('success', 'تم حذف الفئة بنجاح.');
    }
}



