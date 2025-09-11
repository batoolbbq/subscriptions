<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تقرير المشترك</title>
    <style>
        body {
            font-family: "Tajawal", sans-serif;
            background: #fff;
            font-size: 13px;
            line-height: 1.6;
            margin: 20px;
        }

        h2 {
            text-align: center;
            color: #8C5346;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .section-title {
            background: linear-gradient(90deg, #f58220, #d95b00);
            color: #fff;
            padding: 6px 12px;
            font-weight: bold;
            border-radius: 6px;
            margin: 14px 0 10px 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        th,
        td {
            border: 1px solid #E5E7EB;
            padding: 7px 10px;
            text-align: right;
            vertical-align: middle;
        }

        th {
            background: #FFF7EE;
            font-weight: bold;
            color: #374151;
            font-size: 13px;
        }

        td {
            font-weight: 600;
            color: #1F2328;
            font-size: 13px;
        }

        .footer {
            text-align: center;
            font-weight: bold;
            color: #e63946;
            margin-top: 25px;
            font-size: 14px;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    @foreach ($all as $customer)
        <h2>بيانات المشترك / المنتفع</h2>

        <div class="section-title">البيانات الأساسية</div>
        <table>
            <tr>
                <th>الاسم (من الأحوال)</th>
                <th>الاسم (بالإنجليزية)</th>
            </tr>
            <tr>
                <td>{{ $customer->fullnamea ?? '—' }}</td>
                <td>{{ $customer->fullnamee ?? '—' }}</td>
            </tr>
            <tr>
                <th>تاريخ الميلاد</th>
                <th>الرقم الوطني</th>
                <th>الجنس</th>
            </tr>
            <tr>
                <td>{{ $customer->yearbitrh ?? '—' }}</td>
                <td>{{ $customer->nationalID ?? '—' }}</td>
                <td>

                    {{ $customer->gender }}



                </td>
            </tr>
            <tr>
                <th>رقم الهاتف</th>
                <th>البريد الإلكتروني</th>
                <th>رقم القيد</th>
            </tr>
            <tr>
                <td>{{ $customer->phone ?? '—' }}</td>
                <td>{{ $customer->email ?? '—' }}</td>
                <td>{{ $customer->registrationnumbers ?? '—' }}</td>
            </tr>
        </table>

        <div class="section-title">بيانات المصرف</div>
        <table>
            <tr>
                <th>المصرف</th>
                <th>الفرع</th>
                <th>IBAN</th>
            </tr>
            <tr>
                <td>{{ optional($customer->bank)->name ?? '—' }}</td>
                <td>{{ optional($customer->bankBranch)->name ?? '—' }}</td>
                <td style="direction:ltr">{{ $customer->iban ?? '—' }}</td>
            </tr>
        </table>

        @if ($customer->insured_no || $customer->pension_no || $customer->account_no || $customer->total_pension)
            <div class="section-title">البيانات المالية</div>
            <table>
                <tr>
                    <th>رقم الضمان</th>
                    <th>رقم المعاش</th>
                    <th>رقم الحساب</th>
                </tr>
                <tr>
                    <td>{{ $customer->insured_no ?? '—' }}</td>
                    <td>{{ $customer->pension_no ?? '—' }}</td>
                    <td>{{ $customer->account_no ?? '—' }}</td>
                </tr>
                <tr>
                    <th colspan="2">إجمالي المرتب</th>
                    <td>{{ $customer->total_pension ?? '—' }}</td>
                </tr>
            </table>
        @endif

        <div class="footer">
            قيمة الخدمة المقدمة هي 45 دينار ليبي فقط لا غير لكل مشترك أو منتفع
        </div>

        <div class="page-break"></div>
    @endforeach

</body>

</html>
