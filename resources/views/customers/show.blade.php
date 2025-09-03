@extends('layouts.master')

@section('title', 'بيانات المشترك')

@section('content')
<div class="container" style="direction: rtl; font-family: 'Tajawal', sans-serif">
    <h3 class="mb-3">بيانات المشترك</h3>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>الاسم:</strong> {{ $customer->fullnamea }}</p>
            <p><strong>الاسم بالإنجليزية:</strong> {{ $customer->fullnamee }}</p>
            <p><strong>الرقم الوطني:</strong> {{ $customer->nationalID }}</p>
            <p><strong>البريد الإلكتروني:</strong> {{ $customer->email }}</p>
            <p><strong>رقم الهاتف:</strong> {{ $customer->phone }}</p>
            <p><strong>المنطقة الصحية:</strong> {{ optional($customer->city)->name }}</p>
            <p><strong>البلدية:</strong> {{ optional($customer->municipal)->name }}</p>
            <p><strong>المصرف:</strong> {{ optional($customer->bank)->name }}</p>
            <p><strong>الفرع:</strong> {{ optional($customer->branch)->name }}</p>
            <p><strong>IBAN:</strong> {{ $customer->iban }}</p>
        </div>
    </div>

    @if($dependents->count())
    <h4>المشتركين الفرعيين</h4>
    <ul>
        @foreach($dependents as $dep)
        <li>{{ $dep->fullnamea }} ({{ $dep->nationalID }})</li>
        @endforeach
    </ul>
    @endif

    <a href="{{ route('customers.printAll', $customer->id) }}"
        class="btn btn-primary">
        <i class="fa fa-print"></i> طباعة
    </a>

</div>
@endsection