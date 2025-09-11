@extends('layouts.master')

@section('title', 'بيانات المشترك')

@section('content')
    <style>
        :root {
            --brand: #F58220;
            --brand-dark: #d95b00;
            --border: #E5E7EB;
            --ink: #1F2328;
            --muted: #6b7280;
        }

        .section-title {
            font-weight: 800;
            margin: 18px 0 12px;
            color: var(--brand-dark);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        .info-item {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 14px;
            min-height: 70px;
        }

        .info-item label {
            display: block;
            font-size: .85rem;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .info-item .val {
            font-weight: 700;
            color: var(--ink);
            word-break: break-word;
        }

        .btn-print {
            background: var(--brand);
            color: #fff;
            font-weight: 700;
            border-radius: 8px;
            padding: 10px 18px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 16px;
            box-shadow: 0 4px 12px rgba(245, 130, 32, .25);
            transition: background .2s;
            text-decoration: none;
        }

        .btn-print:hover {
            background: var(--brand-dark);
            color: #fff;
        }
    </style>

    <div class="container" style="direction: rtl; font-family: 'Tajawal', sans-serif">

        <h3 class="section-title"> بيانات المشترك</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>الاسم</label>
                <div class="val">{{ $customer->fullnamea }}</div>
            </div>
            <div class="info-item">
                <label>الاسم بالإنجليزية</label>
                <div class="val">{{ $customer->fullnamee }}</div>
            </div>
            <div class="info-item">
                <label>الرقم الوطني</label>
                <div class="val">{{ $customer->nationalID }}</div>
            </div>
            <div class="info-item">
                <label>البريد الإلكتروني</label>
                <div class="val">{{ $customer->email ?? '—' }}</div>
            </div>
            <div class="info-item">
                <label>رقم الهاتف</label>
                <div class="val">{{ $customer->phone ?? '—' }}</div>
            </div>
            <div class="info-item">
                <label>المنطقة الصحية</label>
                <div class="val">{{ optional($customer->cities)->name ?? '—' }}</div>
            </div>
            <div class="info-item">
                <label>البلدية</label>
                <div class="val">{{ optional($customer->municipals)->name ?? '—' }}</div>
            </div>
            <div class="info-item">
                <label>المصرف</label>
                <div class="val">{{ optional($customer->bank)->name ?? '—' }}</div>
            </div>
            <div class="info-item">
                <label>الفرع</label>
                <div class="val">{{ optional($customer->bankBranch)->name ?? '—' }}</div>
            </div>
            <div class="info-item">
                <label>IBAN</label>
                <div class="val" style="direction:ltr">{{ $customer->iban ?? '—' }}</div>
            </div>
        </div>

        @if ($dependents->count())
            <h4 class="section-title">المشتركين الفرعيين</h4>
            <div class="info-grid">
                @foreach ($dependents as $dep)
                    <div class="info-item">
                        <label>الاسم</label>
                        <div class="val">{{ $dep->fullnamea }} <br>
                            <small style="color:var(--muted)">({{ $dep->nationalID }})</small>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <a href="{{ route('customers.printAll', $customer->id) }}" class="btn-print">
            <i class="fa fa-print"></i> طباعة
        </a>
    </div>
@endsection
