@extends('layouts.master')
@section('title', 'بيانات وكالة التأمين')

@section('content')
<div class="container py-4" style="--ink:#111827;--line:#e5e7eb;--blue-50:#f3f6ff;--blue-200:#cfd8ff;--blue-700:#1d4ed8;--amber-50:#fff5e6;--amber-200:#ffd8a8;--amber-800:#92400e;--red-50:#fff1f1;--red-200:#ffc9c9;--red-700:#b42318;--green-50:#e9fbf2;--green-700:#10734a;--gray-50:#eff2f6;--gray-700:#374151;">

  {{-- العنوان + رجوع --}}
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
    <div>
      <h3 style="margin:0;font-weight:800;color:var(--ink);">بيانات وكالة التأمين</h3>
      <div style="color:#6b7280;font-size:14px;">عرض تفاصيل الوكالة والمستندات المرتبطة</div>
    </div>
    <a href="{{ route('insuranceAgents.index') }}"
       style="background:#fff;border:2px solid var(--line);color:var(--ink);padding:8px 14px;border-radius:12px;font-weight:800;text-decoration:none;">
      <i class="fa fa-arrow-right"></i> رجوع
    </a>
  </div>

  {{-- تنبيهات الجلسة --}}
  @if (session('success'))
    <div style="border:1.5px solid #bbf7d0;background:#f0fdf4;padding:12px;border-radius:8px;color:#166534;margin-bottom:12px;">
      {{ session('success') }}
    </div>
  @endif
  @if (session('error'))
    <div style="border:1.5px solid var(--red-200);background:var(--red-50);padding:12px;border-radius:8px;color:var(--red-700);margin-bottom:12px;">
      {{ session('error') }}
    </div>
  @endif
  @if (session('info'))
    <div style="border:1.5px solid var(--blue-200);background:var(--blue-50);padding:12px;border-radius:8px;color:var(--blue-700);margin-bottom:12px;">
      {{ session('info') }}
    </div>
  @endif

  {{-- بطاقة الحالة + زر التفعيل/الإلغاء --}}
  @php
    $userExists = \App\Models\User::where('email', $insuranceAgents->email)->exists();
  @endphp
  <div style="border:2px solid var(--line);border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);margin-bottom:16px;overflow:hidden;">
    <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:2px solid var(--line);padding:12px 16px;font-weight:800;color:var(--gray-700);">
      حالة الحساب
    </div>
    <div style="padding:16px;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
      <div style="font-weight:700;color:var(--ink);display:flex;align-items:center;gap:8px;">
        الحالة:
        @if ($userExists)
          <span style="display:inline-block;background:var(--green-50);color:var(--green-700);border:2px solid #a7f3d0;border-radius:6px;padding:4px 12px;font-weight:800;">
            مفعّل <i class="fa fa-circle"></i>
          </span>
        @else
          <span style="display:inline-block;background:var(--gray-50);color:var(--gray-700);border:2px solid #d1d5db;border-radius:6px;padding:4px 12px;font-weight:800;">
            غير مفعّل <i class="fa fa-circle"></i>
          </span>
        @endif
      </div>

      <form method="POST"
            action="{{ $userExists ? route('insurance-agents.deactivate', $insuranceAgents->id)
                                   : route('insurance-agents.activate', $insuranceAgents->id) }}"
            style="margin:0;">
        @csrf
        <button type="submit"
          style="background:{{ $userExists ? 'var(--red-50)' : 'var(--green-50)' }};
                 border:2px solid {{ $userExists ? 'var(--red-200)' : '#a7f3d0' }};
                 color:{{ $userExists ? 'var(--red-700)' : 'var(--green-700)' }};
                 padding:8px 18px;border-radius:999px;font-weight:800;cursor:pointer;">
          {{ $userExists ? 'إلغاء التفعيل' : 'تفعيل' }}
        </button>
      </form>
    </div>
  </div>

  {{-- بطاقة: البيانات الأساسية --}}
  <div style="border:2px solid var(--line);border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);margin-bottom:16px;overflow:hidden;">
    <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:2px solid var(--line);padding:12px 16px;font-weight:800;color:var(--gray-700);">
      البيانات الأساسية
    </div>
    <div style="padding:16px;display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px;">
      <div><div style="color:#6b7280;font-size:13px;">الاسم رباعي</div><div style="font-weight:700;color:var(--ink);">{{ $insuranceAgents->name }}</div></div>
      <div><div style="color:#6b7280;font-size:13px;">رقم الهاتف</div><div style="font-weight:700;color:var(--ink);">{{ $insuranceAgents->phone_number }}</div></div>
      <div><div style="color:#6b7280;font-size:13px;">العنوان</div><div style="font-weight:700;color:var(--ink);">{{ $insuranceAgents->address }}</div></div>
      <div><div style="color:#6b7280;font-size:13px;">البريد الإلكتروني</div><div style="font-weight:700;color:var(--ink);">{{ $insuranceAgents->email }}</div></div>
    </div>
  </div>

  {{-- بطاقة: الموقع والجهة --}}
  <div style="border:2px solid var(--line);border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);margin-bottom:16px;overflow:hidden;">
    <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:2px solid var(--line);padding:12px 16px;font-weight:800;color:var(--gray-700);">
      الموقع والجهة
    </div>
    <div style="padding:16px;display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px;">
      <div><div style="color:#6b7280;font-size:13px;">المنطقة الصحية</div><div style="font-weight:700;color:var(--ink);">{{ $insuranceAgents->cities->name }}</div></div>
      <div><div style="color:#6b7280;font-size:13px;">البلدية</div><div style="font-weight:700;color:var(--ink);">{{ $insuranceAgents->municipals->name }}</div></div>
      <div style="grid-column:1/-1;"><div style="color:#6b7280;font-size:13px;">وصف المكان</div><div style="font-weight:700;color:var(--ink);">{{ $insuranceAgents->description }}</div></div>
    </div>
  </div>

  {{-- بطاقة: المستندات --}}
  <div style="border:2px solid var(--line);border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);margin-bottom:16px;overflow:hidden;">
    <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:2px solid var(--line);padding:12px 16px;font-weight:800;color:var(--gray-700);">
      المستندات
    </div>
    <div style="padding:16px;display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px;">

      {{-- شهادة الميلاد --}}
      @if($insuranceAgents->birth_certificate_path)
      <a href="{{ asset("/storage/$insuranceAgents->birth_certificate_path") }}"
         style="display:flex;align-items:center;gap:10px;border:2px solid var(--line);border-radius:12px;padding:10px 12px;text-decoration:none;color:var(--ink);">
        <i class="fa fa-file-text-o" aria-hidden="true" style="font-size:20px;"></i>
        <span style="font-weight:700;">شهادة الميلاد</span>
      </a>
      @endif

      {{-- شهادة التخرج --}}
      @if($insuranceAgents->qualification_path)
      <a href="{{ asset("/storage/$insuranceAgents->qualification_path") }}"
         style="display:flex;align-items:center;gap:10px;border:2px solid var(--line);border-radius:12px;padding:10px 12px;text-decoration:none;color:var(--ink);">
        <i class="fa fa-file-text-o" aria-hidden="true" style="font-size:20px;"></i>
        <span style="font-weight:700;">شهادة التخرج</span>
      </a>
      @endif

      {{-- صورة المكان --}}
      @if($insuranceAgents->location_image_path)
      <a href="{{ asset("/storage/$insuranceAgents->location_image_path") }}"
         style="display:flex;align-items:center;gap:10px;border:2px solid var(--line);border-radius:12px;padding:10px 12px;text-decoration:none;color:var(--ink);">
        <i class="fa fa-file-text-o" aria-hidden="true" style="font-size:20px;"></i>
        <span style="font-weight:700;">صورة للمكان</span>
      </a>
      @endif

      {{-- مثال لو ودّك تفعل لاحقًا شهادة التأمين (معلّقة بالأعلى بالكود القديم) --}}
      {{-- @if($insuranceAgents->Insurance_certificate)
        <a href="{{ asset("/storage/$insuranceAgents->Insurance_certificate") }}" ...> إفادة التسجيل في الهيئة </a>
      @endif --}}
    </div>
  </div>

</div>
@endsection
