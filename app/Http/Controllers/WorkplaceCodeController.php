<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\WorkplaceCode;



class WorkplaceCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
               return view('workplace_codes.create');
    }


    // عرض صفحة إدخال الفرعي
    public function createChild()
    {
      
        // نجيب كل التصنيفات الأساسية (اللي parent_id = null)
        $parents = WorkplaceCode::whereNull('parent_id')->get();
        return view('workplace_codes.createchild', compact('parents'));
    }

    // تخزين التصنيف (أساسي أو فرعي)
  public function store(Request $request)
{
    $request->validate([
        'name'      => 'required|string|max:255',
        'code'      => 'required|string|max:50|unique:workplace_codes,code',
        'parent_id' => 'nullable|exists:workplace_codes,id'
    ], [
        'name.required' => '⚠️ الاسم مطلوب',
        'code.required' => '⚠️ الترميز مطلوب',
        'code.unique'   => '⚠️ هذا الترميز موجود مسبقًا',
    ]);

    // ✅ نجيب كود الأب من قاعدة البيانات (لو فيه أب مختار)
    $parentCode = null;
    if ($request->filled('parent_id')) {
        $parentCode = WorkplaceCode::where('id', $request->parent_id)->value('code');
    }

    // ✅ ننظف الكود بحيث نخزن بس الجزء اللي كتبه المستخدم
    $cleanCode = $request->code;
    if ($parentCode && str_starts_with($cleanCode, $parentCode)) {
        $cleanCode = trim(substr($cleanCode, strlen($parentCode)));
    }

    WorkplaceCode::create([
        'name'      => $request->name,
        'code'      => $cleanCode,        // نخزن المدخل فقط
        'parent_id' => $request->parent_id,
    ]);

    return redirect()->route('home')->with('success', '✅ تمت الإضافة بنجاح');
}


public function children($parentId)
{
    return WorkplaceCode::where('parent_id', $parentId)
        ->get(['id', 'name', 'code']);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
