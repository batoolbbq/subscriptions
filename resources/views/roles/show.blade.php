@extends('layouts.master')
@section('title', 'عرض الدور')

@section('content')
<div class="container py-4" style="--ink:#111827;--line:#e5e7eb;--blue-50:#f3f6ff;--blue-200:#cfd8ff;--blue-700:#1d4ed8;--amber-50:#fff5e6;--amber-200:#ffd8a8;--amber-800:#92400e;--gray-50:#eff2f6;--gray-700:#374151;">

  {{-- العنوان --}}
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
    <div>
      <h3 style="margin:0;font-weight:800;color:var(--ink);">{{ ucfirst($role->name) }} - الدور</h3>
      <div style="color:#6b7280;font-size:14px;">عرض تفاصيل الدور والصلاحيات المرتبطة به</div>
    </div>
    <a href="{{ route('roles.index') }}" style="background:#fff;border:2px solid var(--line);color:var(--ink);padding:8px 14px;border-radius:12px;font-weight:800;text-decoration:none;">
      <i class="fa fa-arrow-right"></i> رجوع
    </a>
  </div>

  {{-- بطاقة معلومات الدور --}}
  <div style="border:2px solid var(--line);border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);margin-bottom:20px;">
    <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:2px solid var(--line);padding:12px 16px;font-weight:800;color:var(--gray-700);">
      تفاصيل الدور
    </div>
    <div style="padding:16px;">
      <p><strong>الاسم:</strong> {{ $role->name }}</p>
    </div>
  </div>

  {{-- جدول الصلاحيات --}}
  <div style="border:2px solid var(--line);border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);overflow:hidden;">
    <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:2px solid var(--line);padding:12px 16px;font-weight:800;color:var(--gray-700);">
      الصلاحيات المرتبطة
    </div>
    <div class="table-responsive">
      <table style="width:100%;margin:0;color:var(--ink);border-collapse:collapse;">
        <thead>
          <tr>
            <th style="padding:12px;border-bottom:2px solid var(--line);color:var(--gray-700);font-weight:800;">الاسم</th>
            <th style="padding:12px;border-bottom:2px solid var(--line);color:var(--gray-700);font-weight:800;">الاسم العربي</th>
            <th style="padding:12px;border-bottom:2px solid var(--line);color:var(--gray-700);font-weight:800;">المنصة</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rolePermissions as $permission)
            <tr style="border-top:1px solid var(--line);">
              <td style="padding:12px;">{{ $permission->name }}</td>
              <td style="padding:12px;">{{ $permission->arabic_name ?: $permission->name }}</td>
              <td style="padding:12px;">{{ $permission->guard_name }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- الأزرار --}}
  <div style="display:flex;gap:10px;margin-top:20px;">
    <a href="{{ route('roles.edit', $role->id) }}" style="background:var(--amber-50);border:2px solid var(--amber-200);color:var(--amber-800);padding:8px 18px;border-radius:999px;font-weight:800;text-decoration:none;">
      تعديل <i class="fa fa-edit"></i>
    </a>
    <a href="{{ route('roles.index') }}" style="background:#fff;border:2px solid var(--line);color:var(--ink);padding:8px 18px;border-radius:999px;font-weight:800;text-decoration:none;">
      رجوع
    </a>
  </div>
</div>
@endsection
