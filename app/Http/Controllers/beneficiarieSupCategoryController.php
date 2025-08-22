<?php

namespace App\Http\Controllers;
use App\Models\beneficiariesCategories;
use App\Models\beneficiariesSupCategories;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; 


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
        $data = $request->validate([
            'name'   => ['required','string','max:255'],
            'type'   => ['required','string','max:100'],
            // أرقام فقط حتى 5 خانات
            'code'   => ['required','string','regex:/^\d{1,5}$/','max:5',
                         'unique:beneficiaries_sup_categories,code'],
            'beneficiaries_categories_id' => ['required','exists:beneficiaries_categories,id'],
            'status' => ['required', Rule::in([0,1])],
        ], [
            'code.regex' => ' يجب أن يكون أرقامًا فقط حتى  خانتين.',
        ]);

        beneficiariesSupCategories::create($data);

        return redirect()->route('beneficiaries-sup-categories.index')
            ->with('success','تم إضافة الفئة الفرعية بنجاح.');
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
        $item = beneficiariesCategories::findOrFail($id);

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


