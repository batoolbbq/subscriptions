@extends('layouts.master')

@section('content')
<div class="container py-4" style="direction: rtl; text-align: right;">
    <h3 class="mb-4" style="font-weight: 800; color: #8C5346;">إضافة ترميز تصنيف فرعي</h3>

    {{-- رسالة نجاح أو خطأ --}}
    @if ($errors->any())
        <div class="alert alert-danger">
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

        {{-- اختيار الأب --}}
        <div class="mb-3">
            <label for="parent_id" style="font-weight:600; color:#444;">التصنيف الأساسي</label>
            <BR></BR>
            <select name="parent_id" id="parent_id" class="form-select custom-input" required>
                <option value="">— اختر التصنيف —</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" data-code="{{ $parent->code }}">
                        {{ $parent->name }} ({{ $parent->code }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- الكود --}}
        <div class="mb-3">
            <label for="code" style="font-weight:600; color:#444;">الترميز</label>
            <input type="text" name="code" id="code"
                   class="form-control custom-input"
                   placeholder="مثال: MOH" required>
        </div>

        {{-- الاسم --}}
        <div class="mb-4">
            <label for="name" style="font-weight:600; color:#444;">الاسم</label>
            <input type="text" name="name" id="name"
                   class="form-control custom-input"
                   placeholder="مثال: وزارة الصحة" required>
        </div>

        {{-- الأزرار --}}
        <div class="d-flex gap-3">
            <button type="submit" class="btn flex-fill save-btn"> حفظ</button>
            <a href="{{ route('home') }}" class="btn flex-fill cancel-btn"> رجوع</a>
        </div>
    </form>
</div>

{{-- سكربت لضبط الكود الأب --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const parentSelect = document.getElementById('parent_id');
    const codeInput = document.getElementById('code');
    let fixedPrefix = '';

    parentSelect.addEventListener('change', function () {
        const parentCode = this.options[this.selectedIndex].dataset.code || '';
        fixedPrefix = parentCode;
        codeInput.value = fixedPrefix;
    });

    codeInput.addEventListener('input', function () {
        if (!this.value.startsWith(fixedPrefix)) {
            this.value = fixedPrefix; // يمنع المسح
        }
    });
});
</script>

{{-- ستايل إضافي --}}
<style>
    .custom-input {
        border-radius:8px;
        padding:10px;
        border:1px solid #ddd;
        box-shadow: inset 0 2px 4px rgba(0,0,0,.05); /* ظل داخلي من الجوانب */
        transition: all .2s ease-in-out;
    }
    .custom-input:focus {
        border-color:#F58220;
        box-shadow:0 0 0 3px rgba(245,130,32,.25), inset 0 2px 4px rgba(0,0,0,.08);
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
