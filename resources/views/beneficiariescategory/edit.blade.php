@extends('layouts.master')

@section('title', 'تعديل الفئة')

@section('content')
<div class="container py-4" style="font-family: sans-serif;">

  {{-- العنوان + رجوع --}}
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
    <div>
      <h3 style="margin:0;font-weight:800;color:#111827;">تعديل الفئة: {{ $item->name }}</h3>
      <div style="color:#6b7280;font-size:14px;">عدّل بيانات الفئة ثم احفظ التغييرات.</div>
    </div>
    <a href="{{ route('beneficiariescategory.index') }}" 
       style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:6px 14px;font-weight:700;text-decoration:none;">
      <i class="fa fa-arrow-right"></i> رجوع للقائمة
    </a>
  </div>

  {{-- الأخطاء --}}
  @if ($errors->any())
    <div style="border:1.5px solid #fecaca;background:#fff5f5;padding:12px;border-radius:8px;margin-bottom:16px;">
      <div style="font-weight:700;margin-bottom:6px;">تحقق من الحقول التالية:</div>
      <ul style="margin:0;padding-left:20px;">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('beneficiariescategory.update', $item->id) }}">
    @csrf @method('PUT')

    <div style="border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);margin-bottom:16px;overflow:hidden;">
      <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid #D0D5DD;padding:10px 14px;display:flex;align-items:center;gap:8px;">
        <span style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid #FFD8A8;color:#92400E;font-weight:800;">1</span>
        <h6 style="margin:0;font-weight:800;color:#374151;">بيانات الفئة</h6>
      </div>

      <div style="padding:16px;">
        <div class="row g-3">
          <div class="col-md-6">
            <label style="font-weight:700;color:#374151;">اسم الفئة <span style="color:red;">*</span></label>
            <input type="text" name="name" class="form-control"
                   style="border:1.5px solid #E5E7EB;"
                   value="{{ old('name', $item->name) }}" placeholder="اسم الفئة" required>
          </div>

          <div class="col-md-6">
            <label style="font-weight:700;color:#374151;">الكود <span style="color:red;">*</span></label>
            <input type="text" name="code" class="form-control"
                   style="border:1.5px solid #E5E7EB;"
                   value="{{ old('code', $item->code) }}" placeholder="الكود (أرقام فقط حتى 5)" maxlength="5"
                   onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
          </div>

          <div class="col-md-12">
            <label style="font-weight:700;color:#374151;display:block;margin-bottom:6px;">الحالة</label>
            <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;">
              <label style="display:inline-flex;gap:6px;align-items:center;">
                <input type="radio" name="status" value="1" {{ old('status', $item->status) == 1 ? 'checked' : '' }}>
                <span>مفعّلة</span>
              </label>
              <label style="display:inline-flex;gap:6px;align-items:center;">
                <input type="radio" name="status" value="0" {{ old('status', $item->status) == 0 ? 'checked' : '' }}>
                <span>موقوفة</span>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- الأزرار --}}
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <button type="submit" 
              style="display:inline-flex;align-items:center;gap:6px;background:#FFF7EE;color:#92400E;border:1.5px solid #FFD8A8;border-radius:999px;padding:8px 18px;font-weight:800;cursor:pointer;">
        <i class="fa fa-save"></i> حفظ التعديلات
      </button>
      <a href="{{ route('beneficiariescategory.index') }}" 
         style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:8px 18px;font-weight:800;text-decoration:none;">
        إلغاء
      </a>
    </div>

  </form>
</div>
@endsection
