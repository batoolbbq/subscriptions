@extends('layouts.master')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-4" style="direction: rtl; text-align: right; max-width: 700px;">

    <h3 class="mb-4" style="font-weight: 800; color: #8C5346;">إضافة تصنيف أساسي</h3>

    {{-- عرض الأخطاء --}}
    @if ($errors->any())
        <div class="alert alert-danger rounded-3 shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- الفورم --}}
    <form action="{{ route('workplace_codes.store') }}" method="POST"
          style="background:#fff; border:1px solid #eee; border-radius:12px; padding:24px; box-shadow:0 6px 12px rgba(0,0,0,.05);">
        @csrf

        {{-- الاسم --}}
        <div class="mb-3">
            <label for="name" style="font-weight:600; color:#444;">الاسم</label>
            <input type="text" name="name" id="name"
                   class="form-control custom-input"
                   placeholder="مثال: وزارات" required>
        </div>

        {{-- الترميز --}}
        <div class="mb-4">
            <label for="code" style="font-weight:600; color:#444;">الترميز</label>
            <input type="text" name="code" id="code"
                   class="form-control custom-input"
                   placeholder="مثال: MO" required>
        </div>

        {{-- الأزرار --}}
        <div class="d-flex gap-3">
            <button type="submit" class="btn flex-fill save-btn"> حفظ</button>
            <a href="{{ route('workplace_codes.index') }}" class="btn flex-fill cancel-btn"> رجوع</a>
        </div>
    </form>
</div>

{{-- ستايل إضافي --}}
<style>
    .custom-input {
        border-radius:8px;
        padding:10px;
        border:1px solid #ddd;
        transition: all .2s ease-in-out;
    }
    .custom-input:focus {
        border-color:#F58220;
        box-shadow:0 0 0 3px rgba(245,130,32,.25);
    }

    .save-btn {
        background:#F58220;
        color:#fff;
        font-weight:600;
        border:none;
        border-radius:8px;
        padding:10px;
        font-size:1rem;
        transition:.2s;
    }
    .save-btn:hover {
        background:#d95b00;
    }

    .cancel-btn {
        background:#f3f4f6;
        color:#333;
        font-weight:600;
        border:none;
        border-radius:8px;
        padding:10px;
        font-size:1rem;
        text-align:center;
        text-decoration:none;
        transition:.2s;
    }
    .cancel-btn:hover {
        background:#e5e7eb;
        color:#111;
    }
</style>


@endsection
