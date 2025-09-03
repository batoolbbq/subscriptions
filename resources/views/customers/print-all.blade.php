<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير المشترك</title>
    <style>
        body {
            font-family: "tajawal", sans-serif;
            direction: rtl;
            background-color: #fff;
            font-size: 14px;
            line-height: 1.8;
        }

        .title {
            text-align: center;
            background: linear-gradient(90deg, #ff7b00, #f58220);
            padding: 10px;
            border-radius: 12px 12px 0 0;
            color: #fff;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .form-card {
            border: 1.5px solid #eee;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
            padding: 15px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .col {
            width: 48%;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #444;
        }

        .input {
            display: block;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 6px 10px;
            background: #f9f9f9;
            font-size: 13px;
            color: #222;
        }

        .footer {
            text-align: center;
            font-weight: bold;
            color: #e63946;
            margin-top: 40px;
            font-size: 15px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

@foreach($all as $customer)
<div class="form-card">
    <div class="title">بيانات المشترك / المنتفع</div>

    <div class="row">
        <div class="col">
            <label>الاسم (من الأحوال)</label>
            <div class="input">{{ $customer->fullnamea }}</div>
        </div>
        <div class="col">
            <label>الاسم (بالإنجليزية)</label>
            <div class="input">{{ $customer->fullnamee }}</div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <label>اسم الأم</label>
            <div class="input">{{ $customer->mother ?? '—' }}</div>
        </div>
        <div class="col">
            <label>تاريخ الميلاد</label>
            <div class="input">{{ $customer->yearbitrh }}</div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <label>الرقم الوطني</label>
            <div class="input">{{ $customer->nationalID }}</div>
        </div>
        <div class="col">
            <label>الجنس</label>
            <div class="input">{{ $customer->gender }}</div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <label>رقم الهاتف</label>
            <div class="input">{{ $customer->phone }}</div>
        </div>
        <div class="col">
            <label>البريد الإلكتروني</label>
            <div class="input">{{ $customer->email }}</div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <label>المصرف</label>
            <div class="input">{{ optional($customer->bank)->name }}</div>
        </div>
        <div class="col">
            <label>فرع المصرف</label>
            <div class="input">{{ optional($customer->branch)->name }}</div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <label>IBAN</label>
            <div class="input">{{ $customer->iban }}</div>
        </div>
        <div class="col">
            <label>رقم القيد</label>
            <div class="input">{{ $customer->registrationnumbers }}</div>
        </div>
    </div>

    {{-- بيانات المطابقة من الشيت --}}
    @if($customer->insured_no || $customer->pension_no || $customer->account_no)
    <div class="row">
        <div class="col">
            <label>رقم الضمان</label>
            <div class="input">{{ $customer->insured_no }}</div>
        </div>
        <div class="col">
            <label>رقم المعاش</label>
            <div class="input">{{ $customer->pension_no }}</div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label>رقم الحساب</label>
            <div class="input">{{ $customer->account_no }}</div>
        </div>
        <div class="col">
            <label>إجمالي المرتب</label>
            <div class="input">{{ $customer->total_pension }}</div>
        </div>
    </div>
    @endif
</div>
<div class="footer">
    قيمة الخدمة المقدمة هي 45 دينار ليبي فقط لا غير لكل مشترك أو منتفع
</div>
<div class="page-break"></div>
@endforeach



</body>
</html>
