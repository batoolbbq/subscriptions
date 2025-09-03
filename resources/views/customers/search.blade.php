@extends('layouts.master')
@section('title', 'بحث عن مشترك')

@section('content')
    <div class="container py-4"
        style="direction:rtl;
 --ink:#1F2328;--muted:#6b7280;--line:#E5E7EB;
 --brand:#F58220;--brand-600:#ff8f34;--brand-700:#d95b00;
 --brown:#8C5346;--bg-1:#FFF7EE;--card-shadow:0 6px 18px rgba(0,0,0,.06)">

        <div class="mb-3 text-center">
            <h3 style="margin:0;color:var(--brown);font-weight:800">🔎 بحث عن مشترك</h3>
            <div style="color:var(--muted);font-size:.9rem">اختر طريقة البحث المناسبة</div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $e)
                    <div>{{ $e }}</div>
                @endforeach
            </div>
        @endif

        @if (session('notfound'))
            <div class="alert alert-warning">{{ session('notfound') }}</div>
        @endif

        {{-- صف الكروت الثلاثة --}}
        <div class="row g-3 mb-4">
            {{-- 1) بالرقم الوطني --}}
            <div class="col-md-4">
                <div class="card" style="border:0;box-shadow:var(--card-shadow);border-radius:16px;">
                    <div class="card-header"
                        style="background:var(--bg-1);border:none;border-top-left-radius:16px;border-top-right-radius:16px;color:var(--brown);font-weight:800;">
                        البحث بالرقم الوطني
                    </div>
                    <div class="card-body">
                        <form action="{{ route('customers.search.nid') }}" method="post" class="row g-2">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">الرقم الوطني</label>
                                <input type="text" name="nationalID" value="{{ old('nationalID') }}" class="form-control"
                                    placeholder="رقم وطني">
                            </div>
                            <div class="col-12 text-center mt-2">
                                <button class="btn" type="submit"
                                    style="background:var(--brand);color:white;font-weight:700;border-radius:10px;padding:.5rem 1.1rem;">بحث</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 2) بالرقم التأميني / القيد --}}
            <div class="col-md-4">
                <div class="card" style="border:0;box-shadow:var(--card-shadow);border-radius:16px;">
                    <div class="card-header"
                        style="background:var(--bg-1);border:none;border-top-left-radius:16px;border-top-right-radius:16px;color:var(--brown);font-weight:800;">
                        البحث برقم القيد (التأميني)
                    </div>
                    <div class="card-body">
                        <form action="{{ route('customers.search.reg') }}" method="POST" class="row g-2">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">رقم القيد</label>
                                <input type="text" name="regnumber" value="{{ old('regnumber') }}" class="form-control"
                                    placeholder="رقم القيد/التأميني">
                            </div>
                            <div class="col-12 text-center mt-2">
                                <button class="btn" type="submit"
                                    style="background:var(--brand);color:white;font-weight:700;border-radius:10px;padding:.5rem 1.1rem;">بحث</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 3) برقم الهاتف --}}
            <div class="col-md-4">
                <div class="card" style="border:0;box-shadow:var(--card-shadow);border-radius:16px;">
                    <div class="card-header"
                        style="background:var(--bg-1);border:none;border-top-left-radius:16px;border-top-right-radius:16px;color:var(--brown);font-weight:800;">
                        البحث برقم الهاتف
                    </div>
                    <div class="card-body">
                        <form action="{{ route('customers.search.phone') }}" method="post" class="row g-2">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control"
                                    placeholder="09XXXXXXXX">
                            </div>
                            <div class="col-12 text-center mt-2">
                                <button class="btn" type="submit"
                                    style="background:var(--brand);color:white;font-weight:700;border-radius:10px;padding:.5rem 1.1rem;">بحث</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- نتيجة البحث (موحّدة) --}}
        @isset($customer)
            <div class="card" style="border:0;box-shadow:var(--card-shadow);border-radius:16px;">
                <div class="card-header"
                    style="background:var(--bg-1);border:none;border-top-left-radius:16px;border-top-right-radius:16px;color:var(--brown);font-weight:800;">
                    بيانات المشترك
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        {{-- أساسي --}}
                        <div class="col-md-4">
                            <div class="fw-bold">رقم القيد</div>
                            <div class="text-muted">{{ $customer->regnumber ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-bold">الرقم الوطني</div>
                            <div class="text-muted">{{ $customer->nationalID ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-bold">رقم الهاتف</div>
                            <div class="text-muted">{{ $customer->phone ?? '-' }}</div>
                        </div>

                        <div class="col-md-4">
                            <div class="fw-bold">الاسم بالكامل</div>
                            <div class="text-muted">{{ $customer->fullnamea ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-bold">البريد الإلكتروني</div>
                            <div class="text-muted">{{ $customer->email ?? '-' }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="fw-bold">الجنس</div>
                            <div class="text-muted">{{ $customer->gender ?? '-' }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="fw-bold">تاريخ الميلاد</div>
                            <div class="text-muted">{{ $customer->yearbitrh ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-bold">رقم الجواز</div>
                            <div class="text-muted">{{ $customer->passportnumber ?? '-' }}</div>
                        </div>

                        {{-- مرجعي --}}
                        <div class="col-md-4">
                            <div class="fw-bold">الفئة الرئيسية</div>
                            <div class="text-muted">{{ optional($customer->beneficiariesCategoryRelation)->name ?? '-' }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="fw-bold">الفئة الفرعية</div>
                            <div class="text-muted">{{ optional($customer->beneficiariesSupCategoryRelation)->name ?? '-' }}
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="fw-bold">الحالة الاجتماعية</div>
                            <div class="text-muted">{{ optional($customer->socialstatuses)->name ?? '-' }}</div>
                        </div>

                        <div class="col-md-4">
                            <div class="fw-bold">فصيلة الدم</div>
                            <div class="text-muted">{{ optional($customer->bloodtypes)->name ?? '-' }}</div>
                        </div>

                        <div class="col-md-4">
                            <div class="fw-bold">البلدية</div>
                            <div class="text-muted">{{ optional($customer->municipals)->name ?? '-' }}</div>
                        </div>

                        <div class="col-md-4">
                            <div class="fw-bold">أقرب نقطة</div>
                            <div class="text-muted">{{ $customer->nearestpoint ?? '-' }}</div>
                        </div>

                        <div class="col-md-4">
                            <div class="fw-bold">المنطقة الصحية</div>
                            <div class="text-muted">{{ optional($customer->cities)->name ?? '-' }}</div>
                        </div>



                        {{-- اختيارية --}}
                        @if ($customer->institucions_id && $customer->institucion)
                            <div class="col-md-4">
                                <div class="fw-bold">جهة العمل</div>
                                <div class="text-muted">{{ $customer->institucion->name }}</div>
                            </div>
                        @endif
                        @if ($customer->bank_id && $customer->bank)
                            <div class="col-md-4">
                                <div class="fw-bold">المصرف</div>
                                <div class="text-muted">{{ $customer->bank->name }}</div>
                            </div>
                        @endif
                        @if ($customer->bank_branch_id && $customer->bankBranch)
                            <div class="col-md-4">
                                <div class="fw-bold">فرع المصرف</div>
                                <div class="text-muted">{{ $customer->bankBranch->name }}</div>
                            </div>
                        @endif
                        @if (!empty($customer->iban))
                            <div class="col-md-6">
                                <div class="fw-bold">رقم الحساب الدولي (IBAN)</div>
                                <div class="text-muted" style="direction:ltr">{{ $customer->iban }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endisset

    </div>
@endsection
