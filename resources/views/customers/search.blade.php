@extends('layouts.master')

@section('title', 'بحث عن مشترك')

@section('content')
    <div class="container py-4" style="direction:rtl; font-family:'Tajawal', sans-serif;">

        {{-- عنوان الصفحة --}}
        <h3 class="mb-3 text-center" style="color:#F58220; font-weight:800;">🔎 بحث عن مشترك</h3>

        {{-- رسائل خطأ --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $e)
                    <div>{{ $e }}</div>
                @endforeach
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        {{-- نموذج البحث --}}
        <form action="{{ route('customers.search') }}" method="post">
            @csrf

            <div class="mb-3">
                <label class="form-label">الرقم الوطني</label>
                <input type="text" name="national_id" class="form-control" placeholder="أدخل الرقم الوطني">
            </div>

            <div class="mb-3">
                <label class="form-label">رقم الهاتف</label>
                <input type="text" name="phone" class="form-control" placeholder="09XXXXXXXX">
            </div>

            <div class="mb-3">
                <label class="form-label">الرقم التأميني</label>
                <input type="text" name="insurance_no" class="form-control" placeholder="أدخل الرقم التأميني">
            </div>

            <button type="submit" class="btn btn-primary w-100">بحث</button>
        </form>

    </div>
@endsection
