<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>طباعة بيانات المشترك</title>
    <style>
        body {
            font-family: 'Tajawal', "DejaVu Sans", sans-serif;
            color: #111;
            margin: 0;
            padding: 16px
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px
        }

        .title {
            font-weight: 800;
            font-size: 20px
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px
        }

        .card {
            border: 1px solid #eee;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 10px
        }

        .label {
            color: #6b7280;
            font-size: 12px
        }

        .value {
            font-weight: 700;
            font-size: 14px
        }

        .no-print {
            margin-bottom: 8px
        }

        @media print {
            .no-print {
                display: none
            }

            .card {
                border: 0;
                padding: 8px
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="title">بيانات المشترك</div>
        <div class="no-print">
            <button onclick="window.print()">🖨️ طباعة</button>
        </div>
    </div>

    <div class="card">
        <div class="grid">
            <div>
                <div class="label">الاسم الكامل</div>
                <div class="value">{{ $customer->fullnamea ?? '-' }}</div>
            </div>
            <div>
                <div class="label">الرقم التأميني</div>
                <div class="value">{{ $customer->regnumber ?? '-' }}</div>
            </div>

            <div>
                <div class="label">الرقم الوطني</div>
                <div class="value">{{ $customer->nationalID ?? '-' }}</div>
            </div>
            <div>
                <div class="label">رقم الهاتف</div>
                <div class="value">{{ $customer->phone ?? '-' }}</div>
            </div>

            <div>
                <div class="label">الجنس</div>
                <div class="value">{{ $customer->gender ?? '-' }}</div>
            </div>
            <div>
                <div class="label">تاريخ الميلاد</div>
                <div class="value">{{ $customer->yearbitrh ?? '-' }}</div>
            </div>

            <div>
                <div class="label">الحالة الاجتماعية</div>
                <div class="value">{{ optional($customer->socialstatuses)->name ?? '-' }}</div>
            </div>
            <div>
                <div class="label">فصيلة الدم</div>
                <div class="value">{{ optional($customer->bloodtypes)->name ?? '-' }}</div>
            </div>

            <div>
                <div class="label">البلدية</div>
                <div class="value">{{ optional($customer->municipals)->name ?? '-' }}</div>
            </div>
            <div>
                <div class="label">المنطقة الصحية</div>
                <div class="value">{{ optional($customer->cities)->name ?? '-' }}</div>
            </div>

            @if ($customer->institucion)
                <div>
                    <div class="label">جهة العمل</div>
                    <div class="value">{{ $customer->institucion->name }}</div>
                </div>
            @endif

            @if ($customer->bank)
                <div>
                    <div class="label">المصرف</div>
                    <div class="value">{{ $customer->bank->name }}</div>
                </div>
            @endif

            @if ($customer->bankBranch)
                <div>
                    <div class="label">فرع المصرف</div>
                    <div class="value">{{ $customer->bankBranch->name }}</div>
                </div>
            @endif
        </div>
    </div>

    @if ($customer->subscription)
        <div class="card">
            <div class="label">الاشتراك</div>
            <div class="value">
                {{ optional($customer->subscription->beneficiariesCategory)->name ?? '—' }}
            </div>

            @if ($customer->subscription->values && $customer->subscription->values->count())
                <div style="margin-top:8px">
                    <div class="label">تفاصيل المساهمات</div>
                    <ul>
                        @foreach ($customer->subscription->values as $sv)
                            <li>
                                {{ optional($sv->type)->name ?? 'نوع' }} :
                                {{ $sv->value }} {{ $sv->is_percentage ? '%' : 'دينار' }} لمدة {{ $sv->duration }}
                                شهر
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

</body>

</html>
