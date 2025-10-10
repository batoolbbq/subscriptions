@extends('layouts.master')

@section('title', 'إضافة جهة عمل')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

@section('content')
    <div class="container py-4"
        style="font-family: 'Tajawal', system-ui, -apple-system, Segoe UI, Roboto, sans-serif; color:#8C5346;">

        {{-- العنوان وزر الرجوع --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <h3 style="margin:0;font-weight:800;color:8C5346;">إضافة جهة عمل</h3>

            </div>


            <a href="{{ route('institucions.index') }}"
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

        <form action="{{ route('institucions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="excel_rows" id="excel_rows">

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
                        <div class="col-lg-5">
                            <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">
                                نوع جهة العمل <span style="color:#ef4444;">*</span>
                            </label>
                            @php
                                $isWakeel = auth()->user()->hasRole('Wakeel');
                                $publicCategoryIds = isset($publicCategoryIds) ? $publicCategoryIds : [19];
                            @endphp
                            <select id="work_categories_id" name="work_categories_id" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                required>
                                <option value="" disabled {{ old('work_categories_id') ? '' : 'selected' }}>— اختر
                                    النوع —</option>
                                @foreach ($workCategories as $wc)
                                    @php
                                        $isPublicForWakeel = $isWakeel && in_array($wc->id, $publicCategoryIds);
                                        $requires = in_array($wc->id, $requiresDocsIds ?? []) ? 1 : 0;
                                    @endphp
                                    @continue($isPublicForWakeel)
                                    <option value="{{ $wc->id }}" data-requires="{{ $requires }}"
                                        {{ (string) old('work_categories_id') === (string) $wc->id ? 'selected' : '' }}>
                                        {{ $wc->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div style="color:#6b7280;font-size:13px;margin-top:6px;">ستظهر حقول السجل والملفات تلقائيًا إذا
                                كان النوع يتطلبها.</div>
                        </div>

                        <div class="col-lg-7">
                            <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">
                                اسم جهة العمل <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="text" name="name" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                value="{{ old('name') }}" placeholder="أدخل اسم الجهة" required>
                        </div>


                        {{-- @role('insurance-manager|admin')
                            <div class="col-md-6">
                                <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">
                                    الترميز (اختياري)
                                </label>
                                <input type="text" name="code" class="form-control"
                                    style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                    value="{{ old('code') }}" placeholder="مثال: HR-TR-2025">
                                <div style="color:#6b7280;font-size:13px;margin-top:6px;">
                                    الحقل غير فريد — قد تتشارك عدة جهات نفس الترميز.
                                </div>
                            </div>
                        @endrole --}}


                        @role('insurance-manager|admin')
                            <div class="col-md-12">
                                <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">
                                    الترميز (اختياري)
                                </label>

                                <div class="row g-2">
                                    {{-- التصنيف الرئيسي --}}
                                    <div class="col-md-4">
                                        <select id="main-code" name="parent_id" class="form-select"
                                            style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;
                    border-radius:999px;padding:10px 14px;font-size:1rem;outline:none;">
                                            <option value="">اختر التصنيف الرئيسي</option>
                                            @foreach ($parents as $p)
                                                <option value="{{ $p->id }}" data-code="{{ $p->code }}">
                                                    {{ $p->name }} ({{ $p->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- التصنيف الفرعي --}}
                                    <div class="col-md-4">
                                        <select id="child-code" name="child_id" class="form-select"
                                            style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;
                    border-radius:999px;padding:10px 14px;font-size:1rem;outline:none;"
                                            disabled>
                                            <option value="">اختر التصنيف الفرعي</option>
                                        </select>
                                    </div>

                                    {{-- الكود النهائي (يُعرض هنا) --}}
                                    <div class="col-md-4">
                                        <input type="text" id="final-code" name="code" class="form-control" readonly
                                            style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;
                    border-radius:999px;padding:10px 14px;font-size:1rem;outline:none;color:#92400E;font-weight:700;">
                                    </div>
                                </div>


                            </div>
                        @endrole

                        {{-- 
                        <div class="col-md-6">
                            <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">
                                الاشتراك <span style="color:#ef4444;">*</span>
                            </label>
                            <select name="subscriptions_id" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                required>
                                <option value="" disabled selected>— اختر الاشتراك —</option>
                                @foreach ($subscriptions as $s)
                                    <option value="{{ $s->id }}"
                                        {{ old('subscriptions_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name ?? 'اشتراك #' . $s->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                        @if ($showAgentSelect)
                            <div class="col-md-6">
                                <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">
                                    الوكيل التأميني
                                </label>
                                <select name="insurance_agent_id" class="form-control"
                                    style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;">
                                    <option value="">— اختياري —</option>
                                    @foreach ($agents as $a)
                                        <option value="{{ $a->id }}"
                                            {{ (string) old('insurance_agent_id', (string) $preselectedAgentId) === (string) $a->id ? 'selected' : '' }}>
                                            {{ $a->name ?? 'Agent #' . $a->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="insurance_agent_id" value="{{ $preselectedAgentId }}">
                        @endif
                    </div>
                </div>
            </div>

            {{-- البطاقة 2: بيانات السجل --}}
            <div id="docs-card"
                style="display:none;border:1.5px solid #E5E7EB;border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);margin-bottom:16px;overflow:hidden;background:#fff;">
                <div
                    style="background:linear-gradient(135deg,#d95b00 0%,#F58220 35%,#FF8F34 70%,#ffb066 100%);color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                    <span
                        style="background:#FF8F34;color:#fff;width:34px;height:34px;display:grid;place-items:center;border-radius:999px;font-size:.95rem;box-shadow:0 10px 22px rgba(245,130,32,.35);">2</span>
                    <h6 style="margin:0;font-weight:800;color:#ffffff">بيانات السجل التجاري والترخيص</h6>
                </div>

                <div style="padding:22px 20px 26px;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">الرقم
                                التجاري</label>
                            <input type="text" name="commercial_number" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                value="{{ old('commercial_number') }}" placeholder="مثال: 123456789">
                        </div>
                        <div class="col-md-6">
                            <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">ملف
                                الترخيص</label>
                            <input type="file" name="license_number" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-6">
                            <label style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">ملف
                                السجل التجاري</label>
                            <input type="file" name="commercial_record" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
            </div>

            {{-- البطاقة 3: استيراد الإكسل --}}
            <div
                style="border:1.5px solid #E5E7EB;border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);margin-bottom:16px;overflow:hidden;background:#fff;">
                <div
                    style="background:linear-gradient(135deg,#d95b00 0%,#F58220 35%,#FF8F34 70%,#ffb066 100%);color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                    <span
                        style="background:#FF8F34;color:#fff;width:34px;height:34px;display:grid;place-items:center;border-radius:999px;font-size:.95rem;box-shadow:0 10px 22px rgba(245,130,32,.35);">3</span>
                    <h6 style="margin:0;font-weight:800;color:#ffffff;">استيراد بيانات الموظفين / الحسابات</h6>
                </div>

                <div style="padding:22px 20px 26px;">
                    <div class="mb-3">
                        <label for="excel_sheet" class="form-label"
                            style="display:block;margin-bottom:6px;font-size:.95rem;font-weight:700;">
                            شيت الإكسل (اختياري)
                        </label>
                        <input type="file" name="excel_sheet" id="excel_sheet" class="form-control"
                            style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                            accept=".xlsx,.xls,.csv">

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
                    حفظ الجهة
                    <i class="fa-solid fa-circle-check"></i>
                </button>


            </div>
        </form>
    </div>
@endsection

{{-- @push('scripts')
    {{-- مكتبات مساعدة --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
    // 🟠 سكربت إدارة الترميز (التصنيف الرئيسي + الفرعي + الجزء الإضافي)
    document.addEventListener('DOMContentLoaded', function() {
        const parentSel = document.getElementById('main-code');
        const childSel = document.getElementById('child-code');
        const extraInp = document.getElementById('extra-code');
        const preview = document.getElementById('preview-code');
        const finalInp = document.getElementById('final-code');

        // ✅ دالة لتحديث الكود النهائي في الوقت الفعلي
        function updateFinal() {
            const parentCode = parentSel.options[parentSel.selectedIndex]?.dataset.code || '';
            const childCode = childSel.options[childSel.selectedIndex]?.dataset.code || '';
            const extra = extraInp.value.trim();
            const fullCode = parentCode + childCode + extra;
            preview.textContent = fullCode;
            finalInp.value = fullCode;
        }

        // ✅ عند اختيار التصنيف الرئيسي → نجلب الفروع
        parentSel.addEventListener('change', function() {
            const parentId = this.value;
            childSel.innerHTML = '<option value="">اختر التصنيف الفرعي (اختياري)</option>';
            childSel.disabled = true;
            updateFinal();

            if (parentId) {
                fetch(`/workplace-codes/${parentId}/children`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(item => {
                                const opt = document.createElement('option');
                                opt.value = item.id;
                                opt.dataset.code = item.code;
                                opt.textContent = `${item.name} (${item.code})`;
                                childSel.appendChild(opt);
                            });
                            childSel.disabled = false;
                        }
                    });
            }
        });

        // ✅ أي تغيير في التصنيف الفرعي أو الجزء الإضافي يحدث الكود
        childSel.addEventListener('change', updateFinal);
        extraInp.addEventListener('input', updateFinal);
    });
</script>

<script>
    // 🟢 سكربت فحص شيت الإكسل قبل الحفظ
    (function() {
        const select = document.getElementById('work_categories_id');
        const docsCard = document.getElementById('docs-card');

        // ✅ إظهار/إخفاء كارت السجل التجاري حسب نوع الجهة
        function toggleDocs() {
            const opt = select.options[select.selectedIndex];
            const requires = opt ? opt.getAttribute('data-requires') === '1' : false;
            docsCard.style.display = requires ? '' : 'none';
        }

        select.addEventListener('change', toggleDocs);
        toggleDocs();

        // ✅ فحص شيت الإكسل قبل الإرسال
        const form = document.querySelector('form[action="{{ route('institucions.store') }}"]');
        const fileInput = document.getElementById('excel_sheet');
        const hiddenCount = document.getElementById('excel_rows');
        let confirmed = false;

        form.addEventListener('submit', function(e) {
            if (!fileInput || !fileInput.files || fileInput.files.length === 0 || confirmed) return true;

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
                        html: `تم العثور على <b>${count}</b> صف في شيت الإكسل.<br>هل تريدين متابعة الحفظ؟`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'نعم، احفظ',
                        cancelButtonText: 'إلغاء'
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
                        text: 'حدث خطأ أثناء قراءة ملف الإكسل. سيتم متابعة الحفظ بدون فحص الصفوف.',
                        icon: 'warning',
                        confirmButtonText: 'متابعة الحفظ'
                    }).then(() => form.submit());
                }
            };

            reader.onerror = function() {
                Swal.fire({
                    title: 'خطأ في الملف',
                    text: 'تعذر قراءة ملف الإكسل. سيتم متابعة الحفظ بدون تأكيد العدد.',
                    icon: 'warning',
                    confirmButtonText: 'متابعة الحفظ'
                }).then(() => form.submit());
            };

            reader.readAsArrayBuffer(fileInput.files[0]);
        });
    })();
</script> --}}
{{-- @endpush --}} --


@push('scripts')
    <!-- مكتبات SweetAlert و Excel -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

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
