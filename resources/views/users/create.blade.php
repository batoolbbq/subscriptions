@extends('layouts.master')
@section('title', 'إضافة مستخدم')

@section('content')
<div class="container py-4" style="font-family: sans-serif;">

    {{-- العنوان وزر الرجوع --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <div>
            <h3 style="margin:0;font-weight:800;color:#111827;">إضافة مستخدم</h3>
            <div style="color:#6b7280;font-size:14px;">قم بملء البيانات المطلوبة لإضافة مستخدم جديد.</div>
        </div>
        <a href="{{ route('users.index') }}" 
           style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:6px 14px;font-weight:700;text-decoration:none;">
            <i class="fa fa-arrow-right"></i> رجوع للقائمة
        </a>
    </div>

    {{-- رسائل الأخطاء --}}
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

    {{-- الفورم --}}
    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div style="border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);overflow:hidden;margin-bottom:16px;">
            <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid #D0D5DD;padding:10px 14px;display:flex;align-items:center;gap:8px;">
                <span style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid #FFD8A8;color:#92400E;font-weight:800;">
                    1
                </span>
                <h6 style="margin:0;font-weight:800;color:#374151;">بيانات المستخدم</h6>
            </div>
            <div style="padding:16px;">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label style="font-weight:700;color:#374151;">اسم المستخدم <span style="color:red">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" 
                               class="form-control @error('username') is-invalid @enderror" placeholder="اسم المستخدم">
                        @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label style="font-weight:700;color:#374151;">الاسم الأول <span style="color:red">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" 
                               class="form-control @error('first_name') is-invalid @enderror" placeholder="الاسم الأول">
                        @error('first_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label style="font-weight:700;color:#374151;">اسم العائلة <span style="color:red">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" 
                               class="form-control @error('last_name') is-invalid @enderror" placeholder="اسم العائلة">
                        @error('last_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label style="font-weight:700;color:#374151;">البريد الإلكتروني <span style="color:red">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="form-control @error('email') is-invalid @enderror" placeholder="البريد الإلكتروني">
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label style="font-weight:700;color:#374151;">رقم الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" 
                               class="form-control @error('phone') is-invalid @enderror" placeholder="رقم الهاتف">
                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label style="font-weight:700;color:#374151;">كلمة المرور <span style="color:red">*</span></label>
                        <input type="password" name="password" 
                               class="form-control @error('password') is-invalid @enderror" placeholder="كلمة المرور">
                        <small class="form-text text-muted">الحد الأدنى 8 أحرف</small>
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label style="font-weight:700;color:#374151;">الحالة</label>
                        <select name="status" class="form-control select2 @error('status') is-invalid @enderror">
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>مفعل</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>غير مفعل</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label style="font-weight:700;color:#374151;">الدور</label>
                        <select name="role" class="form-control select2 @error('role') is-invalid @enderror">
                            <option value="">اختر الدور</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- الأزرار --}}
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <button type="submit" 
                    style="display:inline-flex;align-items:center;gap:6px;background:#FFF7EE;color:#92400E;border:1.5px solid #FFD8A8;border-radius:999px;padding:8px 18px;font-weight:800;">
                <i class="fa fa-save"></i> حفظ المستخدم
            </button>
            <a href="{{ route('users.index') }}" 
               style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:8px 18px;font-weight:800;text-decoration:none;">
                إلغاء
            </a>
        </div>

    </form>
</div>
@endsection
