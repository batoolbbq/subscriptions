@extends('layouts.master')

@section('title', 'تفاصيل جهة العمل')

@section('content')
    <div class="container py-4" style="font-family: sans-serif;">

        {{-- العنوان وروابط سريعة --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-1" style="color:#111827;">{{ $institucion->name }}</h3>
                <div style="color:#6b7280; font-size:0.85rem;">
                    تم الإنشاء: {{ $institucion->created_at?->format('Y-m-d H:i') }} • آخر تحديث:
                    {{ $institucion->updated_at?->format('Y-m-d H:i') }}
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('institucions.edit', $institucion) }}"
                    style="padding:6px 14px; border:1.5px solid #ffd8a8; border-radius:999px; background:#fff5e6; color:#92400e; font-weight:700; font-size:0.85rem; text-decoration:none;">
                    تعديل
                </a>
                <a href="{{ route('institucions.index') }}"
                    style="padding:6px 14px; border:1.5px solid #d1d5db; border-radius:999px; background:#f9fafb; color:#374151; font-weight:700; font-size:0.85rem; text-decoration:none;">
                    رجوع
                </a>
            </div>
        </div>

        {{-- تنبيه التشابه (فقط عند محاولة التفعيل) --}}
        @if (session('similar_warning') && !$institucion->status)
            <div class="alert alert-warning" role="alert"
                style="border:1px solid #f59e0b; background:#fffbeb; color:#92400e; border-radius:12px; padding:12px 16px; margin-bottom:12px;">
                <div style="font-weight:800; margin-bottom:6px;">
                    {{ session('similar_warning') }}
                </div>

                @php($dups = session('similar_conflicts', []))
                @if (!empty($dups))
                    <ul style="margin:0; padding-inline-start:20px;">
                        @foreach ($dups as $dup)
                            <li style="margin:4px 0;">
                                @if (Route::has('institucions.show'))
                                    <a href="{{ route('institucions.show', $dup['id']) }}"
                                        style="text-decoration:underline; color:#7c2d12;">
                                        {{ $dup['name'] }}
                                    </a>
                                @else
                                    {{ $dup['name'] }}
                                @endif
                                <span style="opacity:.8;">— تشابه تقريبي:
                                    {{ isset($dup['percent']) ? $dup['percent'] . '%' : 'غير متاح' }}</span>
                            </li>
                        @endforeach
                    </ul>

                    {{-- زر "تفعيل رغم التشابه" (اختياري) --}}
                    @can('institucions.toggle-status')
                        @if (!$institucion->status)
                            <form action="{{ route('institucions.toggle-status', $institucion) }}" method="POST"
                                class="mt-2">
                                @csrf @method('PATCH')
                                <input type="hidden" name="force" value="1">
                                <button type="submit"
                                    style="padding:6px 14px; border:1.5px solid #f59e0b; border-radius:999px; background:#fff7ed; color:#92400e; font-weight:700; font-size:0.85rem;">
                                    تفعيل رغم التشابه
                                </button>
                            </form>
                        @endif
                    @endcan
                @endif
            </div>
        @endif

        {{-- رسالة النجاح --}}
        @if (session('success'))
            <div role="alert"
                style="background:#e9fbf2; border:1.5px solid #86efac; color:#10734a; padding:10px 14px; border-radius:8px; font-weight:600; margin-bottom:1rem;">
                {{ session('success') }}
            </div>
        @endif

        {{-- أزرار التفعيل/الإيقاف --}}
        <div class="d-flex gap-2 mb-3">
            @can('institucions.toggle-status')
                <form action="{{ route('institucions.toggle-status', $institucion) }}" method="POST">
                    @csrf @method('PATCH')

                    @if ($institucion->status)
                        {{-- إيقاف: يعمل دائماً --}}
                        <button type="submit"
                            style="padding:6px 14px; border:1.5px solid #dc2626; border-radius:999px; background:#fff1f1; color:#b91c1c; font-weight:700; font-size:0.85rem;">
                            إيقاف
                        </button>
                    @else
                        {{-- تفعيل: يتعطّل لو فيه تشابه --}}
                        <button type="submit" @if (session('similar_conflicts')) disabled @endif
                            style="padding:6px 14px; border:1.5px solid #86efac; border-radius:999px;
                                       background:{{ session('similar_conflicts') ? '#f3f4f6' : '#e9fbf2' }};
                                       color:{{ session('similar_conflicts') ? '#9ca3af' : '#10734a' }};
                                       font-weight:700; font-size:0.85rem;">
                            تفعيل
                        </button>
                    @endif
                </form>
            @endcan
        </div>

        <div class="row g-4">
            {{-- بطاقة المعلومات الأساسية --}}
            <div class="col-12 col-lg-8">
                <div
                    style="border:1.5px solid #e5e7eb; border-radius:14px; box-shadow:0 6px 20px rgba(17,24,39,.05); overflow:hidden; height:100%;">
                    <div
                        style="background:linear-gradient(180deg, #FFF7EE, #FCE8D6); padding:10px 14px; font-weight:700; display:flex; align-items:center; gap:8px;">
                        <span
                            style="background:#1d4ed8; color:#fff; padding:3px 10px; border-radius:999px; font-size:0.75rem;">بيانات
                            أساسية</span>
                        <span style="font-size:0.85rem; color:{{ $institucion->status ? '#10734a' : '#374151' }};">
                            الحالة:
                            @if ($institucion->status)
                                <span
                                    style="display:inline-block; background:#e9fbf2; color:#10734a; border:1px solid #86efac; border-radius:6px; padding:3px 10px; font-weight:700; font-size:0.8rem;">
                                    نشطة
                                </span>
                            @else
                                <span
                                    style="display:inline-block; background:#eff2f6; color:#374151; border:1px solid #d1d5db; border-radius:6px; padding:3px 10px; font-weight:700; font-size:0.8rem;">
                                    موقوفة
                                </span>
                            @endif
                        </span>
                    </div>
                    <div style="padding:14px;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div style="color:#6b7280; font-size:0.8rem; margin-bottom:4px;">نوع جهة العمل</div>
                                <div style="font-weight:600; color:#111827;">
                                    {{ optional($institucion->workCategory)->name ?? '—' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="color:#6b7280; font-size:0.8rem; margin-bottom:4px;">الاشتراك</div>
                                <div style="font-weight:600; color:#111827;">
                                    {{ optional($institucion->subscription)->name ?? '#' . (optional($institucion->subscription)->id ?? '—') }}
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div style="color:#6b7280; font-size:0.8rem; margin-bottom:4px;">الرقم التجاري</div>
                                <div style="font-weight:600; color:#111827;">
                                    {{ $institucion->commercial_number ?: '—' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="color:#6b7280; font-size:0.8rem; margin-bottom:4px;">الوكيل التأميني</div>
                                <div style="font-weight:600; color:#111827;">
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
                    style="border:1.5px solid #e5e7eb; border-radius:14px; box-shadow:0 6px 20px rgba(17,24,39,.05); overflow:hidden; height:100%;">
                    <div style="background:linear-gradient(180deg, #FFF7EE, #FCE8D6); padding:10px 14px; font-weight:700;">
                        المستندات
                    </div>
                    <div style="padding:14px;">
                        <div style="margin-bottom:12px;">
                            <div style="color:#6b7280; font-size:0.8rem; margin-bottom:4px;">ملف الترخيص</div>
                            @if ($institucion->license_number)
                                <a href="{{ asset($institucion->license_number) }}" target="_blank"
                                    style="color:#1d4ed8; text-decoration:none; font-weight:600;">عرض الملف</a>
                            @else
                                <div style="color:#9ca3af;">لا يوجد ملف ترخيص</div>
                            @endif
                        </div>

                        <div>
                            <div style="color:#6b7280; font-size:0.8rem; margin-bottom:4px;">ملف السجل التجاري</div>
                            @if ($institucion->commercial_record)
                                <a href="{{ asset($institucion->commercial_record) }}" target="_blank"
                                    style="color:#1d4ed8; text-decoration:none; font-weight:600;">عرض الملف</a>
                            @else
                                <div style="color:#9ca3af;">لا يوجد ملف سجل تجاري</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- .row --}}

    </div> {{-- .container --}}
@endsection
