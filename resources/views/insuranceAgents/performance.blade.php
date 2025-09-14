@extends('layouts.master')

@section('title', 'بيانات وكيل التأمين')


{{-- روابط Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


@section('content')
    <div class="container py-4"
        style="direction:rtl;
       --ink:#1F2328;--muted:#6b7280;--line:#E5E7EB;
       --brand:#F58220;--brand-600:#ff8f34;--brand-700:#d95b00;
       --brown:#8C5346;--green-50:#e9fbf2;--green-700:#10734a;--brand-dark:#c24a00;">




        {{-- عنوان الصفحة --}}
        <div class="mb-3 text-center">
            <h3 style="margin:0;color:var(--brown);font-weight:800">بحث عن وكيل </h3>
            <div style="color:var(--muted);font-size:.9rem"> اختر اسم الوكيل او ابحث برقم الهاتف ثم اضغط بحث لعرض بيانات
                الوكيل</div>
        </div>

        {{-- نموذج البحث (داخل كارت) --}}
        <div class="card shadow-sm mb-4" style="border-radius:14px;overflow:hidden;border:1px solid #E5E7EB;">
            <div class="card-body">
                <form method="GET" action="{{ route('agents.performance.index') }}" class="row g-3 align-items-end">

                    <div class="col-md-4">
                        <label class="form-label" style="color:var(--muted);font-weight:700">رقم الهاتف (الوكيل)</label>
                        <input type="text" name="phone" value="{{ $phone }}" class="form-control search-input"
                            placeholder="مثال: 0912345678">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" style="color:var(--muted);font-weight:700">اسم الوكيل</label>
                        <select id="agentSelect" name="agent_id" class="styled-select w-100">
                            <option value="">— اختر الوكيل —</option>
                            @foreach ($agentsList as $a)
                                <option value="{{ $a->id }}" {{ request('agent_id') == $a->id ? 'selected' : '' }}>
                                    {{ $a->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>




                    <div class="col-md-2">
                        <button class="btn w-100"
                            style="background:var(--brand);color:#fff;border:none;border-radius:6px;
                           padding:10px 16px;font-weight:800;box-shadow:0 8px 20px rgba(245,130,32,.25);">
                            بحث
                        </button>
                    </div>
                </form>

                <style>
                    .styled-select {
                        border: 1.5px solid #d7dbe0;
                        border-radius: 6px;
                        padding: 10px 14px;
                        height: 46px;
                        font-size: 0.95rem;
                        color: #333;
                        background-color: #fff;
                        appearance: none;
                        /* يخفي السهم الافتراضي */
                        -webkit-appearance: none;
                        -moz-appearance: none;
                        background-image: url("data:image/svg+xml;utf8,<svg fill='%23F58220' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
                        background-repeat: no-repeat;
                        background-position: left 12px center;
                        /* السهم يسار (RTL) */
                        background-size: 18px;
                    }
                </style>


            </div>
        </div>




        {{-- لا يوجد وكيل --}}
        @if ($phone && !$agent)
            <div class="alert alert-danger" style="border-radius:14px">
                🚫 لم يتم العثور على وكيل بهذا الرقم: {{ $phone }}
            </div>
        @endif

        {{-- عند العثور على وكيل --}}
        @if ($agent)
            {{-- بطاقة بيانات الوكيل --}}
            <div
                style="border:1.5px solid var(--line);border-radius:20px;overflow:hidden;
                box-shadow:0 10px 28px rgba(0,0,0,.06);background:#fff;margin-bottom:20px;">
                <div
                    style="background:linear-gradient(135deg,var(--brand-700),var(--brand),var(--brand-600));
                  color:#fff;padding:12px 16px;font-weight:800;display:flex;align-items:center;gap:10px;">
                    <i class="fa fa-id-badge"></i>
                    <span>بيانات الوكيل</span>
                </div>

                <div class="card-body" style="padding:16px">
                    <div class="row gy-3">
                        <div class="col-md-4">
                            <div style="color:var(--muted);font-size:.9rem">الاسم</div>
                            <div style="font-weight:800;color:var(--ink)">{{ $agent->name ?? '—' }}</div>
                        </div>

                        <div class="col-md-4">
                            <div style="color:var(--muted);font-size:.9rem">رقم الهاتف</div>
                            <div style="font-weight:800;color:var(--ink)">{{ $agent->phone_number ?? '—' }}</div>
                        </div>

                        <div class="col-md-4">
                            <div style="color:var(--muted);font-size:.9rem">الإيميل</div>
                            <div style="font-weight:800;color:var(--ink)">{{ $agent->email ?? '—' }}</div>
                        </div>

                        <div class="col-md-4">
                            <div style="color:var(--muted);font-size:.9rem">المنطقة الصحية</div>
                            <div style="font-weight:800;color:var(--ink)"> {{ optional($agent->cities)->name ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div style="color:var(--muted);font-size:.9rem">البلدية</div>
                            <div style="font-weight:800;color:var(--ink)">{{ optional($agent->municipals)->name ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div style="color:var(--muted);font-size:.9rem">العنوان</div>
                            <div style="font-weight:800;color:var(--ink)">{{ $agent->address ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="text-end small" style="color:#9ca3af;margin-top:10px;">
                        Agent ID: {{ $agent->id }} •
                        Linked Users: {{ method_exists($agent, 'users') ? $agent->users->count() : 0 }}
                    </div>
                </div>
            </div>

            {{-- كارت إجمالي الخدمات (ألوان براند) --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="h-100"
                        style="border-radius:18px;background:linear-gradient(135deg,var(--brand-700),var(--brand));
                    box-shadow:0 10px 24px rgba(217,91,0,.25);color:#fff;">
                        <div class="p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div style="font-size:.9rem;">إجمالي عدد الخدمات المقدمة</div>
                                <div style="font-size:1.9rem;font-weight:800;">{{ number_format($totalServices ?? 0) }}
                                </div>
                            </div>
                            <div style="font-size:32px">🧾</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- الأزرار --}}
            <div class="row g-3 mb-2">
                <div class="col-md-6">
                    <a class="btn w-100" href="{{ route('agents.services.customers', $agent->id) }}"
                        style="border-radius:12px;background:var(--brand);color:#fff;font-weight:800;
                  padding:10px 14px;box-shadow:0 8px 20px rgba(245,130,32,.28);">
                        📋 عرض خدمات المشتركين
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="btn w-100" href="{{ route('agents.services.institutions', $agent->id) }}"
                        style="border-radius:12px;background:#8C5346;color:#fff;font-weight:800;
                  padding:10px 14px;box-shadow:0 8px 20px rgba(140,83,70,.25);">
                        🏢 عرض خدمات جهات العمل
                    </a>
                </div>
            </div>
        @endif
    </div>


@endsection
