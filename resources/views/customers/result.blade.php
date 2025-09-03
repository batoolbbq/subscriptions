@extends('layouts.master')

@section('title', 'نتيجة البحث')

@section('content')
<div class="container py-4" style="direction:rtl;font-family:'Tajawal',sans-serif">

    <h3 class="mb-3 text-center">📄 بيانات المشترك</h3>

    <div class="card p-4">
        <p><strong>الاسم:</strong> {{ $customer->name ?? '-' }}</p>
        <p><strong>الرقم الوطني:</strong> {{ $customer->national_id ?? '-' }}</p>
        <p><strong>الرقم التأميني:</strong> {{ $customer->insurance_no ?? '-' }}</p>
        <p><strong>الهاتف:</strong> {{ $customer->phone ?? '-' }}</p>
        <p><strong>تاريخ الميلاد:</strong> {{ $customer->birth_date ?? '-' }}</p>
    </div>

    <a href="{{ route('customers.search.form') }}" class="btn btn-secondary mt-3">🔙 رجوع للبحث</a>
</div>
@endsection
