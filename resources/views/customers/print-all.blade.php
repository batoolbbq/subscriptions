<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تقرير المشترك</title>
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            direction: rtl;
            margin: 18px;
            color: #1F2328;
            font-size: 13px;
            line-height: 1.6;
        }

        h2.title {
            margin: 0 0 14px;
            text-align: center;
            color: #8C5346;
            font-weight: 800;
        }

        .sec-title {
            background: #F58220;
            color: #fff;
            border-radius: 6px;
            padding: 8px 12px;
            font-weight: 800;
            margin: 16px 0 8px;
        }

        table.kv {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.kv td {
            border: 1px solid #eee;
            padding: 10px 12px;
            vertical-align: middle;
            box-sizing: border-box;
            word-break: break-word;
        }

        .k-label {
            background: #FFF3E7;
            font-weight: 700;
            width: 22%;
            white-space: nowrap;
        }

        .k-value {
            background: #fff;
            width: 28%;
        }

        .ltr {
            direction: ltr;
            text-align: left;
            unicode-bidi: bidi-override;
        }

        .sign-row {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-top: 8px;
            position: fixed;
            bottom: 100px;
            left: 0;
            right: 0;
        }

        .sign-label {
            font-weight: 700;
        }

        .sign-line {
            height: 28px;
            border-bottom: 1px solid #999;
            margin-top: 8px;
        }

        .footer-wrap {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            background: #fff;
        }

        .fee {
            padding: 8px;
            text-align: center;
            font-weight: 800;
        }

        .fee .red {
            color: #d92901;
        }

        .footer-bar {
            height: 4px;
            background: #F58220;
            margin-top: 6px;
        }

        .footer {
            font-size: 12px;
            color: #6b7280;
            text-align: center;
            padding: 6px 0;
        }

        .footer strong {
            color: #8C5346;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    @foreach ($all as $customer)
        <!-- 🟠 اسم الوكالة -->

        <h2 class="title"> الوكالة:
            {{ auth()->user()->first_name ?? '' }} {{ auth()->user()->last_name ?? '' }}
            / {{ optional(auth()->user()->insuranceAgents()->first()->municipals)->name ?? '' }}

        </h2>

        <h2 class="title">بيانات المشترك / المنتفع</h2>

        <!-- البيانات الأساسية -->
        <div class="sec-title">البيانات الأساسية</div>
        <table class="kv">
            <tr>
                <td class="k-label">الاسم (من الأحوال)</td>
                <td class="k-value">{{ $customer->fullnamea ?? '—' }}</td>
                <td class="k-label">الاسم (بالإنجليزية)</td>
                <td class="k-value">{{ $customer->fullnamee ?? '—' }}</td>
            </tr>
            <tr>
                <td class="k-label">تاريخ الميلاد</td>
                <td class="k-value">{{ $customer->yearbitrh ?? '—' }}</td>
                <td class="k-label">الرقم الوطني</td>
                <td class="k-value">{{ $customer->nationalID ?? '—' }}</td>
            </tr>
            <tr>
                <td class="k-label">الجنس</td>
                <td class="k-value">
                    @if ($customer->gender == 1)
                        ذكر
                    @elseif ($customer->gender == 2)
                        أنثى
                    @endif
                </td>
                <td class="k-label">رقم الهاتف</td>
                <td class="k-value">{{ $customer->phone ?? '—' }}</td>
            </tr>
            <tr>
                <td class="k-label">البريد الإلكتروني</td>
                <td class="k-value">{{ $customer->email ?? '—' }}</td>
                <td class="k-label">رقم القيد</td>
                <td class="k-value">{{ $customer->registrationnumbers ?? '—' }}</td>
            </tr>
        </table>

        <!-- بيانات المصرف -->
        <div class="sec-title">بيانات المصرف</div>
        <table class="kv">
            <tr>
                <td class="k-label">المصرف</td>
                <td class="k-value">{{ optional($customer->bank)->name ?? '—' }}</td>
                <td class="k-label">الفرع</td>
                <td class="k-value">{{ optional($customer->bankBranch)->name ?? '—' }}</td>
            </tr>
            <tr>
                <td class="k-label">IBAN</td>
                <td class="k-value ltr" colspan="3">{{ $customer->iban ?? '—' }}</td>
            </tr>
        </table>

        @if ($customer->insured_no || $customer->pension_no || $customer->account_no || $customer->total_pension)
            <!-- البيانات المالية -->
            <div class="sec-title">البيانات المالية</div>
            <table class="kv">
                <tr>
                    <td class="k-label">رقم الضمان</td>
                    <td class="k-value">{{ $customer->insured_no ?? '—' }}</td>
                    <td class="k-label">رقم المعاش</td>
                    <td class="k-value">{{ $customer->pension_no ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="k-label">رقم الحساب</td>
                    <td class="k-value">{{ $customer->account_no ?? '—' }}</td>
                    <td class="k-label">إجمالي المرتب</td>
                    <td class="k-value">{{ $customer->total_pension ?? '—' }}</td>
                </tr>
            </table>
        @endif

        <!-- 🟠 خانة التوقيع -->
        <div class="sign-row">
            <span class="sign-label">ختم الوكالة:</span>
            <div class="sign-line"></div>
        </div>

        <!-- 🟠 الفوتر -->
        <div class="footer-wrap">
            <div class="fee">
                قيمة الخدمة المقدمة:
                <span class="red">إصدار جديد</span> —
                <span class="red">خمسة وأربعون دينار ليبي (45 د.ل)</span>
                لكل مشترك أو منتفع
            </div>

            <div class="footer-bar"></div>
            <div class="footer" dir="LTR">
                info@phif.gov.ly &nbsp;&nbsp; <strong>1577</strong> هاتف &nbsp;&nbsp;
                84266 طرابلس ليبيا &nbsp;–&nbsp; حي الاندلس – صندوق التأمين الصحي العام
            </div>
        </div>

        <div class="page-break"></div>
    @endforeach

</body>

</html>
