@extends('layouts.master')

@section('title', 'تفاصيل جهة العمل')

@section('content')
    <div class="container py-4"
        style="--brand:#F58220;--brand-600:#ff8f34;--brown:#8C5346;--ink:#374151;--muted:#6b7280;--line:#E5E7EB;
                --hdr1:#d95b00;--hdr2:#F58220;--hdr3:#FF8F34;--hdr4:#ffb066;
                --green-50:#e9fbf2;--green-700:#10734a;--gray-50:#eff2f6;--gray-700:#374151;
                --red-50:#fff1f1;--red-200:#ffc9c9;--red-700:#b42318;">

        {{-- العنوان وروابط سريعة --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-1" style="font-weight:800;color:var(--brown);">{{ $institucion->name }}</h3>
                <div style="color:var(--muted); font-size:.9rem;">
                    تم الإنشاء: {{ $institucion->created_at?->format('Y-m-d H:i') }} • آخر تحديث:
                    {{ $institucion->updated_at?->format('Y-m-d H:i') }}
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">

                @if (!$institucion->status)
                    <a href="{{ route('institucions.edit', $institucion) }}"
                        style="padding:8px 16px;border:1.5px solid #FFD8A8;border-radius:999px;background:#FFF5E6;
                  color:#92400E;font-weight:800;font-size:.9rem;text-decoration:none;
                  box-shadow:0 8px 18px rgba(0,0,0,.06);">
                        تعديل
                    </a>
                @else 
                @role('insurance-manager')
                    <a href="{{ route('institucions.edit', $institucion) }}"
                        style="padding:8px 16px;border:1.5px solid #FFD8A8;border-radius:999px;background:#FFF5E6;
                  color:#92400E;font-weight:800;font-size:.9rem;text-decoration:none;
                  box-shadow:0 8px 18px rgba(0,0,0,.06);">
                        تعديل
                    </a>
                    @endrole
                @endif
                <a href="{{ route('institucions.index') }}"
                    style="padding:8px 16px;border:1.5px solid var(--line);border-radius:999px;background:#fff;
                          color:var(--ink);font-weight:800;font-size:.9rem;text-decoration:none;
                          box-shadow:0 8px 18px rgba(0,0,0,.06);">
                    رجوع
                </a>
            </div>
        </div>

        {{-- تنبيه التشابه --}}
        @if (session('similar_warning') && !$institucion->status)
            <div class="alert alert-warning" role="alert"
                style="border:1.5px solid #f59e0b;background:#fffbeb;color:#92400e;border-radius:16px;
                        padding:12px 16px;margin-bottom:12px;box-shadow:0 10px 24px rgba(245,158,11,.15);">
                <div style="font-weight:800;margin-bottom:6px;">
                    {{ session('similar_warning') }}
                </div>

                @php($dups = session('similar_conflicts', []))
                @if (!empty($dups))
                    <table style="width:100%;border-collapse:collapse;margin-top:10px;">
                        <thead>
                            <tr style="background:#fff8eb;color:#92400e;">
                                <th style="padding:6px 10px;text-align:right;border-bottom:1px solid #f59e0b;">الجهة</th>
                                <th style="padding:6px 10px;text-align:right;border-bottom:1px solid #f59e0b;">نسبة التشابه
                                </th>
                                <th style="padding:6px 10px;text-align:right;border-bottom:1px solid #f59e0b;">عدد المشتركين
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dups as $dup)
                                <tr>
                                    <td style="padding:6px 10px;border-bottom:1px solid #f3f4f6;">
                                        @if (Route::has('institucions.show'))
                                            <a href="{{ route('institucions.show', $dup['id']) }}"
                                                style="text-decoration:underline;color:#7c2d12;">
                                                {{ $dup['name'] }}
                                            </a>
                                        @else
                                            {{ $dup['name'] }}
                                        @endif
                                    </td>
                                    <td style="padding:6px 10px;border-bottom:1px solid #f3f4f6;">
                                        {{ isset($dup['percent']) ? $dup['percent'] . '%' : 'غير متاح' }}
                                    </td>
                                    <td style="padding:6px 10px;border-bottom:1px solid #f3f4f6;">
                                        {{ $dup['count'] ?? 0 }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @can('institucions.toggle-status')
                        @if (!$institucion->status)
                            <button type="button" id="btn-activate-anyway"
                                style="margin-top:10px;padding:8px 16px;border:1.5px solid #f59e0b;border-radius:999px;
                                       background:#fff7ed;color:#92400e;font-weight:800;font-size:.85rem;">
                                تفعيل رغم التشابه و نقل المشتركين
                            </button>
                        @endif
                    @endcan
                @endif
            </div>
        @endif

        {{-- رسالة النجاح --}}
        @if (session('success'))
            <div role="alert"
                style="background:var(--green-50);border:1.5px solid #86efac;color:var(--green-700);
                        padding:10px 14px;border-radius:14px;font-weight:800;margin-bottom:1rem;
                        box-shadow:0 8px 18px rgba(16,115,74,.12);">
                {{ session('success') }}
            </div>
        @endif

        {{-- أزرار التفعيل/الإيقاف + زر النقل --}}
        <div class="d-flex gap-2 mb-3">
            @can('institucions.toggle-status')
                <form action="{{ route('institucions.toggle-status', $institucion) }}" method="POST">
                    @csrf @method('PATCH')
                    @if ($institucion->status)
                        <button type="submit"
                            style="padding:8px 16px;border:1.5px solid var(--red-200);border-radius:999px;
                                       background:var(--red-50);color:var(--red-700);font-weight:800;font-size:.9rem;
                                       box-shadow:0 8px 18px rgba(180,35,24,.08);">
                            إيقاف
                        </button>
                    @else
                        <button type="submit" @if (session('similar_conflicts')) disabled @endif
                            style="padding:8px 16px;border:1.5px solid #86efac;border-radius:999px;
                                       background:{{ session('similar_conflicts') ? '#f3f4f6' : 'var(--green-50)' }};
                                       color:{{ session('similar_conflicts') ? '#9ca3af' : 'var(--green-700)' }};
                                       font-weight:800;font-size:.9rem;box-shadow:0 8px 18px rgba(16,115,74,.08);">
                            تفعيل
                        </button>
                    @endif
                </form>

                {{-- زر النقل اليدوي --}}

                {{-- فورم خفي للنقل
                <form id="transfer-form" action="{{ route('institucions.transfer-customers', $institucion) }}" method="POST"
                    style="display:none;">
                    @csrf
                    <input type="hidden" name="from_id" id="transfer-from-id">
                </form> --}}


            @endcan
        </div>





        <div class="row g-4">
            {{-- بطاقة المعلومات الأساسية --}}
            <div class="col-12 col-lg-8">
                <div
                    style="border:1.5px solid var(--line);border-radius:32px;box-shadow:0 18px 40px rgba(0,0,0,.10);overflow:hidden;height:100%;background:#fff;">
                    <div
                        style="background:linear-gradient(135deg,#d95b00 0%,#F58220 35%,#FF8F34 70%,#ffb066 100%);
       color:#fff;padding:12px 16px;font-weight:800;border-radius:32px 32px 0 0;">
                        <span style="font-size:.9rem;color:{{ $institucion->status ? '#ffff' : '#374151' }};">
                            الحالة:
                            @if ($institucion->status)
                                <span
                                    style="display:inline-block;background:var(--green-50);color:var(--green-700);border:1px solid #86efac;border-radius:8px;padding:3px 10px;font-weight:800;font-size:.8rem;">
                                    نشطة
                                </span>
                            @else
                                <span
                                    style="display:inline-block;background:var(--gray-50);color:var(--gray-700);border:1px solid #d1d5db;border-radius:8px;padding:3px 10px;font-weight:800;font-size:.8rem;">
                                    موقوفة
                                </span>
                            @endif
                        </span>
                    </div>
                    <div style="padding:16px 18px;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div style="color:var(--muted);font-size:.85rem;margin-bottom:4px;">نوع جهة العمل</div>
                                <div style="font-weight:700;color:var(--ink);">
                                    {{ optional($institucion->workCategory)->name ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div style="color:var(--muted);font-size:.85rem;margin-bottom:4px;">الاشتراك</div>
                                <div style="font-weight:700;color:var(--ink);">
                                    {{ optional($institucion->subscription)->name ?? '#' . (optional($institucion->subscription)->id ?? '—') }}
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <div style="color:var(--muted);font-size:.85rem;margin-bottom:4px;">الرقم التجاري</div>
                                <div style="font-weight:700;color:var(--ink);">
                                    {{ $institucion->commercial_number ?: '—' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="color:var(--muted);font-size:.85rem;margin-bottom:4px;">الوكيل التأميني</div>
                                <div style="font-weight:700;color:var(--ink);">
                                    {{ optional($institucion->insuranceAgent)->name ?? '—' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- بطاقة المستندات --}}
            <div class="col-12 col-lg-4">
                <div
                    style="border:1.5px solid var(--line);border-radius:32px;box-shadow:0 18px 40px rgba(0,0,0,.10);overflow:hidden;height:100%;background:#fff;">
                    <div
                        style="background:linear-gradient(135deg,#d95b00 0%,#F58220 35%,#FF8F34 70%,#ffb066 100%);
           color:#fff;padding:12px 16px;font-weight:800;border-radius:32px 32px 0 0;">
                        المستندات
                    </div>

                    <div style="padding:16px 18px;">
                        <div style="margin-bottom:12px;">
                            <div style="color:var(--muted);font-size:.85rem;margin-bottom:4px;">ملف الترخيص</div>
                            @if ($institucion->license_number)
                                <a href="{{ asset($institucion->license_number) }}" target="_blank"
                                    style="color:#9F5547;text-decoration:underline;">
                                    عرض الملف الحالي
                                </a>
                            @else
                                <div style="color:#9ca3af;">لا يوجد ملف ترخيص</div>
                            @endif
                        </div>

                        <div>
                            <div style="color:var(--muted);font-size:.85rem;margin-bottom:4px;">ملف السجل التجاري</div>
                            @if ($institucion->commercial_record)
                                <a href="{{ asset($institucion->commercial_record) }}" target="_blank"
                                    style="color:#9F5547;text-decoration:underline;">
                                    عرض الملف الحالي
                                </a>
                            @else
                                <div style="color:#9F5547;">لا يوجد ملف سجل تجاري</div>
                            @endif
                        </div>

                        <div style="color:var(--muted);font-size:.85rem;margin-bottom:4px;">ملف اكسل شيت </div>
                        <a href="{{ asset($institucion->excel_path) }}" target="_blank"
                            style="color:#9F5547;text-decoration:underline;">
                            عرض ملف الإكسل الحالي
                        </a>
                    </div>
                </div>
            </div>

            {{-- بطاقة المشتركين --}}
            @role('insurance-manager')
                <div class="col-12 mt-4">
                    <div
                        style="border:1.5px solid var(--line);border-radius:20px;padding:16px;background:#fff;
    box-shadow:0 10px 20px rgba(0,0,0,.06);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 style="font-weight:800;color:var(--brown);margin:0;">
                                عدد المشتركين التابعين لهذه الجهة: {{ $customersCount }}
                            </h5>

                            {{-- زر النقل --}}
                            @if ($customersCount > 0)
                                <a href="{{ route('institucions.transferview', $institucion) }}"
                                    style="padding:8px 16px;border:1.5px solid #FFD8A8;border-radius:999px;
                          background:#FFF5E6;color:#92400E;font-weight:800;font-size:.9rem;
                          text-decoration:none;box-shadow:0 8px 18px rgba(0,0,0,.06);">
                                    نقل المشتركين
                                </a>
                            @else
                                <span
                                    style="padding:8px 16px;border:1.5px solid #d1d5db;border-radius:999px;
                             background:#f3f4f6;color:#9ca3af;font-weight:800;font-size:.9rem;
                             box-shadow:0 8px 18px rgba(0,0,0,.06);cursor:not-allowed;">
                                    نقل المشتركين
                                </span>
                            @endif

                        </div>
                    </div>
                </div>
            @endrole



            {{-- فورم خفي لإرسال force=1 + code عند "تفعيل رغم التشابه" --}}
            @can('institucions.toggle-status')
                <form id="force-activate-form" action="{{ route('institucions.toggle-status', $institucion) }}" method="POST"
                    style="display:none;">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="force" value="1">
                    <input type="hidden" name="code" id="force-code-input">
                </form>
            @endcan
        </div> {{-- .row --}}
    </div> {{-- .container --}}
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // زر تفعيل رغم التشابه
        (function() {
            const btn = document.getElementById('btn-activate-anyway');
            if (!btn) return;

            btn.addEventListener('click', async function() {
                const hasCode = {{ $institucion->code ? 'true' : 'false' }};
                let codeVal = '';

                if (!hasCode) {
                    const {
                        value: inputCode
                    } = await Swal.fire({
                        title: 'أدخل ترميز جهة العمل',
                        input: 'text',
                        inputLabel: 'الترميز مطلوب للتفعيل',
                        inputPlaceholder: 'مثال: HR-TR-2025',
                        inputValidator: (v) => {
                            if (!v || v.trim() === '') return 'الترميز مطلوب';
                        },
                        showCancelButton: true,
                        confirmButtonText: 'تفعيل',
                        cancelButtonText: 'إلغاء'
                    });
                    if (!inputCode) return;
                    codeVal = inputCode.trim();
                } else {
                    const ok = await Swal.fire({
                        title: 'تفعيل رغم التشابه؟',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'نعم، فعّل',
                        cancelButtonText: 'إلغاء'
                    });
                    if (!ok.isConfirmed) return;
                }

                const form = document.getElementById('force-activate-form');
                const hiddenCode = document.getElementById('force-code-input');
                if (codeVal) hiddenCode.value = codeVal;
                form.submit();
            });
        })();

        // زر نقل المشتركين
        document.getElementById('btn-transfer-customers').addEventListener('click', async function() {
            const options = @json($otherInstitucions);
            let inputOptions = {};
            for (const [id, name] of Object.entries(options)) {
                inputOptions[id] = name;
            }

            const {
                value: fromId
            } = await Swal.fire({
                title: 'اختر الجهة المراد النقل منها',
                input: 'select',
                inputOptions: inputOptions,
                inputPlaceholder: 'اختر جهة عمل...',
                showCancelButton: true,
                confirmButtonText: 'نقل',
                cancelButtonText: 'إلغاء'
            });

            if (fromId) {
                document.getElementById('transfer-from-id').value = fromId;
                document.getElementById('transfer-form').submit();
            }
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const part1 = document.getElementById('select-part1');
            const part2 = document.getElementById('select-part2');
            const extra = document.getElementById('extra-code');
            const hiddenSub = document.getElementById('sub_code_id');

            // عند اختيار الأساسي
            part1.addEventListener('change', function() {
                const parentId = this.value;
                part2.innerHTML = '<option value="">اختر التصنيف الفرعي</option>';
                part2.disabled = true;

                if (parentId) {
                    fetch(`/workplace_codes/by-parent/${parentId}`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(item => {
                                const opt = document.createElement('option');
                                opt.value = item.id;
                                opt.dataset.code = item.code;
                                opt.textContent = `${item.name} (${item.code})`;
                                part2.appendChild(opt);
                            });
                            part2.disabled = false;
                        });
                }
            });

            // عند اختيار الفرعي نخزن الـ id
            part2.addEventListener('change', function() {
                hiddenSub.value = this.value || '';
            });
        });
    </script>
@endpush
