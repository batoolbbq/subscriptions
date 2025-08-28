@extends('layouts.master')
@section('title', 'إضافة فئة فرعية')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

@section('content')
    <div class="container py-4"
        style="font-family: 'Tajawal', system-ui, -apple-system, Segoe UI, Roboto, sans-serif; color:#8C5346;">

        {{-- الهيدر --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <h3 style="margin:0;font-weight:800;color:#8C5346;">إضافة فئة فرعية جديدة</h3>
                <br>
                <div style="color:#6b7280;font-size:14px;">قم بملء الحقول المطلوبة لإضافة فئة فرعية جديدة.</div>
                </br>

            </div>
            <a href="{{ route('beneficiaries-sup-categories.index') }}"
                style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:8px 14px;font-weight:800;text-decoration:none;box-shadow:0 8px 18px rgba(0,0,0,.06);">
                <i class="fa fa-arrow-right"></i> رجوع للقائمة
            </a>
        </div>

        {{-- رسائل الأخطاء --}}
        @if ($errors->any())
            <div
                style="border:1.5px solid #fecaca;background:#fef2f2;padding:12px;border-radius:14px;margin-bottom:16px;box-shadow:0 10px 28px rgba(0,0,0,.08);color:#991b1b;">
                <div style="font-weight:800;margin-bottom:6px;">تحقق من الحقول التالية:</div>
                <ul style="margin:0;padding-inline-start:22px;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- الفورم --}}
        <form method="POST" action="{{ route('beneficiaries-sup-categories.store') }}">
            @csrf

            {{-- البطاقة --}}
            <div
                style="border:1.5px solid #E5E7EB;border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);margin-bottom:16px;overflow:hidden;background:#fff;">

                {{-- هيدر البطاقة --}}
                <div
                    style="background:linear-gradient(135deg,#d95b00 0%,#F58220 35%,#FF8F34 70%,#ffb066 100%);
                  color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                    <span
                        style="background:#FF8F34;color:#fff;width:34px;height:34px;display:grid;place-items:center;border-radius:999px;font-size:.95rem;box-shadow:0 10px 22px rgba(245,130,32,.35);">1</span>
                    <h6 style="margin:0;font-weight:800;color:#ffffff;">بيانات الفئة الفرعية</h6>
                </div>

                {{-- المحتوى --}}
                <div style="padding:22px 20px 26px;">
                    <div class="mb-3">
                        <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">اسم الفئة الفرعية
                            <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" class="form-control"
                            style="width:100%;border:1.5px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                            value="{{ old('name') }}" maxlength="255" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">النوع <span
                                style="color:#ef4444;">*</span></label>
                        <input type="text" name="type" class="form-control"
                            style="width:100%;border:1.5px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                            value="{{ old('type') }}" maxlength="100" required>
                    </div>

                    <div class="mb-3">
                        <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">الكود (أرقام فقط
                            حتى 5) <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="code" class="form-control"
                            style="width:100%;border:1.5px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                            maxlength="5" value="{{ old('code') }}"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                    </div>

                    <div class="mb-3">
                        <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">الفئة الرئيسية
                            <span style="color:#ef4444;">*</span></label>
                        <select name="beneficiaries_categories_id" class="form-control"
                            style="width:100%;border:1.5px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                            required>
                            <option value="">— اختر الفئة الرئيسية —</option>
                            @foreach ($mainCategories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('beneficiaries_categories_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">الحالة</label>
                        <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;">
                            <label style="display:inline-flex;gap:6px;align-items:center;">
                                <input type="radio" name="status" value="1"
                                    {{ old('status', 1) == 1 ? 'checked' : '' }}>
                                <span>مفعّلة</span>
                            </label>
                            <label style="display:inline-flex;gap:6px;align-items:center;">
                                <input type="radio" name="status" value="0"
                                    {{ old('status') == 0 ? 'checked' : '' }}>
                                <span>موقوفة</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- الأزرار --}}
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button type="submit"
                    style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;text-align:center;padding:13px 26px;border-radius:999px;font-weight:900;font-size:1rem;letter-spacing:.3px;
                     background:#F58220;color:#fff;box-shadow:0 12px 26px rgba(245,130,32,.30);">
                    حفظ الفئة الفرعية
                    <i class="fa-solid fa-circle-check"></i>
                </button>

                <a href="{{ route('beneficiaries-sup-categories.index') }}"
                    style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:13px 26px;font-weight:900;text-decoration:none;">
                    إلغاء
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>

        </form>
    </div>
@endsection
