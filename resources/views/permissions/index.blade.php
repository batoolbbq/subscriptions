@extends('layouts.master')
@section('title','الصلاحيات')

@section('content')
<div class="container py-4" style="--ink:#111827;--line:#e5e7eb;--blue-50:#f3f6ff;--blue-200:#cfd8ff;--blue-700:#1d4ed8;--amber-50:#fff5e6;--amber-200:#ffd8a8;--amber-800:#92400e;--red-50:#fff1f1;--red-200:#ffc9c9;--red-700:#b42318;--green-50:#e9fbf2;--green-700:#10734a;--gray-50:#eff2f6;--gray-700:#374151;">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 style="color:var(--ink);font-weight:700;margin:0;">قائمة الصلاحيات</h3>
    <a href="{{ route('permissions.create') }}" style="background:var(--blue-50);border:2px solid var(--blue-200);color:var(--blue-700);padding:8px 14px;border-radius:12px;font-weight:800;text-decoration:none;">
      <i class="fa fa-plus"></i> إضافة صلاحية
    </a>
  </div>

  <div style="border:2px solid var(--line);border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);overflow:hidden;">
    <div class="table-responsive">
      <table style="width:100%;margin:0;color:var(--ink);border-collapse:collapse;">
        <thead>
          <tr>
            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;border-bottom:2px solid var(--line);padding:14px;">#</th>
            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;border-bottom:2px solid var(--line);padding:14px;">الاسم</th>
            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;border-bottom:2px solid var(--line);padding:14px;">المنصة</th>
            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;border-bottom:2px solid var(--line);padding:14px;width:240px;">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($permissions as $permission)
            <tr style="border-top:1px solid var(--line);">
              <td style="padding:14px;color:#64748b;font-weight:700;">{{ $loop->iteration }}</td>
              <td style="padding:14px;font-weight:600;">{{ $permission->name }}</td>
              <td style="padding:14px;">{{ $permission->guard_name }}</td>
              <td style="padding:14px;">
                <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                  <a href="{{ route('permissions.edit', $permission->id) }}" style="background:var(--amber-50);border:2px solid var(--amber-200);color:var(--amber-800);padding:7px 14px;border-radius:999px;font-weight:800;text-decoration:none;cursor:pointer;">
                    تعديل <i class="fa fa-edit"></i>
                  </a>
                  <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $permission->id }}"
                    style="background:var(--red-50);border:2px solid var(--red-200);color:var(--red-700);padding:7px 14px;border-radius:999px;font-weight:800;cursor:pointer;">
                    حذف <i class="fa fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>

            {{-- Modal الحذف --}}
            <div class="modal fade" id="deleteModal{{ $permission->id }}" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:14px;">
                  <div class="modal-header" style="background:var(--red-50);border-bottom:1px solid var(--red-200);">
                    <h5 class="modal-title" style="color:var(--red-700);font-weight:800;">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    هل أنت متأكد من حذف هذه الصلاحية؟
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="margin:0;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger">حذف</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

          @empty
            <tr>
              <td colspan="4" style="padding:20px;text-align:center;color:var(--gray-700);">لا توجد صلاحيات</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
