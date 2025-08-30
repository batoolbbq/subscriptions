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
                <a href="{{ route('institucions.edit', $institucion) }}"
                    style="padding:8px 16px;border:1.5px solid #FFD8A8;border-radius:999px;background:#FFF5E6;
                          color:#92400E;font-weight:800;font-size:.9rem;text-decoration:none;
                          box-shadow:0 8px 18px rgba(0,0,0,.06);">
                    تعديل
                </a>
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
                    <ul style="margin:0;padding-inline-start:20px;">
                        @foreach ($dups as $dup)
                            <li style="margin:4px 0;">
                                @if (Route::has('institucions.show'))
                                    <a href="{{ route('institucions.show', $dup['id']) }}"
                                        style="text-decoration:underline;color:#7c2d12;">
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

                    @can('institucions.toggle-status')
                        @if (!$institucion->status)
                            <form action="{{ route('institucions.toggle-status', $institucion) }}" method="POST"
                                class="mt-2">
                                @csrf @method('PATCH')
                                <input type="hidden" name="force" value="1">
                                <button type="submit"
                                    style="padding:8px 16px;border:1.5px solid #f59e0b;border-radius:999px;
                                               background:#fff7ed;color:#92400e;font-weight:800;font-size:.85rem;">
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
                style="background:var(--green-50);border:1.5px solid #86efac;color:var(--green-700);
                        padding:10px 14px;border-radius:14px;font-weight:800;margin-bottom:1rem;
                        box-shadow:0 8px 18px rgba(16,115,74,.12);">
                {{ session('success') }}
            </div>
        @endif

        {{-- أزرار التفعيل/الإيقاف --}}
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
            @endcan
        </div>

        <div class="row g-4">
            {{-- بطاقة المعلومات الأساسية --}}
            <div class="col-12 col-lg-8">
                <div
                    style="border:1.5px solid var(--line);border-radius:32px;box-shadow:0 18px 40px rgba(0,0,0,.10);overflow:hidden;height:100%;background:#fff;">
                    <div
                        style="background:linear-gradient(135deg,#FFF7EE,#FCE8D6);padding:12px 16px;font-weight:800;display:flex;align-items:center;gap:10px;border-radius:32px 32px 0 0;">
                        <span
                            style="background:#1d4ed8;color:#fff;padding:4px 12px;border-radius:999px;font-size:.8rem;">بيانات
                            أساسية</span>
                        <span style="font-size:.9rem;color:{{ $institucion->status ? '#10734a' : '#374151' }};">
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
                        style="background:linear-gradient(135deg,#FFF7EE,#FCE8D6);padding:12px 16px;font-weight:800;border-radius:32px 32px 0 0;">
                        المستندات
                    </div>
                    <div style="padding:16px 18px;">
                        <div style="margin-bottom:12px;">
                            <div style="color:var(--muted);font-size:.85rem;margin-bottom:4px;">ملف الترخيص</div>
                            @if ($institucion->license_number)
                                <a href="{{ asset($institucion->license_number) }}" target="_blank"
                                    style="color:#1d4ed8;text-decoration:none;font-weight:700;">
                                    عرض الملف
                                </a>
                            @else
                                <div style="color:#9ca3af;">لا يوجد ملف ترخيص</div>
                            @endif
                        </div>

                        <div>
                            <div style="color:var(--muted);font-size:.85rem;margin-bottom:4px;">ملف السجل التجاري</div>
                            @if ($institucion->commercial_record)
                                <a href="{{ asset($institucion->commercial_record) }}" target="_blank"
                                    style="color:#1d4ed8;text-decoration:none;font-weight:700;">
                                    عرض الملف
                                </a>
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
