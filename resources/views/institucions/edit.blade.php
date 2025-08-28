{{-- resources/views/institucions/edit.blade.php --}}
@extends('layouts.master')

@section('title', 'تعديل جهة عمل')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

@section('content')
    <div class="container py-4"
        style="font-family:'Tajawal',system-ui,-apple-system,Segoe UI,Roboto,sans-serif;color:#8C5346;">

        {{-- العنوان + زر الرجوع --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <h3 style="margin:0;font-weight:800;color:#8C5346;">تعديل جهة عمل</h3>
            </div>
            <a href="{{ route('institucions.show', $institucion) }}"
                style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#6b7280;border:1.5px solid #D0D5DD;border-radius:999px;padding:8px 14px;font-weight:800;text-decoration:none;box-shadow:0 8px 18px rgba(0,0,0,.06);">
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

        <form action="{{ route('institucions.update', $institucion) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- البطاقة 1: الأساسيات --}}
            <div
                style="border:1.5px solid #E5E7EB;border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);margin-bottom:16px;overflow:hidden;background:#fff;">
                <div
                    style="background:linear-gradient(135deg,#d95b00 0%,#F58220 35%,#FF8F34 70%,#ffb066 100%);color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                    <span
                        style="background:#FF8F34;color:#fff;width:34px;height:34px;display:grid;place-items:center;border-radius:999px;font-size:.95rem;box-shadow:0 10px 22px rgba(245,130,32,.35);">1</span>
                    <h6 style="margin:0;font-weight:800;color:#ffffff;">أساسيات جهة العمل</h6>
                </div>

                <div style="padding:22px 20px 26px;">
                    <div class="row g-3">
                        {{-- نوع جهة العمل --}}
                        <div class="col-lg-5">
                            <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">
                                نوع جهة العمل <span style="color:#ef4444;">*</span>
                            </label>
                            <select id="work_categories_id" name="work_categories_id" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                required>
                                <option value="" disabled>— اختر النوع —</option>
                                @foreach ($workCategories as $wc)
                                    @php $requires = in_array($wc->id, $requiresDocsIds ?? []) ? 1 : 0; @endphp
                                    <option value="{{ $wc->id }}" data-requires="{{ $requires }}"
                                        {{ (string) old('work_categories_id', $institucion->work_categories_id) === (string) $wc->id ? 'selected' : '' }}>
                                        {{ $wc->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div style="color:#6b7280;font-size:13px;margin-top:6px;">
                                ستظهر حقول السجل والملفات تلقائيًا إذا كان النوع يتطلبها.
                            </div>
                        </div>

                        {{-- اسم الجهة --}}
                        <div class="col-lg-7">
                            <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">
                                اسم جهة العمل <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="text" name="name" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                value="{{ old('name', $institucion->name) }}" placeholder="أدخل اسم الجهة" required>
                        </div>

                        {{-- الاشتراك --}}
                        <div class="col-md-6">
                            <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">
                                الاشتراك <span style="color:#ef4444;">*</span>
                            </label>
                            <select name="subscriptions_id" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                required>
                                <option value="" disabled>— اختر الاشتراك —</option>
                                @foreach ($subscriptions as $s)
                                    <option value="{{ $s->id }}"
                                        {{ (string) old('subscriptions_id', $institucion->subscriptions_id) === (string) $s->id ? 'selected' : '' }}>
                                        {{ $s->name ?? 'اشتراك #' . $s->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- الوكيل التأميني (اختياري) --}}
                        <div class="col-md-6">
                            <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">
                                الوكيل التأميني (اختياري)
                            </label>
                            <select name="insurance_agent_id" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;">
                                <option value="">— اختياري —</option>
                                @foreach ($agents as $a)
                                    <option value="{{ $a->id }}"
                                        {{ (string) old('insurance_agent_id', $institucion->insurance_agent_id) === (string) $a->id ? 'selected' : '' }}>
                                        {{ $a->name ?? 'Agent #' . $a->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- البطاقة 2: بيانات السجل والترخيص (تظهر حسب النوع) --}}
            <div id="docs-card"
                style="display:none;border:1.5px solid #E5E7EB;border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);margin-bottom:16px;overflow:hidden;background:#fff;">
                <div
                    style="background:linear-gradient(135deg,#d95b00 0%,#F58220 35%,#FF8F34 70%,#ffb066 100%);color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                    <span
                        style="background:#FF8F34;color:#fff;width:34px;height:34px;display:grid;place-items:center;border-radius:999px;font-size:.95rem;box-shadow:0 10px 22px rgba(245,130,32,.35);">2</span>
                    <h6 style="margin:0;font-weight:800;color:#ffffff;">بيانات السجل التجاري والترخيص</h6>
                </div>

                <div style="padding:22px 20px 26px;">
                    <div class="row g-3">
                        {{-- الرقم التجاري --}}
                        <div class="col-md-6">
                            <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">الرقم
                                التجاري</label>
                            <input type="text" name="commercial_number" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                value="{{ old('commercial_number', $institucion->commercial_number) }}"
                                placeholder="مثال: 123456789">
                        </div>

                        {{-- ملف الترخيص --}}
                        <div class="col-md-6">
                            <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">ملف
                                الترخيص</label>
                            @if ($institucion->license_number)
                                <div class="mb-1">
                                    <a href="{{ Storage::url($institucion->license_number) }}" target="_blank"
                                        style="color:#1d4ed8;text-decoration:underline;">
                                        عرض الملف الحالي
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="license_number" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                accept=".pdf,.jpg,.jpeg,.png">
                        </div>

                        {{-- ملف السجل التجاري --}}
                        <div class="col-md-6">
                            <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">ملف السجل
                                التجاري</label>
                            @if ($institucion->commercial_record)
                                <div class="mb-1">
                                    <a href="{{ asset('insurancagents_files/' . $institucion->commercial_record) }}"
                                        style="color:#1d4ed8;text-decoration:underline;">
                                        عرض الملف الحالي
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="commercial_record" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
            </div>

            {{-- الأزرار --}}
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button type="submit"
                    onmouseover="this.style.filter='brightness(1.03)'; this.style.transform='translateY(-1px)';"
                    onmouseout="this.style.filter='none'; this.style.transform='none';"
                    style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;text-align:center;padding:13px 26px;border-radius:999px;font-weight:900;font-size:1rem;letter-spacing:.3px;
                   background:#F58220;color:#fff;box-shadow:0 12px 26px rgba(245,130,32,.30);">
                    حفظ التعديلات
                    <i class="fa-solid fa-circle-check"></i> {{-- ✅ هنا بدل floppy-disk --}}
                </button>


            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const select = document.getElementById('work_categories_id');
            const docsCard = document.getElementById('docs-card');

            // نفس منطق الإظهار/الإخفاء مع ضبط required للحقول المتعلقة
            function toggleDocs() {
                const opt = select.options[select.selectedIndex];
                const requires = opt ? opt.getAttribute('data-requires') === '1' : false;

                docsCard.style.display = requires ? '' : 'none';

                const commercial = document.querySelector('[name="commercial_number"]');
                const licFile = document.querySelector('[name="license_number"]');
                const crFile = document.querySelector('[name="commercial_record"]');

                [commercial, licFile, crFile].forEach(function(el) {
                    if (!el) return;
                    if (requires) el.setAttribute('required', 'required');
                    else el.removeAttribute('required');
                });
            }

            select.addEventListener('change', toggleDocs);
            toggleDocs();
        })();
    </script>
@endpush
