@extends('layouts.master')

@section('title', 'تفاصيل جهة العمل')
@section('css')
    {{-- خط + أيقونات --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
@endsection

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
                <div style="color:var(--muted); font-size:0.9rem;">
                    تم الإنشاء: {{ $institucion->created_at?->format('Y-m-d H:i') }} • آخر تحديث:
                    {{ $institucion->updated_at?->format('Y-m-d H:i') }}
                </div>
            </div>

            <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                <a href="{{ route('institucions.index') }}"
                    style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:var(--ink);
                      border:1.5px solid var(--line);border-radius:999px;padding:8px 16px;font-weight:800;
                      text-decoration:none;">
                    <i class="fa-solid fa-arrow-right"></i> الرجوع للقائمة
                </a>
            </div>
        </div>

        {{-- رسالة النجاح --}}
        @if (session('success'))
            <div role="alert"
                style="background:var(--green-50);border:1.5px solid #86efac;color:var(--green-700);
                    padding:10px 14px;border-radius:14px;font-weight:800;margin-bottom:1rem;">
                {{ session('success') }}
            </div>
        @endif

        {{-- أزرار التفعيل/الإيقاف --}}
        <div class="mb-3" style="display:flex;gap:.5rem;flex-wrap:wrap;">
            @can('institucions.toggle-status')
                <form action="{{ route('institucions.toggle-status', $institucion) }}" method="POST" style="margin:0;">
                    @csrf @method('PATCH')
                    @if ($institucion->status)
                        <button type="submit"
                            style="display:inline-flex;align-items:center;gap:6px;background:var(--red-50);
                                   color:var(--red-700);border:1.5px solid var(--red-200);border-radius:999px;
                                   padding:8px 16px;font-weight:800;">
                            <i class="fa-solid fa-ban"></i> إيقاف
                        </button>
                    @else
                        <button type="submit"
                            style="display:inline-flex;align-items:center;gap:6px;background:var(--green-50);
                                   color:var(--green-700);border:1.5px solid #86efac;border-radius:999px;
                                   padding:8px 16px;font-weight:800;">
                            <i class="fa-solid fa-check"></i> تفعيل
                        </button>
                    @endif
                </form>
            @endcan
        </div>

        {{-- الكارد 1: بيانات أساسية --}}
        <div class="mb-4"
            style="border:1.5px solid var(--line);border-radius:40px;box-shadow:0 18px 40px rgba(0,0,0,.12);background:#fff;">
            <div
                style="background:linear-gradient(135deg,var(--hdr1),var(--hdr2),var(--hdr3),var(--hdr4));
                    color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;
                    border-radius:40px 40px 0 0;">
                <span
                    style="background:var(--hdr3);color:#fff;width:34px;height:34px;display:grid;place-items:center;
                         border-radius:999px;font-size:.95rem;">
                    <i class="fa-solid fa-circle-info"></i>
                </span>
                <h6 style="margin:0;font-weight:800;color:#fff;">بيانات أساسية</h6>
            </div>
            <div style="padding:22px 20px 26px;">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div style="color:var(--muted);font-size:.9rem;margin-bottom:4px;">نوع جهة العمل</div>
                        <div style="font-weight:600;color:var(--ink);">
                            {{ optional($institucion->workCategory)->name ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div style="color:var(--muted);font-size:.9rem;margin-bottom:4px;">الاشتراك</div>
                        <div style="font-weight:600;color:var(--ink);">
                            {{ optional($institucion->subscription)->name ?? '#' . (optional($institucion->subscription)->id ?? '—') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="color:var(--muted);font-size:.9rem;margin-bottom:4px;">الرقم التجاري</div>
                        <div style="font-weight:600;color:var(--ink);">{{ $institucion->commercial_number ?: '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div style="color:var(--muted);font-size:.9rem;margin-bottom:4px;">الوكيل التأميني</div>
                        <div style="font-weight:600;color:var(--ink);">
                            {{ optional($institucion->insuranceAgent)->name ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- الكارد 2: المستندات (الترخيص + السجل + الإكسل) --}}
        <div class="mb-4"
            style="border:1.5px solid var(--line);border-radius:40px;box-shadow:0 18px 40px rgba(0,0,0,.12);background:#fff;">
            <div
                style="background:linear-gradient(135deg,var(--hdr1),var(--hdr2),var(--hdr3),var(--hdr4));
           color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:600;
           border-radius:40px 40px 0 0;">
                <span
                    style="background:var(--hdr3);color:#fff;width:34px;height:34px;display:grid;place-items:center;
             border-radius:999px;font-size:.95rem;">
                    <i class="fa-solid fa-file"></i>
                </span>
                <h6 style="margin:0;font-weight:600;color:#fff;">المستندات</h6>
            </div>

            <div style="padding:18px;display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:12px;">
                {{-- ملف الترخيص --}}
                <div>
                    <div style="color:var(--muted);font-size:.9rem;margin-bottom:6px;">ملف الترخيص</div>
                    @if ($institucion->license_number)
                        <a href="{{ asset($institucion->license_number) }}" target="_blank"
                            style="display:inline-flex;align-items:center;gap:8px;background:#FFF5E6;
                  border:1.5px solid #FFD8A8;color:#92400E;padding:10px 14px;border-radius:999px;
                  font-weight:600;text-decoration:none;">
                            <i class="fa-regular fa-file-pdf"></i> الترخيص
                        </a>
                    @else
                        <div style="color:#9ca3af;">لا يوجد ملف ترخيص</div>
                    @endif
                </div>

                {{-- ملف السجل التجاري --}}
                <div>
                    <div style="color:var(--muted);font-size:.9rem;margin-bottom:6px;">ملف السجل التجاري</div>
                    @if ($institucion->commercial_record)
                        <a href="{{ asset($institucion->commercial_record) }}" target="_blank"
                            style="display:inline-flex;align-items:center;gap:8px;background:#FFF5E6;
                  border:1.5px solid #FFD8A8;color:#92400E;padding:10px 14px;border-radius:999px;
                  font-weight:600;text-decoration:none;">
                            <i class="fa-solid fa-file-invoice"></i> السجل التجاري
                        </a>
                    @else
                        <div style="color:#9ca3af;">لا يوجد ملف سجل تجاري</div>
                    @endif
                </div>

                {{-- شيت الإكسل --}}
                <div>
                    <div style="color:var(--muted);font-size:.9rem;margin-bottom:6px;">شيت الإكسل</div>
                    @if (!empty($institucion->excel_sheet))
                        <a href="{{ asset($institucion->excel_sheet) }}" target="_blank"
                            style="display:inline-flex;align-items:center;gap:8px;background:var(--green-50);
                  border:1.5px solid #86efac;color:var(--green-700);padding:10px 14px;border-radius:999px;
                  font-weight:600;text-decoration:none;">
                            <i class="fa-regular fa-file-excel"></i> الإكسل
                        </a>
                    @else
                        <div style="color:#9ca3af;">لا يوجد ملف إكسل</div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
