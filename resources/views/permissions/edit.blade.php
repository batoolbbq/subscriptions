@extends('layouts.master')

@section('title', 'تعديل الصلاحية')

@section('content')
<div class="container py-4" style="font-family: sans-serif;">

    {{-- العنوان وزر الرجوع --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <div>
            <h3 style="margin:0;font-weight:800;color:#111827;">تعديل الصلاحية</h3>
            <div style="color:#6b7280;font-size:14px;">يمكنك تعديل بيانات الصلاحية المطلوبة.</div>
        </div>
        <a href="{{ route('permissions.index') }}" style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:6px 14px;font-weight:700;text-decoration:none;">
            <i class="fa fa-arrow-right"></i> رجوع للقائمة
        </a>
    </div>

    {{-- عرض الأخطاء --}}
    @if ($errors->any())
        <div style="border:1.5px solid #fecaca;background:#fff5f5;padding:12px;border-radius:8px;margin-bottom:16px;">
            <div style="font-weight:700;margin-bottom:6px;">تحقق من الحقول التالية:</div>
            <ul style="margin:0;padding-left:20px;">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
        @method('PATCH')
        @csrf

        {{-- البطاقة --}}
        <div style="border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);margin-bottom:16px;overflow:hidden;">
            <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid #D0D5DD;padding:10px 14px;display:flex;align-items:center;gap:8px;">
                <span style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid #FFD8A8;color:#92400E;font-weight:800;">1</span>
                <h6 style="margin:0;font-weight:800;color:#374151;">بيانات الصلاحية</h6>
            </div>
            <div style="padding:16px;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label style="font-weight:700;color:#374151;">الاسم <span style="color:red;">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $permission->name) }}" placeholder="الاسم" class="form-control" style="border:1.5px solid #E5E7EB;" required>
                    </div>
                    <div class="col-md-6">
                        <label style="font-weight:700;color:#374151;">الاسم بالعربي <span style="color:red;">*</span></label>
                        <input type="text" name="name_arabic" value="{{ old('name_arabic', $permission->arabic_name) }}" placeholder="الاسم بالعربي" class="form-control" style="border:1.5px solid #E5E7EB;" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- الأزرار --}}
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <button type="submit" style="display:inline-flex;align-items:center;gap:6px;background:#FFF7EE;color:#92400E;border:1.5px solid #FFD8A8;border-radius:999px;padding:8px 18px;font-weight:800;">
                <i class="fa fa-save"></i> حفظ
            </button>
            <a href="{{ route('permissions.index') }}" style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:8px 18px;font-weight:800;text-decoration:none;">
                إلغاء
            </a>
        </div>

    </form>
</div>
@endsection
