@extends('layouts.master')

@section('title', 'الخدمة غير متاحة')

@section('content')
    <div class="container py-5 d-flex justify-content-center align-items-center" style="min-height:70vh; direction:rtl;">
        <div class="card shadow-lg text-center p-4" style="border-radius:20px; max-width:500px;">

            <div style="font-size:3rem; color:#F58220; margin-bottom:16px;">
                ⚠️
            </div>

            <h3 style="font-weight:800; color:#8C5346;">الخدمة غير متاحة حاليًا</h3>
            <p style="color:#6b7280; margin-top:10px; font-size:1rem;">
                ميزة <strong>التحويل من فئة إلى فئة</strong> غير مفعلة في الوقت الحالي.
                يرجى المحاولة لاحقًا أو التواصل مع الدعم الفني.
            </p>

            <a href="{{ url()->previous() }}" class="btn mt-3"
                style="background:#F58220; color:#fff; border-radius:999px; padding:.6rem 1.5rem; font-weight:700; text-decoration:none;">
                ⬅ الرجوع
            </a>
        </div>
    </div>
@endsection
