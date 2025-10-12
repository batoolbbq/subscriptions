@extends('layouts.master')


@section('title', 'إضافة جهة عمل')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@section('content')
    <div class="container py-4" style="font-family:'Tajawal',sans-serif;color:#8C5346;">

        {{-- العنوان --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 style="margin:0;font-weight:800;">إضافة جهة عمل</h3>
            <a href="{{ route('institucions.index') }}" class="btn btn-light"
                style="border:none;border-radius:999px;padding:10px 20px;box-shadow:0 3px 8px rgba(0,0,0,.1);">
                <i class="fa fa-arrow-right"></i> رجوع للقائمة
            </a>
        </div>

        {{-- رسائل الأخطاء --}}
        @if ($errors->any())
            <div style="background:#ffe5e5;color:#991b1b;padding:12px 18px;border-radius:16px;margin-bottom:18px;">
                <strong>تحقق من الحقول التالية:</strong>
                <ul style="margin:0;padding-inline-start:20px;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('institucions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="excel_rows" id="excel_rows">

            {{-- البطاقة 1 --}}
            <div style="background:#fff;border-radius:24px;box-shadow:0 10px 24px rgba(0,0,0,.08);margin-bottom:24px;">
                <div
                    style="background:linear-gradient(135deg,#d95b00,#F58220,#FF8F34,#ffb066);
                        color:#fff;padding:14px 18px;font-weight:800;border-radius:24px 24px 0 0;">
                    <span style="background:#FF8F34;padding:5px 11px;border-radius:50%;">1</span> أساسيات جهة العمل
                </div>

                <div style="padding:24px 20px;">
                    <div class="row g-3">

                        {{-- نوع الجهة --}}
                        <div class="col-lg-5">
                            <label class="fw-bold mb-2">نوع جهة العمل <span style="color:#ef4444;">*</span></label>
                            <select id="work_categories_id" name="work_categories_id" class="form-control clean-input"
                                required>
                                <option value="" disabled selected>— اختر النوع —</option>
                                @foreach ($workCategories as $wc)
                                    @php
                                        $requires = in_array($wc->id, $requiresDocsIds ?? []) ? 1 : 0;
                                    @endphp
                                    <option value="{{ $wc->id }}" data-requires="{{ $requires }}">
                                        {{ $wc->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- اسم الجهة --}}
                        <div class="col-lg-7">
                            <label class="fw-bold mb-2">اسم جهة العمل <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="name" class="form-control clean-input"
                                value="{{ old('name') }}" placeholder="أدخل اسم الجهة" required>
                        </div>

                        {{-- الترميز --}}
                        @role('insurance-manager|admin')
                            <div class="col-md-12 mt-3">
                                <label class="fw-bold mb-2">الترميز (اختياري)</label>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <select id="main-code" name="parent_id" class="form-control clean-input">
                                            <option value="">اختر التصنيف الرئيسي</option>
                                            @foreach ($parents as $p)
                                                <option value="{{ $p->id }}" data-code="{{ $p->code }}">
                                                    {{ $p->name }} ({{ $p->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select id="child-code" name="child_id" class="form-control clean-input" disabled>
                                            <option value="">اختر التصنيف الفرعي</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" id="final-code" name="code" class="form-control clean-input"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        @endrole

                        {{-- الوكيل التأميني --}}
                        @role('insurance-manager')
                            <div class="col-md-6 mt-3">
                                <label class="fw-bold mb-2">الوكيل التأميني (اختياري)</label>
                                <select name="insurance_agent_id" id="insurance_agent_id"
                                    class="form-control select2-agent clean-input">
                                    <option value="">— اختياري —</option>
                                    @foreach ($agents as $a)
                                        <option value="{{ $a->id }}">{{ $a->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="insurance_agent_id" value="{{ $preselectedAgentId }}">
                        @endrole

                    </div>
                </div>
            </div>

            {{-- البطاقة 2 --}}
            <div id="docs-card"
                style="display:none;background:#fff;border-radius:24px;box-shadow:0 10px 24px rgba(0,0,0,.08);margin-bottom:24px;">
                <div
                    style="background:linear-gradient(135deg,#d95b00,#F58220,#FF8F34,#ffb066);
                        color:#fff;padding:14px 18px;font-weight:800;border-radius:24px 24px 0 0;">
                    <span style="background:#FF8F34;padding:5px 11px;border-radius:50%;">2</span> بيانات السجل التجاري
                    والترخيص
                </div>

                <div style="padding:24px 20px;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">الرقم التجاري</label>
                            <input type="text" name="commercial_number" class="form-control clean-input"
                                value="{{ old('commercial_number') }}" placeholder="مثال: 123456789">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">ملف الترخيص</label>
                            <input type="file" name="license_number" class="form-control clean-input"
                                accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">ملف السجل التجاري</label>
                            <input type="file" name="commercial_record" class="form-control clean-input"
                                accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
            </div>

            {{-- البطاقة 3 --}}
            <div style="background:#fff;border-radius:24px;box-shadow:0 10px 24px rgba(0,0,0,.08);margin-bottom:24px;">
                <div
                    style="background:linear-gradient(135deg,#d95b00,#F58220,#FF8F34,#ffb066);
                        color:#fff;padding:14px 18px;font-weight:800;border-radius:24px 24px 0 0;">
                    <span style="background:#FF8F34;padding:5px 11px;border-radius:50%;">3</span> استيراد بيانات الموظفين /
                    الحسابات
                </div>

                <div style="padding:24px 20px;">
                    <label class="fw-bold mb-2">شيت الإكسل (اختياري)</label>
                    <input type="file" name="excel_sheet" id="excel_sheet" class="form-control clean-input"
                        accept=".xlsx,.xls,.csv">
                </div>
            </div>

            {{-- زر الحفظ --}}
            <div class="text-center">
                <button type="submit"
                    style="border:none;border-radius:999px;padding:12px 28px;
                background:#F58220;color:#fff;font-weight:900;font-size:1rem;box-shadow:0 8px 20px rgba(245,130,32,.35);">
                    حفظ الجهة <i class="fa-solid fa-circle-check ms-2"></i>
                </button>
            </div>

        </form>
    </div>

    {{-- 🌈 تنسيق موحد للحقول --}}
    <style>
        .clean-input {
            border: none !important;
            border-radius: 12px;
            background: #f9fafb;
            padding: 12px 16px;
            font-size: 1rem;
            color: #333;
            transition: 0.2s;
            box-shadow: inset 0 0 0 1px #e0e0e0;
        }

        .clean-input:focus {
            outline: none;
            background: #fff;
            box-shadow: inset 0 0 0 2px #F58220, 0 0 6px rgba(245, 130, 32, .3);
        }

        .select2-container--default .select2-selection--single {
            border: none !important;
            background: #f9fafb !important;
            border-radius: 12px !important;
            height: 48px;
            display: flex;
            align-items: center;
            box-shadow: inset 0 0 0 1px #e0e0e0;
        }

        .select2-selection__rendered {
            color: #333 !important;
            font-size: 1rem;
            padding-right: 14px !important;
        }

        .select2-dropdown {
            border-radius: 12px !important;
            border: 1px solid #ddd !important;
        }
    </style>

@endsection


@push('scripts')
    <!-- مكتبات JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- CSS خاص بـ Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- 💡 سكربت تفعيل البحث داخل الوكلاء -->
    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2-agent').select2({
                    placeholder: "ابحث باسم الوكيل...",
                    allowClear: true,
                    width: '100%',
                    language: {
                        noResults: function() {
                            return "لا يوجد نتائج مطابقة";
                        }
                    }
                });
            } else {
                console.error("⚠️ مكتبة Select2 لم تُحمّل.");
            }
        });
    </script>
    <!-- ===============================
                                    🟠 1. سكربت الترميز الذكي (نهائي)
                                    ================================ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainSel = document.getElementById('main-code');
            const childSel = document.getElementById('child-code');
            const codeInp = document.querySelector('input[name="code"]');

            let lockedPrefix = ''; // الجزء الثابت من الكود مثل "MO.H."

            // 🔹 تنظيف النصوص من المسافات والنقاط الزائدة
            const clean = str => (str || '').trim().replace(/[.\s]+/g, '').toUpperCase();

            // 🔹 بناء الجزء الثابت
            function buildPrefix() {
                const main = clean(mainSel.options[mainSel.selectedIndex]?.dataset.code || '');
                const child = clean(childSel.options[childSel.selectedIndex]?.dataset.code || '');

                const parts = [main, child].filter(Boolean);
                lockedPrefix = parts.length ? parts.join('.') + '.' : '';

                codeInp.value = lockedPrefix;
                codeInp.removeAttribute('readonly');
                setTimeout(() => {
                    codeInp.focus();
                    codeInp.setSelectionRange(codeInp.value.length, codeInp.value.length);
                }, 50);
            }

            // 🔹 تحميل الفروع عند اختيار الرئيسي
            mainSel.addEventListener('change', function() {
                const parentId = this.value;
                childSel.innerHTML = '<option value="">اختر التصنيف الفرعي</option>';
                childSel.disabled = true;

                if (parentId) {
                    fetch(`/workplace-codes/${parentId}/children`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(item => {
                                const opt = document.createElement('option');
                                opt.value = item.id;
                                opt.dataset.code = clean(item.code);
                                opt.textContent = `${item.name} (${clean(item.code)})`;
                                childSel.appendChild(opt);
                            });
                            childSel.disabled = false;
                        });
                }

                buildPrefix();
            });

            // 🔹 عند اختيار الفرعي
            childSel.addEventListener('change', buildPrefix);

            // 🔹 منع حذف أو تعديل الجزء الثابت
            codeInp.addEventListener('keydown', function(e) {
                const cursorPos = this.selectionStart;
                const protectedZone = cursorPos <= lockedPrefix.length;
                const blockedKeys = ['Backspace', 'Delete', 'ArrowLeft'];

                if (protectedZone && blockedKeys.includes(e.key)) {
                    e.preventDefault();
                }
            });

            // 🔹 تصحيح أي محاولة لمسح الجزء الثابت
            codeInp.addEventListener('input', function() {
                if (!this.value.startsWith(lockedPrefix)) {
                    this.value = lockedPrefix;
                }
            });

            // 🔹 افتراضياً يكون مقفول
            codeInp.setAttribute('readonly', true);
        });
    </script>

    <!-- ===============================
                                    🟢 2. سكربت إظهار/إخفاء حقول السجل التجاري
                                    ================================ -->
    <script>
        (function() {
            const select = document.getElementById('work_categories_id');
            const docsCard = document.getElementById('docs-card');
            if (!select || !docsCard) return;

            function toggleDocs() {
                const opt = select.options[select.selectedIndex];
                const requires = opt ? opt.getAttribute('data-requires') === '1' : false;
                docsCard.style.display = requires ? '' : 'none';
            }

            select.addEventListener('change', toggleDocs);
            toggleDocs();
        })();
    </script>

    <!-- ===============================
                                    🔵 3. سكربت قراءة ملف الإكسل قبل الحفظ
                                    ================================ -->
    <script>
        (function() {
            const form = document.querySelector('form[action="{{ route('institucions.store') }}"]');
            const fileInput = document.getElementById('excel_sheet');
            const hiddenCount = document.getElementById('excel_rows');
            if (!form || !fileInput) return;

            let confirmed = false;

            form.addEventListener('submit', function(e) {
                if (!fileInput.files || fileInput.files.length === 0 || confirmed) return true;

                e.preventDefault();
                const reader = new FileReader();

                reader.onload = function(evt) {
                    try {
                        const data = new Uint8Array(evt.target.result);
                        const workbook = XLSX.read(data, {
                            type: 'array'
                        });
                        const firstSheetName = workbook.SheetNames[0];
                        const ws = workbook.Sheets[firstSheetName];
                        const rows = XLSX.utils.sheet_to_json(ws, {
                            header: 1,
                            blankrows: false
                        });
                        const dataRows = rows.slice(1);
                        const count = dataRows.filter(r => r.some(cell => String(cell ?? '').trim() !== ''))
                            .length;

                        if (hiddenCount) hiddenCount.value = count;

                        Swal.fire({
                            title: 'تأكيد الاستيراد',
                            html: `تم العثور على <b>${count}</b> صفًا في ملف الإكسل.<br>هل تريدين متابعة الحفظ؟`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'نعم، احفظ',
                            cancelButtonText: 'إلغاء',
                        }).then((res) => {
                            if (res.isConfirmed) {
                                confirmed = true;
                                const btn = form.querySelector('button[type="submit"]');
                                if (btn) {
                                    btn.disabled = true;
                                    btn.innerHTML =
                                        '<i class="fa fa-spinner fa-spin"></i> جاري الحفظ...';
                                }
                                form.submit();
                            }
                        });
                    } catch (err) {
                        console.error(err);
                        Swal.fire({
                            title: 'تنبيه',
                            text: 'حدث خطأ أثناء قراءة الملف. سيتم الحفظ بدون تأكيد العدد.',
                            icon: 'warning',
                            confirmButtonText: 'متابعة الحفظ'
                        }).then(() => form.submit());
                    }
                };

                reader.onerror = function() {
                    Swal.fire({
                        title: 'خطأ في الملف',
                        text: 'تعذر فتح ملف الإكسل. سيتم متابعة الحفظ بدون تأكيد العدد.',
                        icon: 'warning',
                        confirmButtonText: 'متابعة الحفظ'
                    }).then(() => form.submit());
                };

                reader.readAsArrayBuffer(fileInput.files[0]);
            });
        })();
    </script>
@endpush
