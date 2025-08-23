@extends('layouts.master')

@section('title', 'إضافة جهة عمل')

@section('content')
    <div class="container py-4" style="font-family: sans-serif;">

        {{-- العنوان وزر الرجوع --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <h3 style="margin:0;font-weight:800;color:#111827;">إضافة جهة عمل</h3>
                <div style="color:#6b7280;font-size:14px;">اختر نوع جهة العمل أولاً، ثم أكمل البيانات المطلوبة.</div>
            </div>
            <a href="{{ route('institucions.index') }}"
                style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:6px 14px;font-weight:700;text-decoration:none;">
                <i class="fa fa-arrow-right"></i> رجوع للقائمة
            </a>
        </div>

        {{-- رسائل الأخطاء --}}
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

        <form action="{{ route('institucions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- مخفي: عدد صفوف الإكسل (اختياري للباك إند) --}}
            <input type="hidden" name="excel_rows" id="excel_rows">

            {{-- البطاقة 1: الأساسيات --}}
            <div
                style="border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);margin-bottom:16px;overflow:hidden;">
                <div
                    style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid #D0D5DD;padding:10px 14px;display:flex;align-items:center;gap:8px;">
                    <span
                        style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid #FFD8A8;color:#92400E;font-weight:800;">1</span>
                    <h6 style="margin:0;font-weight:800;color:#374151;">أساسيات جهة العمل</h6>
                </div>
                <div style="padding:16px;">
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <label style="font-weight:700;color:#374151;">نوع جهة العمل <span
                                    style="color:red;">*</span></label>
                            @php
                                $isWakeel = auth()->user()->hasRole('Wakeel');
                                $publicCategoryIds = isset($publicCategoryIds) ? $publicCategoryIds : [19];
                            @endphp

                            <select id="work_categories_id" name="work_categories_id" class="form-control"
                                style="border:1.5px solid #E5E7EB;" required>
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

                            <div style="color:#6b7280;font-size:13px;">ستظهر حقول السجل والملفات تلقائيًا إذا كان النوع
                                يتطلبها.</div>
                        </div>

                        <div class="col-lg-7">
                            <label style="font-weight:700;color:#374151;">اسم جهة العمل <span
                                    style="color:red;">*</span></label>
                            <input type="text" name="name" class="form-control" style="border:1.5px solid #E5E7EB;"
                                value="{{ old('name') }}" placeholder="أدخل اسم الجهة" required>
                        </div>

                        <div class="col-md-6">
                            <label style="font-weight:700;color:#374151;">الاشتراك <span style="color:red;">*</span></label>
                            <select name="subscriptions_id" class="form-control" style="border:1.5px solid #E5E7EB;"
                                required>
                                <option value="" disabled selected>— اختر الاشتراك —</option>
                                @foreach ($subscriptions as $s)
                                    <option value="{{ $s->id }}"
                                        {{ old('subscriptions_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name ?? 'اشتراك #' . $s->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if ($showAgentSelect)
                            <div class="col-md-6">
                                <label style="font-weight:700;color:#374151;">الوكيل التأميني (اختياري)</label>
                                <select name="insurance_agent_id" class="form-control" style="border:1.5px solid #E5E7EB;">
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
                style="display:none;border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);margin-bottom:16px;overflow:hidden;">
                <div
                    style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid #D0D5DD;padding:10px 14px;display:flex;align-items:center;gap:8px;">
                    <span
                        style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid #FFD8A8;color:#92400E;font-weight:800;">2</span>
                    <h6 style="margin:0;font-weight:800;color:#374151;">بيانات السجل التجاري والترخيص</h6>
                </div>
                <div style="padding:16px;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label style="font-weight:700;color:#374151;">الرقم التجاري</label>
                            <input type="text" name="commercial_number" class="form-control"
                                style="border:1.5px solid #E5E7EB;" value="{{ old('commercial_number') }}"
                                placeholder="مثال: 123456789">
                        </div>
                        <div class="col-md-6">
                            <label style="font-weight:700;color:#374151;">ملف الترخيص</label>
                            <input type="file" name="license_number" class="form-control"
                                style="border:1.5px solid #E5E7EB;" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-6">
                            <label style="font-weight:700;color:#374151;">ملف السجل التجاري</label>
                            <input type="file" name="commercial_record" class="form-control"
                                style="border:1.5px solid #E5E7EB;" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
            </div>

            {{-- البطاقة 3: استيراد الإكسل --}}
            <div
                style="border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);margin-bottom:16px;overflow:hidden;">
                <div
                    style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid #D0D5DD;padding:10px 14px;display:flex;align-items:center;gap:8px;">
                    <span
                        style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid #FFD8A8;color:#92400E;font-weight:800;">3</span>
                    <h6 style="margin:0;font-weight:800;color:#374151;">استيراد بيانات الموظفين / الحسابات</h6>
                </div>
                <div style="padding:16px;">
                    <div class="mb-3">
                        <label for="excel_sheet" class="form-label" style="font-weight:700;color:#374151;">شيت الإكسل
                            (اختياري)</label>
                        <input type="file" name="excel_sheet" id="excel_sheet" class="form-control"
                            accept=".xlsx,.xls,.csv">
                        <small class="text-muted d-block mt-1" style="color:#6b7280;">
                            ترتيب الأعمدة المطلوب (صف العناوين أول صف):<br>
                            الاسم | اسم الأب | اللقب | الرقم الوطني | قيد العائلة | رقم المضمون | رقم المعاش (للمتقاعدين) |
                            رقم الحساب | إجمالي المعاش
                        </small>
                    </div>
                </div>
            </div>

            {{-- الأزرار --}}
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button type="submit"
                    style="display:inline-flex;align-items:center;gap:6px;background:#FFF7EE;color:#92400E;border:1.5px solid #FFD8A8;border-radius:999px;padding:8px 18px;font-weight:800;">
                    <i class="fa fa-save"></i> حفظ الجهة
                </button>
                <a href="{{ route('institucions.index') }}"
                    style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:8px 18px;font-weight:800;text-decoration:none;">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- مكتبات سويت أليرت وقراءة الإكسل في المتصفح --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <script>
        (function() {
            // إظهار/إخفاء بطاقة المستندات حسب نوع الجهة
            const select = document.getElementById('work_categories_id');
            const docsCard = document.getElementById('docs-card');

            function toggleDocs() {
                const opt = select.options[select.selectedIndex];
                const requires = opt ? opt.getAttribute('data-requires') === '1' : false;
                docsCard.style.display = requires ? '' : 'none';
            }
            select.addEventListener('change', toggleDocs);
            toggleDocs();

            // ===== تأكيد قبل الحفظ بناءً على عدد صفوف الإكسل =====
            const form = document.querySelector('form[action="{{ route('institucions.store') }}"]');
            const fileInput = document.getElementById('excel_sheet');
            const hiddenCount = document.getElementById('excel_rows');
            let confirmed = false;

            form.addEventListener('submit', function(e) {
                // لو ما فيش ملف إكسل أو سبق وأكدنا → خليه يرسل عادي
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

                        // نحول لصفوف مع العناوين في أول صف
                        const rows = XLSX.utils.sheet_to_json(ws, {
                            header: 1,
                            blankrows: false
                        });
                        // نشيل صف العناوين
                        const dataRows = rows.slice(1);

                        // نعد الصفوف غير الفارغة (أي خلية فيها قيمة)
                        const count = dataRows.filter(r => r.some(cell => String(cell ?? '').trim() !== ''))
                            .length;

                        if (hiddenCount) hiddenCount.value = count;

                        Swal.fire({
                            title: 'تأكيد الاستيراد',
                            html: `تم العثور على <b>${count}</b> صف في شيت الإكسل.<br>هل تريدين إكمال الحفظ؟`,
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
                            text: 'تعذر قراءة شيت الإكسل في المتصفح. سيتم متابعة الحفظ بدون تأكيد العدد.',
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
