@extends('layouts.master')
@section('title', 'تعديل الاشتراك')

@section('content')
    <div class="row small-spacing">
        <div class="col-md-8">
            <div class="box-content card shadow p-4">
                <form method="POST" action="{{ route('subscriptions.update', $subscription->id) }}">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label>اسم الاشتراك</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $subscription->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>فئة المستفيدين</label>
                        <select name="beneficiaries_categories_id" class="form-control" required>
                            <option value="">اختر الفئة</option>
                            @foreach ($beneficiariesCategories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('beneficiaries_categories_id', $subscription->beneficiaries_categories_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    @foreach ($types as $type)
                        @php
                            $existingValue = $subscription->values->firstWhere('subscription_type', $type->id);
                        @endphp
                        <h5 class="mt-4" style="color: #cc5500;">{{ $type->name }}</h5>
                        <div class="form-group">
                            <label>نوع القيمة</label>
                            <select name="types[{{ $type->id }}][is_percentage]" class="form-control">
                                <option value="" disabled {{ is_null($existingValue) ? 'selected' : '' }}>اختر
                                </option>
                                <option value="1" {{ $existingValue?->is_percentage == '1' ? 'selected' : '' }}>نسبة
                                </option>
                                <option value="0" {{ $existingValue?->is_percentage == '0' ? 'selected' : '' }}>قيمة
                                    ثابتة</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>القيمة</label>
                            <input type="number" step="any" min="0" name="types[{{ $type->id }}][value]"
                                value="{{ old("types.$type->id.value", $existingValue?->value) }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>المدة (بالأشهر)</label>
                            <input type="number" min="0" name="types[{{ $type->id }}][duration]"
                                value="{{ old("types.$type->id.duration", $existingValue?->duration) }}"
                                class="form-control">
                        </div>
                    @endforeach

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-success">تحديث</button>
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">رجوع</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
