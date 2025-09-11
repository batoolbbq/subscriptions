@extends('layouts.master')
@section('title', 'نتيجة البحث')

@section('content')
    <div class="container py-4" style="direction:rtl">

        <div class="card p-4" style="border-radius:16px;box-shadow:0 10px 24px rgba(0,0,0,.06)">
            <h4 class="mb-3" style="color:#8C5346;font-weight:800">📋 بيانات المشترك / المنتفع</h4>
            <hr>

            <div class="row mb-2">
                <div class="col-md-6"><b>الاسم الكامل:</b> {{ $customer->fullnamea }}</div>
                <div class="col-md-6"><b>الرقم الوطني:</b> {{ $customer->nationalID }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6"><b>الرقم التأميني:</b> {{ $customer->regnumber }}</div>
                <div class="col-md-6"><b>رقم الهاتف:</b> {{ $customer->phone }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6"><b>البريد الإلكتروني:</b> {{ $customer->email }}</div>
                <div class="col-md-6"><b>تاريخ الميلاد:</b> {{ $customer->yearbitrh }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6"><b>الجنس:</b> {{ $customer->gender }}</div>
                <div class="col-md-6"><b>الحالة الاجتماعية:</b> {{ optional($customer->socialstatus)->name }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6"><b>الفئة الرئيسية:</b>
                    {{ optional($customer->subscription->beneficiariesCategory)->name }}</div>
                <div class="col-md-6"><b>الفئة الفرعية:</b> {{ optional($customer->beneficiariesSupCategory)->name ?? '-' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6"><b>البلدية:</b> {{ optional($customer->municipal)->name }}</div>
                <div class="col-md-6"><b>المنطقة الصحية:</b> {{ optional($customer->city)->name }}</div>
            </div>

            @if ($customer->institucions_id)
                <div class="row mb-2">
                    <div class="col-md-6"><b>جهة العمل:</b> {{ optional($customer->institucion)->name }}</div>
                    <div class="col-md-6"><b>أقرب نقطة:</b> {{ $customer->nearestpoint }}</div>
                </div>
            @endif

            @if ($customer->bank_id)
                <div class="row mb-2">
                    <div class="col-md-6"><b>المصرف:</b> {{ optional($customer->bank)->name }}</div>
                    <div class="col-md-6"><b>فرع المصرف:</b> {{ optional($customer->bankBranch)->name }}</div>
                </div>
            @endif

            <hr>
            <div class="text-center mt-3">
                <a href="{{ route('customers.print.card', $customer->id) }}" target="_blank" class="btn btn-success px-4">
                    🖨️ إصدار / طباعة
                </a>
            </div>
        </div>
    </div>
@endsection
