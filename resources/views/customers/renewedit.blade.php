@extends('layouts.master')

@section('title', 'تجديد بيانات مشترك')

@section('content')
    <style>
        :root {
            --brand: #F58220;
            --brand-dark: #d96b00;
            --bg: #FFF9F3;
            --panel: #fff;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #E5E7EB;
            --shadow: 0 8px 24px rgba(0, 0, 0, .06);
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }

        .wrap {
            width: 100%;
            max-width: 850px;
        }

        .title-area {
            text-align: center;
            margin-bottom: 25px;
        }

        .title-area h3 {
            font-weight: 800;
            color: var(--brand);
            font-size: 1.9rem;
            margin-bottom: 5px;
        }

        .title-area p {
            color: var(--muted);
            font-size: 1rem;
        }

        .card {
            background: var(--panel);
            border-radius: 22px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: box-shadow .2s ease;
        }

        .card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        .card-head {
            background: linear-gradient(90deg, var(--brand-dark), var(--brand));
            color: #fff;
            font-weight: 800;
            text-align: center;
            padding: 16px;
            font-size: 1.15rem;
            letter-spacing: .3px;
        }

        .card-body {
            padding: 28px;
        }

        label {
            font-weight: 700;
            color: var(--muted);
            margin-bottom: 6px;
            font-size: .95rem;
        }

        input,
        select {
            width: 100%;
            height: 48px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 0 14px;
            font-size: 1rem;
            color: var(--text);
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
        }

        input:focus,
        select:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(245, 130, 32, .15);
        }

        .btn {
            all: unset;
            display: inline-block;
            width: auto;
            background: var(--brand);
            color: #fff;
            font-weight: 800;
            font-size: 1.1rem;
            border-radius: 999px;
            padding: 0.9rem 2.8rem;
            text-align: center;
            box-shadow: 0 10px 20px rgba(245, 130, 32, 0.25);
            cursor: pointer;
            transition: background .2s, transform .15s;
        }

        .btn:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
        }

        .row.g-3 {
            row-gap: 18px;
        }

        .divider {
            height: 1px;
            background: #eee;
            margin: 20px 0;
        }

        .section-title {
            font-weight: 800;
            color: var(--brand);
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .text-center.mt-4 {
            margin-top: 35px !important;
        }
    </style>

    <div class="page">
        <div class="wrap">
            <div class="title-area">
                <h3> تجديد بيانات مشترك / منتفع</h3>
                <p>راجع البيانات وعدّل فقط إن لزم، ثم اضغط <strong>"حفظ ومتابعة"</strong>.</p>
            </div>

            <div class="card">
                <div class="card-head">📋 بيانات المشترك</div>
                <div class="card-body">
                    <form action="{{ route('customer.updateRenew', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- الاسم والرقم الوطني --}}
                        <h6 class="section-title">البيانات الشخصية</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>الاسم الكامل</label>
                                <input type="text" value="{{ $customer->fullnamea }}" readonly>
                                <input type="hidden" name="fullnamea" value="{{ $customer->fullnamea }}">
                            </div>
                            <div class="col-md-6">
                                <label>الرقم الوطني</label>
                                <input type="text" value="{{ $customer->nationalID }}" readonly>
                                <input type="hidden" name="nationalID" value="{{ $customer->nationalID }}">
                            </div>
                        </div>

                        {{-- الجنس وتاريخ الميلاد --}}
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label>الجنس</label>
                                <select name="gender_display" disabled>
                                    <option value="1" @selected($customer->gender == 1)>ذكر</option>
                                    <option value="2" @selected($customer->gender == 2)>أنثى</option>
                                </select>
                                <input type="hidden" name="gender" value="{{ $customer->gender }}">
                            </div>
                            <div class="col-md-6">
                                <label>تاريخ الميلاد</label>
                                <input type="date" readonly value="{{ $customer->yearbitrh }}">
                                <input type="hidden" name="yearbitrh" value="{{ $customer->yearbitrh }}">
                            </div>
                        </div>

                        <div class="divider"></div>

                        {{-- الهاتف والإيميل --}}
                        <h6 class="section-title">بيانات التواصل</h6>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label>رقم الهاتف</label>
                                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label>البريد الإلكتروني</label>
                                <input type="email" name="email" value="{{ old('email', $customer->email) }}">
                            </div>
                        </div>

                        <div class="divider"></div>

                        {{-- المدينة والبلدية --}}
                        <h6 class="section-title">المنطقة الجغرافية</h6>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label>المنطقة الصحية</label>
                                <select name="cities_id">
                                    @foreach (\App\Models\City::all() as $c)
                                        <option value="{{ $c->id }}" @selected($customer->cities_id == $c->id)>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>البلدية</label>
                                <select name="municipals_id">
                                    @foreach (\App\Models\Municipal::all() as $m)
                                        <option value="{{ $m->id }}" @selected($customer->municipals_id == $m->id)>
                                            {{ $m->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="divider"></div>

                        {{-- أقرب نقطة والمصرف --}}
                        <h6 class="section-title">البيانات المالية</h6>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label>أقرب نقطة</label>
                                <input type="text" name="nearestpoint"
                                    value="{{ old('nearestpoint', $customer->nearestpoint) }}">
                            </div>
                            <div class="col-md-6">
                                <label>المصرف</label>
                                <select name="bank_id" id="bank_id">
                                    @foreach (\App\Models\Bank::orderBy('name')->get() as $b)
                                        <option value="{{ $b->id }}" @selected($customer->bank_id == $b->id)>
                                            {{ $b->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- الفرع والحساب --}}
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label>فرع المصرف</label>
                                <select name="bank_branch_id" id="bank_branch_id">
                                    <option value="{{ $customer->bank_branch_id }}">
                                        {{ optional($customer->branch)->name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>رقم الحساب الدولي (IBAN)</label>
                                <input type="text" name="iban" value="{{ old('iban', $customer->iban) }}">
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn"> حفظ ومتابعة التصوير</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
