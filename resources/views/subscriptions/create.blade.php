@extends('layouts.master')

@section('css')
    {{-- خطوط وأيقونات --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
@endsection

@section('title')
    الاشتراكات
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="page-title" style="font-family:'Tajawal',system-ui,-apple-system,Segoe UI,Roboto,sans-serif;">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="mb-0" style="font-weight:800;color:#8C5346;">اضافة اشتراك</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
                    <li class="breadcrumb-item"><a href="" class="default-color">الرئيسية</a></li>
                    <li class="breadcrumb-item active">اضافة اشتراك</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <div class="row small-spacing"
        style="font-family:'Tajawal',system-ui,-apple-system,Segoe UI,Roboto,sans-serif;color:#1F2328;">
        <div class="col-md-12">
            <div class="box-content"></div>
        </div>

        <div class="col-md-8">
            {{-- رسائل النجاح/الفشل --}}
            @if (session('success'))
                <div class="alert alert-success"
                    style="border:1.5px solid #bbf7d0;background:#f0fdf4;padding:12px;border-radius:14px;box-shadow:0 10px 28px rgba(0,0,0,.08);color:#166534;">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                    @if (session('api_response_status'))
                        <div class="mt-1" style="font-size:.9rem;opacity:.8;">
                            كود الاستجابة: {{ session('api_response_status') }}
                        </div>
                    @endif
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger"
                    style="border:1.5px solid #fecaca;background:#fef2f2;padding:12px;border-radius:14px;box-shadow:0 10px 28px rgba(0,0,0,.08);color:#991b1b;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    {{ session('error') }}
                    @if (session('api_error_status'))
                        <div class="mt-1" style="font-size:.9rem;opacity:.8;">
                            كود الاستجابة: {{ session('api_error_status') }}
                        </div>
                    @endif
                    @if (session('api_error_details'))
                        <details class="mt-2">
                            <summary>تفاصيل الخطأ من الـ API</summary>
                            <pre style="white-space:pre-wrap;margin:0">{{ session('api_error_details') }}</pre>
                        </details>
                    @endif
                </div>
            @endif

            {{-- الكارد الرئيسي --}}
            <div class="box-content"
                style="border:1.5px solid #E5E7EB;border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);overflow:hidden;background:#fff;">
                {{-- هيدر الكارد (تدرّج برتقالي) --}}
                <div
                    style="background:linear-gradient(135deg,#d95b00 0%,#F58220 35%,#FF8F34 70%,#ffb066 100%);color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                    <span
                        style="background:#FF8F34;color:#fff;width:34px;height:34px;display:grid;place-items:center;border-radius:999px;font-size:.95rem;box-shadow:0 10px 22px rgba(245,130,32,.35);">
                        <i class="fa-solid fa-layer-group"></i>
                    </span>
                    <h6 style="margin:0;font-weight:800;color:#fff;">تفاصيل الاشتراك</h6>
                </div>

                <div class="p-4" style="padding:22px 20px 26px;">
                    <form method="POST" action="{{ route('subscriptions.store') }}">
                        @csrf

                        {{-- الأخطاء (Validator) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger"
                                style="border:1.5px solid #fecaca;background:#fef2f2;padding:12px;border-radius:14px;box-shadow:0 10px 28px rgba(0,0,0,.08);color:#991b1b;">
                                <ul class="mb-0" style="margin:0;padding-inline-start:22px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- اسم الاشتراك --}}
                        <div class="form-group">
                            <label
                                style="display:block;margin-bottom:6px;color:#6b7280;font-size:.95rem;font-weight:700;">اسم
                                الاشتراك</label>
                            <input type="text" name="name" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                value="{{ old('name') }}" required>
                        </div>

                        {{-- فئة (beneficiaries_categories_id) --}}
                        <div class="form-group">
                            <label
                                style="display:block;margin-bottom:6px;color:#6b7280;font-size:.95rem;font-weight:700;">فئة</label>
                            <select name="beneficiaries_categories_id" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                required>
                                <option value="">اختر الفئة</option>
                                @foreach ($workCategories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('beneficiaries_categories_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr style="border-color:#E5E7EB;">

                        {{-- أنواع الاشتراك --}}
                        @foreach ($types as $type)
                            <h5 class="mt-4" style="margin-top:1rem;font-weight:800;color:#F58220;">{{ $type->name }}
                            </h5>

                            <div class="form-group">
                                <label
                                    style="display:block;margin-bottom:6px;color:#6b7280;font-size:.95rem;font-weight:700;">نوع
                                    القيمة</label>
                                <select name="types[{{ $type->id }}][is_percentage]" class="form-control"
                                    style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;">
                                    <option value="" selected disabled>اختر</option>
                                    <option value="1"
                                        {{ old("types.$type->id.is_percentage") == '1' ? 'selected' : '' }}>نسبة</option>
                                    <option value="0"
                                        {{ old("types.$type->id.is_percentage") == '0' ? 'selected' : '' }}>قيمة ثابتة
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label
                                    style="display:block;margin-bottom:6px;color:#6b7280;font-size:.95rem;font-weight:700;">القيمة</label>
                                <input type="number" step="any" min="0"
                                    name="types[{{ $type->id }}][value]" value="{{ old("types.$type->id.value") }}"
                                    class="form-control"
                                    style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                    placeholder="مثلاً: 5.5">
                            </div>

                            <div class="form-group">
                                <label
                                    style="display:block;margin-bottom:6px;color:#6b7280;font-size:.95rem;font-weight:700;">المدة</label>
                                <input type="number" min="0" name="types[{{ $type->id }}][duration]"
                                    value="{{ old("types.$type->id.duration") }}" class="form-control only-positive"
                                    style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;">
                            </div>

                            {{-- reset قيمة الحقل عند تغيير نوع القيمة --}}
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const select = document.querySelector('select[name="types[{{ $type->id }}][is_percentage]"]');
                                    const input = document.querySelector('input[name="types[{{ $type->id }}][value]"]');
                                    if (select && input) {
                                        select.addEventListener('change', () => {
                                            input.value = '';
                                        });
                                    }
                                });
                            </script>
                        @endforeach

                        {{-- الأزرار --}}
                        <div class="form-group mt-4" style="display:flex;gap:8px;flex-wrap:wrap;">
                            <button type="submit"
                                style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;text-align:center;padding:13px 26px;border-radius:999px;font-weight:900;font-size:1rem;letter-spacing:.3px;background:#F58220;color:#fff;box-shadow:0 12px 26px rgba(245,130,32,.30);">
                                <i class="fa-solid fa-circle-check"></i> حفظ
                            </button>
                            <a href="{{ route('subscriptions.index') }}"
                                style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:13px 26px;font-weight:900;text-decoration:none;">
                                <i class="fa-solid fa-xmark"></i> رجوع
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            {{-- /الكارد --}}
        </div>
    </div>
@endsection

@section('js')
    <script>
        // السماح فقط بالأرقام الموجبة ونقطة عشرية واحدة
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.only-positive').forEach(function(input) {
                input.addEventListener('input', function() {
                    this.value = this.value
                        .replace(/[^\d.]/g, '')
                        .replace(/^0+(\d)/, '$1')
                        .replace(/(\..*)\./g, '$1');
                });
            });
        });
    </script>
@endsection
