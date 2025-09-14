<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>طباعة بيانات المشترك</title>
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            direction: rtl;
            margin: 18px;
            color: #1F2328
        }

        h2.title {
            margin: 0 0 14px;
            text-align: center;
            color: #8C5346;
            font-weight: 800
        }

        /* شريط عنوان القسم */
        .sec-title {
            background: #F58220;
            color: #fff;
            border-radius: 6px;
            padding: 8px 12px;
            font-weight: 800;
            margin: 16px 0 8px
        }

        /* جدول القيم */
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
            white-space: nowrap
        }

        .k-value {
            background: #fff;
            width: 28%
        }

        .ltr {
            direction: ltr;
            text-align: left;
            unicode-bidi: bidi-override;
        }

        /* ملاحظة الرسوم */
        .fee-note {
            margin-top: 14px;
            text-align: center;
            color: #d92901;
            font-weight: 800
        }

        .sign-row {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-top: 8px
        }

        .content {
            flex: 1;
            /* يخلي المحتوى يأخذ المساحة المتبقية */
        }

        .sign-label {
            font-weight: 700
        }

        .sign-line {
            height: 28px;
            border-bottom: 1px solid #999;
            margin-top: 8px
        }

        /* التذييل السفلي */
        .footer-wrap {
            margin-top: auto;
            /* يخليه دايمًا آخر الصفحة */
        }




        .footer-bar {
            height: 4px;
            background: #F58220;
            margin-bottom: 6px;
        }

        .footer {
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }

        .footer strong {
            color: #8C5346
        }


        .footer-wrap,
        .sign-row {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .sign-row {
            bottom: 60px;
            /* فوق الفوتر بشوي */
            width: 100%;
        }

        .footer-wrap {
            bottom: 0;
            width: 100%;
        }

        /* جداول صغيرة ثابتة العرض */
        .w-50 {
            width: 50%
        }

        .w-33 {
            width: 33.333%
        }

        .w-25 {
            width: 25%
        }

        .w-100 {
            width: 100%
        }


        /* قسم قيمة الخدمة */
        .fee {
            padding: 10px 8px;
            margin: 12px 0px;
            font-weight: 800
        }

        .fee .red {
            color: #d92901
        }


        @media print {
            body {
                margin: 0
            }
        }
    </style>
</head>

<body>

      <h2 class="title"> الوكالة:
            {{ auth()->user()->first_name ?? '' }} {{ auth()->user()->last_name ?? '' }}
            / {{ optional(auth()->user()->insuranceAgents()->first()->municipals)->name ?? '' }}

        </h2>
    <h2 class="title">بيانات المشترك / المنتفع</h2>

    <!-- البيانات الأساسية -->
    <div class="sec-title">البيانات الأساسية</div>
    <table class="kv">
        <colgroup>
            <col style="width:22%">
            <col style="width:28%">
            <col style="width:22%">
            <col style="width:28%">
        </colgroup>

        <tr>
            <td class="k-label">نوع الفئة</td>
            <td class="k-value">
                {{ optional($customer->beneficiariesSupCategoryRelation)->name ?? (optional($customer->beneficiariesCategoryRelation)->name ?? '') }}
            </td>
            <td class="k-label">حالة الحساب</td>
            <td class="k-value">{{ $customer->active ?? 0 ? 'مفعّل' : 'غير مفعّل' }}</td>
        </tr>

        <tr>
            <td class="k-label">الاسم باللغة العربية</td>
            <td class="k-value">{{ $customer->fullnamea ?? '' }}</td>
            <td class="k-label">الاسم (بالإنجليزية)</td>
            <td class="k-value">{{ $customer->fullnamee ?? '' }}</td>
        </tr>

        <tr>
            <td class="k-label">البلدية</td>
            <td class="k-value">{{ optional($customer->municipals)->name ?? '' }}</td>
            <td class="k-label">المنطقة الصحية</td>
            <td class="k-value">{{ optional($customer->cities)->name ?? '' }}</td>
        </tr>

        <tr>
            <td class="k-label">تاريخ التسجيل</td>
            <td class="k-value">{{ optional($customer->created_at)->format('d/m/y') ?? '' }}</td>
            <td class="k-label">تاريخ الميلاد</td>
            <td class="k-value">{{ $customer->yearbitrh ?? '' }}</td>
        </tr>

        <tr>
            <td class="k-label">الجنس</td>
            <td class="k-value">
                @if ($customer->gender == 1)
                    ذكر
                @elseif($customer->gender == 2)
                    أنثى
                @else
                    {{ $customer->gender ?? '' }}
                @endif
            </td>
            <td class="k-label">فصيلة الدم</td>
            <td class="k-value">{{ optional($customer->bloodtypes)->name ?? '' }}</td>
        </tr>

        <tr>
            <td class="k-label">رقم الهاتف</td>
            <td class="k-value">{{ $customer->phone ?? '' }}</td>
            <td class="k-label">الرقم الوطني</td>
            <td class="k-value">{{ $customer->nationalID ?? '' }}</td>
        </tr>

        <tr>
            <td class="k-label">رقم القيد</td>
            <td class="k-value">{{ $customer->registrationnumbers ?? '' }}</td>
            <td class="k-label">رقم الجواز</td>
            <td class="k-value">{{ $customer->passportnumber ?? '' }}</td>
        </tr>

        <tr>
            <td class="k-label">رقم التأمين</td>
            <td class="k-value">{{ $customer->regnumber ?? '' }}</td>
            <td class="k-label">البريد الإلكتروني</td>
            <td class="k-value">{{ $customer->email ?? '' }}</td>
        </tr>

        <tr>
            <td class="k-label">جهة / مكان العمل</td>
            <td class="k-value" colspan="3">{{ optional($customer->institucion)->name ?? '' }}</td>
        </tr>
    </table>

    <!-- بيانات المصرف -->
    <div class="sec-title">بيانات المصرف</div>
    <table class="kv">
        <colgroup>
            <col style="width:22%">
            <col style="width:28%">
            <col style="width:22%">
            <col style="width:28%">
        </colgroup>

        <tr>
            <td class="k-label">المصرف</td>
            <td class="k-value">{{ optional($customer->bank)->name ?? '' }}</td>
            <td class="k-label">الفرع</td>
            <td class="k-value">
                @php
                    $branch = optional($customer->bankBranch)->name ?? (optional($customer->bank_branch)->name ?? '');
                @endphp
                {{ $branch }}
            </td>
        </tr>

        <tr>
            <td class="k-label">رقم الحساب</td>
            <td class="k-value">{{ $customer->account_no ?? '' }}</td>
            <td class="k-label">IBAN</td>
            <td class="k-value ltr">{{ $customer->iban ?? '' }}</td>
        </tr>
    </table>





    <div class="sign-row">
        <span class="sign-label">ختم الوكالة:</span>
        <div class="sign-line"></div>

    </div>

    <div class="footer-wrap">

        {{-- <div class="fee">
            قيمة الخدمة المقدمة: <span class="red" >بدل فاقد / تالف</span> — 
            <span class="red">30 د.ل</span> لكل
            مشترك
            أو منتفع

        </div>  --}}
        {{--
         <div class="fee">
            قيمة الخدمة المقدمة: <span class="red">إصدار جديد</span> —
            <span class="red">خمسة وأربعون دينار ليبي (45 د.ل)</span> لكل مشترك أو منتفع
         </div> --}}

        <div class="footer-bar"></div>
        <div class="footer" dir="LTR">
            info@phif.gov.ly &nbsp;&nbsp; <strong>1577</strong> هاتف &nbsp;&nbsp; 84266 طرابلس ليبيا &nbsp;–&nbsp; حي
            الاندلس – صندوق التأمين الصحي العام
        </div>
    </div>

</body>

</html>
