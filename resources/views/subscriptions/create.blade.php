@extends('layouts.master')
@section('css')
@section('title')
    الاشتراكات
@stop
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="mb-0">اضافة اشتراك </h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
                <li class="breadcrumb-item"><a href="" class="default-color">الرئيسية</a></li>
                <li class="breadcrumb-item active">اضافة اشتراك </li>
            </ol>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
        </div>
    </div>

    <div class="col-md-8">
        <div class="box-content card shadow p-4">
            <form method="POST" action="{{ route('subscriptions.store') }}">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- اسم الاشتراك --}}
                <div class="form-group">
                    <label>اسم الاشتراك</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                {{-- فئة جهة العمل --}}
                <div class="form-group">
                    <label>فئة </label>
                    <select name="beneficiaries_categories_id" class="form-control" required>
                        <option value="">اختر الفئة</option>
                        @foreach ($workCategories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('beneficiaries_categories_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>

                @foreach ($types as $type)
                    <h5 class="mt-4" style="color: #cc5500;">{{ $type->name }}</h5>
                    <div class="form-group">
                        <label>نوع القيمة</label>
                        <select name="types[{{ $type->id }}][is_percentage]" class="form-control">
                            <option value="" selected disabled>اختر</option>
                            <option value="1" {{ old("types.$type->id.is_percentage") == '1' ? 'selected' : '' }}>
                                نسبة</option>
                            <option value="0" {{ old("types.$type->id.is_percentage") == '0' ? 'selected' : '' }}>
                                قيمة ثابتة</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>القيمة</label>
                        <input type="number" step="any" min="0" name="types[{{ $type->id }}][value]"
                            value="{{ old("types.$type->id.value") }}" class="form-control" placeholder="مثلاً: 5.5">
                    </div>
                    <div class="form-group">
                        <label>المدة</label>
                        <input type="number" min="0" name="types[{{ $type->id }}][duration]"
                            value="{{ old("types.$type->id.duration") }}" class="form-control only-positive">
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const selects = document.querySelectorAll('select[name^="types"]');

                            selects.forEach(select => {
                                const typeId = select.name.match(/\d+/)[0];
                                const input = document.querySelector(`input[name="types[${typeId}][value]"]`);

                                select.addEventListener('change', () => {
                                    input.value = ''; 
                                });

                            });
                        });
                    </script>
                @endforeach

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-success">حفظ</button>
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.only-positive').forEach(function(input) {
            input.addEventListener('input', function() {
                this.value = this.value
                    .replace(/[^\d.]/g, '') // تمنع الحروف والرموز
                    .replace(/^0+(\d)/, '$1') // تمنع صفر في البداية
                    .replace(/(\..*)\./g, '$1'); // تمنع أكثر من نقطة
            });
        });
    });
</script>
@endsection
