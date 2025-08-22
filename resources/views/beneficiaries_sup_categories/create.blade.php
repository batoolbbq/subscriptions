@extends('layouts.master')
@section('title','إضافة فئة فرعية')

@section('content')
<div class="container py-4" style="--ink:#111827;--line:#e5e7eb;--blue-50:#f3f6ff;--blue-200:#cfd8ff;--blue-700:#1d4ed8;--amber-50:#fff5e6;--amber-200:#ffd8a8;--amber-800:#92400e;--red-50:#fff1f1;--red-200:#ffc9c9;--red-700:#b42318;--gray-50:#eff2f6;--gray-700:#374151;">

  {{-- الهيدر --}}
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
    <div>
      <h3 style="margin:0;font-weight:800;color:var(--ink);">إضافة فئة فرعية جديدة</h3>
      <div style="color:#6b7280;font-size:14px;">قم بملء الحقول المطلوبة لإضافة فئة فرعية جديدة.</div>
    </div>
    <a href="{{ route('beneficiaries-sup-categories.index') }}" style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:var(--ink);border:1.5px solid var(--line);border-radius:999px;padding:6px 14px;font-weight:700;text-decoration:none;">
      <i class="fa fa-arrow-right"></i> رجوع للقائمة
    </a>
  </div>

  {{-- رسائل الأخطاء --}}
  @if ($errors->any())
    <div style="border:1.5px solid var(--red-200);background:var(--red-50);padding:12px;border-radius:8px;margin-bottom:16px;">
      <div style="font-weight:700;margin-bottom:6px;">تحقق من الحقول التالية:</div>
      <ul style="margin:0;padding-left:20px;">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- الفورم --}}
  <form method="POST" action="{{ route('beneficiaries-sup-categories.store') }}">
    @csrf

    <div style="border:1.5px solid var(--line);border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);margin-bottom:16px;overflow:hidden;">
      
      {{-- هيدر البطاقة --}}
      <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid var(--line);padding:10px 14px;display:flex;align-items:center;gap:8px;">
        <span style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid var(--amber-200);color:var(--amber-800);font-weight:800;">1</span>
        <h6 style="margin:0;font-weight:800;color:var(--gray-700);">بيانات الفئة الفرعية</h6>
      </div>

      {{-- المحتوى --}}
      <div style="padding:16px;">
        <div class="mb-3">
          <label style="font-weight:700;color:var(--gray-700);">اسم الفئة الفرعية <span style="color:red;">*</span></label>
          <input type="text" name="name" class="form-control" style="border:1.5px solid #E5E7EB;" value="{{ old('name') }}" maxlength="255" required autofocus>
        </div>

        <div class="mb-3">
          <label style="font-weight:700;color:var(--gray-700);">النوع <span style="color:red;">*</span></label>
          <input type="text" name="type" class="form-control" style="border:1.5px solid #E5E7EB;" value="{{ old('type') }}" maxlength="100" required>
        </div>

        <div class="mb-3">
          <label style="font-weight:700;color:var(--gray-700);">الكود (أرقام فقط حتى 5) <span style="color:red;">*</span></label>
          <input type="text" name="code" class="form-control" style="border:1.5px solid #E5E7EB;" maxlength="5" value="{{ old('code') }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
        </div>

        <div class="mb-3">
          <label style="font-weight:700;color:var(--gray-700);">الفئة الرئيسية <span style="color:red;">*</span></label>
          <select name="beneficiaries_categories_id" class="form-control" style="border:1.5px solid #E5E7EB;" required>
            <option value="">— اختر الفئة الرئيسية —</option>
            @foreach($mainCategories as $cat)
              <option value="{{ $cat->id }}" {{ old('beneficiaries_categories_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label style="font-weight:700;color:var(--gray-700);display:block;">الحالة</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ old('status', 1) == 1 ? 'checked' : '' }}>
            <label class="form-check-label" for="status_active">مفعّلة</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ old('status') == 0 ? 'checked' : '' }}>
            <label class="form-check-label" for="status_inactive">موقوفة</label>
          </div>
        </div>
      </div>
    </div>

    {{-- الأزرار --}}
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <button type="submit" style="display:inline-flex;align-items:center;gap:6px;background:var(--amber-50);color:var(--amber-800);border:1.5px solid var(--amber-200);border-radius:999px;padding:8px 18px;font-weight:800;">
        <i class="fa fa-save"></i> حفظ
      </button>
      <a href="{{ route('beneficiaries-sup-categories.index') }}" style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:var(--ink);border:1.5px solid var(--line);border-radius:999px;padding:8px 18px;font-weight:800;text-decoration:none;">
        إلغاء
      </a>
    </div>

  </form>
</div>
@endsection
