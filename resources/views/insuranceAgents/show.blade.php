@extends('layouts.master')
@section('title', 'بيانات وكالة التأمين')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">


@section('content')
    <div class="container py-4"
        style="--brand:#F58220;--brand-600:#ff8f34;--brown:#8C5346;--ink:#1F2328;--muted:#6b7280;--line:#E5E7EB;
            --hdr1:#d95b00;--hdr2:#F58220;--hdr3:#FF8F34;--hdr4:#ffb066;
            --green-50:#e9fbf2;--green-700:#10734a;--gray-50:#eff2f6;--gray-700:#374151;
            --red-50:#fff1f1;--red-200:#ffc9c9;--red-700:#b42318;">

        {{-- العنوان + رجوع --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <h3 style="margin:0;font-weight:800;color:var(--brown);font-family:'Tajawal',system-ui;">بيانات وكالة التأمين
                </h3>
                {{-- <br>
                <div style="color:var(--muted);font-size:14px;">عرض تفاصيل الوكالة والمستندات المرتبطة</div>
            </br> --}}

            </div>

            <a href="{{ route('insuranceAgents.index') }}"
                style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;
                  background:#fff;color:var(--ink);border:1.5px solid var(--line);
                  border-radius:999px;padding:10px 16px;font-weight:900;text-decoration:none;
                  box-shadow:0 8px 18px rgba(0,0,0,.06);"
                onmouseover="this.style.background='#f9fafb';" onmouseout="this.style.background='#fff';">
                <i class="fa fa-arrow-right"></i> رجوع
            </a>
        </div>

        {{-- التنبيهات --}}
        @if (session('success'))
            <div
                style="border:1.5px solid #86efac;background:#f0fdf4;padding:12px;border-radius:14px;color:#166534;margin-bottom:12px;box-shadow:0 10px 28px rgba(0,0,0,.08);font-weight:800;">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div
                style="border:1.5px solid var(--red-200);background:var(--red-50);padding:12px;border-radius:14px;color:var(--red-700);margin-bottom:12px;box-shadow:0 10px 28px rgba(0,0,0,.08);font-weight:800;">
                {{ session('error') }}
            </div>
        @endif
        @if (session('info'))
            <div
                style="border:1.5px solid #FFD8A8;background:#FFF7EE;padding:12px;border-radius:14px;color:#92400E;margin-bottom:12px;box-shadow:0 10px 28px rgba(0,0,0,.08);font-weight:800;">
                {{ session('info') }}
            </div>
        @endif

        @php
            $userExists = \App\Models\User::where('email', $insuranceAgents->email)->exists();
        @endphp

        {{-- بطاقة حالة الحساب --}}
        <div
            style="background:#fff;border:1.5px solid var(--line);border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);overflow:hidden;margin-bottom:16px;">
            <div
                style="background:linear-gradient(135deg,var(--hdr1) 0%,var(--hdr2) 35%,var(--hdr3) 70%,var(--hdr4) 100%);
                    color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                <span
                    style="background:var(--hdr3);color:#fff;width:34px;height:34px;display:grid;place-items:center;border-radius:999px;font-size:.95rem;box-shadow:0 10px 22px rgba(245,130,32,.35);">
                    <i class="fa fa-user-shield"></i>
                </span>
                <h6 style="margin:0;font-weight:800;color:#fff;">حالة الحساب</h6>
            </div>

            <div
                style="padding:22px 20px 26px;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
                <div style="font-weight:800;color:var(--ink);display:flex;align-items:center;gap:8px;">
                    الحالة:
                    @if ($userExists)
                        <span
                            style="display:inline-block;background:var(--green-50);color:var(--green-700);border:1.5px solid #86efac;border-radius:999px;padding:6px 12px;font-weight:800;">مفعّل</span>
                    @else
                        <span
                            style="display:inline-block;background:var(--gray-50);color:var(--gray-700);border:1.5px solid #d1d5db;border-radius:999px;padding:6px 12px;font-weight:800;">غير
                            مفعّل</span>
                    @endif
                </div>

                <form method="POST"
                    action="{{ $userExists ? route('insurance-agents.deactivate', $insuranceAgents->id) : route('insurance-agents.activate', $insuranceAgents->id) }}"
                    style="margin:0;">
                    @csrf
                    <button type="submit"
                        style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;text-align:center;
                               padding:12px 22px;border-radius:999px;font-weight:900;letter-spacing:.3px;
                               background:{{ $userExists ? 'var(--red-50)' : 'var(--brand)' }};
                               color:{{ $userExists ? 'var(--red-700)' : '#fff' }};
                               border:1.5px solid {{ $userExists ? 'var(--red-200)' : 'var(--brand)' }};
                               box-shadow:0 12px 26px rgba(245,130,32,.15);"
                        onmouseover="if(!{{ $userExists ? 'true' : 'false' }}) this.style.background='var(--brand-600)';"
                        onmouseout="if(!{{ $userExists ? 'true' : 'false' }}) this.style.background='var(--brand)';">
                        {{ $userExists ? 'إلغاء التفعيل' : 'تفعيل' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- بطاقة البيانات الأساسية --}}
        <div
            style="background:#fff;border:1.5px solid var(--line);border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);overflow:hidden;margin-bottom:16px;">
            <div
                style="background:linear-gradient(135deg,var(--hdr1) 0%,var(--hdr2) 35%,var(--hdr3) 70%,var(--hdr4) 100%);
                    color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                <span
                    style="background:var(--hdr3);color:#fff;width:34px;height:34px;display:grid;place-items:center;border-radius:999px;font-size:.95rem;box-shadow:0 10px 22px rgba(245,130,32,.35);">1</span>
                <h6 style="margin:0;font-weight:800;color:#fff;">البيانات الأساسية</h6>
            </div>

            <div
                style="padding:22px 20px 26px;display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:14px;">
                <div>
                    <label
                        style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">الاسم
                        رباعي</label>
                    <div style="font-weight:800;color:var(--ink);">{{ $insuranceAgents->name }}</div>
                </div>
                <div>
                    <label style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">رقم
                        الهاتف</label>
                    <div style="font-weight:800;color:var(--ink);">{{ $insuranceAgents->phone_number }}</div>
                </div>
                <div>
                    <label
                        style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">العنوان</label>
                    <div style="font-weight:800;color:var(--ink);">{{ $insuranceAgents->address }}</div>
                </div>
                <div>
                    <label
                        style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">البريد
                        الإلكتروني</label>
                    <div style="font-weight:800;color:var(--ink);">{{ $insuranceAgents->email }}</div>
                </div>
            </div>
        </div>

        {{-- بطاقة الموقع والجهة --}}
        <div
            style="background:#fff;border:1.5px solid var(--line);border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);overflow:hidden;margin-bottom:16px;">
            <div
                style="background:linear-gradient(135deg,var(--hdr1) 0%,var(--hdr2) 35%,var(--hdr3) 70%,var(--hdr4) 100%);
                    color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                <span
                    style="background:var(--hdr3);color:#fff;width:34px;height:34px;display:grid;place-items:center;border-radius:999px;font-size:.95rem;box-shadow:0 10px 22px rgba(245,130,32,.35);">2</span>
                <h6 style="margin:0;font-weight:800;color:#fff;">الموقع والجهة</h6>
            </div>

            <div
                style="padding:22px 20px 26px;display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:14px;">
                <div>
                    <label
                        style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">المنطقة
                        الصحية</label>
                    <div style="font-weight:800;color:var(--ink);">{{ $insuranceAgents->cities->name }}</div>
                </div>
                <div>
                    <label
                        style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">البلدية</label>
                    <div style="font-weight:800;color:var(--ink);">{{ $insuranceAgents->municipals->name }}</div>
                </div>
                <div style="grid-column:1/-1;">
                    <label style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">وصف
                        المكان</label>
                    <div style="font-weight:800;color:var(--ink);">{{ $insuranceAgents->description }}</div>
                </div>
            </div>
        </div>

        {{-- بطاقة المستندات --}}
        {{-- بطاقة المستندات --}}
        <div
            style="background:#fff;border:1.5px solid var(--line);border-radius:24px;
            box-shadow:0 18px 40px rgba(0,0,0,.12);overflow:hidden;margin-bottom:16px;">
            <div
                style="background:linear-gradient(135deg,var(--hdr1) 0%,var(--hdr2) 35%,
                var(--hdr3) 70%,var(--hdr4) 100%);
                color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                <span
                    style="background:var(--hdr3);color:#fff;width:34px;height:34px;
                     display:grid;place-items:center;border-radius:999px;font-size:.95rem;
                     box-shadow:0 10px 22px rgba(245,130,32,.35);">
                    <i class="fa-solid fa-file"></i>
                </span>
                <h6 style="margin:0;font-weight:800;color:#fff;">المستندات</h6>
            </div>

            <div
                style="padding:22px 20px 26px;display:grid;
                grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px;">

                @if ($insuranceAgents->birth_certificate_path)
                    <a href="{{ asset('insurancagents_files/' . $insuranceAgents->birth_certificate_path) }}"
                        style="display:flex;align-items:center;gap:10px;border:1.5px solid var(--line);
                      border-radius:16px;padding:12px 14px;text-decoration:none;color:var(--ink);">
                        <i class="fa-solid fa-id-card" style="font-size:20px;color:var(--brand);"></i>
                        <span style="font-weight:800;">شهادة الميلاد</span>
                    </a>
                @endif

                @if ($insuranceAgents->qualification_path)
                    <a href="{{ asset('insurancagents_files/' . $insuranceAgents->qualification_path) }}"
                        style="display:flex;align-items:center;gap:10px;border:1.5px solid var(--line);
                      border-radius:16px;padding:12px 14px;text-decoration:none;color:var(--ink);">
                        <i class="fa-solid fa-graduation-cap" style="font-size:20px;color:var(--brand);"></i>
                        <span style="font-weight:800;">شهادة التخرج</span>
                    </a>
                @endif

                @if ($insuranceAgents->location_image_path)
                    <a href="{{ asset('insurancagents_files/' . $insuranceAgents->location_image_path) }}"
                        style="display:flex;align-items:center;gap:10px;border:1.5px solid var(--line);
                      border-radius:16px;padding:12px 14px;text-decoration:none;color:var(--ink);">
                        <i class="fa-regular fa-image" style="font-size:20px;color:var(--brand);"></i>
                        <span style="font-weight:800;">صورة للمكان</span>
                    </a>
                @endif
            </div>
        </div>

    </div>
@endsection
