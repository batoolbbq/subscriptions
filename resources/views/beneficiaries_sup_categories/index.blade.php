@extends('layouts.master')

@section('title','الفئات الفرعية')

@section('content')
<div class="container py-4" style="--ink:#111827;--line:#e5e7eb;--blue-50:#f3f6ff;--blue-200:#cfd8ff;--blue-700:#1d4ed8;--amber-50:#fff5e6;--amber-200:#ffd8a8;--amber-800:#92400e;--red-50:#fff1f1;--red-200:#ffc9c9;--red-700:#b42318;--green-50:#e9fbf2;--green-700:#10734a;--gray-50:#eff2f6;--gray-700:#374151;">

  @if (session('success'))
    <div class="alert alert-success" style="border:1px solid var(--green-700);border-radius:8px;">
      {{ session('success') }}
    </div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 style="color:var(--ink);font-weight:700;margin:0;">الفئات الفرعية</h3>
    <a href="{{ route('beneficiaries-sup-categories.create') }}" style="background:var(--blue-50);border:2px solid var(--blue-200);color:var(--blue-700);padding:8px 14px;border-radius:12px;font-weight:800;text-decoration:none;">
      <i class="fa fa-plus"></i> إضافة
    </a>
  </div>

  <div style="border:2px solid var(--line);border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);overflow:hidden;">
    <div class="table-responsive">
      <table style="width:100%;margin:0;color:var(--ink);border-collapse:collapse;">
        <thead>
          <tr>
            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;font-size:1.05rem;border-bottom:2px solid var(--line);padding:14px;">#</th>
            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;font-size:1.05rem;border-bottom:2px solid var(--line);padding:14px;">الاسم</th>
            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;font-size:1.05rem;border-bottom:2px solid var(--line);padding:14px;">النوع</th>
            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;font-size:1.05rem;border-bottom:2px solid var(--line);padding:14px;">الكود</th>
            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;font-size:1.05rem;border-bottom:2px solid var(--line);padding:14px;">الفئة الرئيسية</th>
            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;font-size:1.05rem;border-bottom:2px solid var(--line);padding:14px;">الحالة</th>
            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;font-size:1.05rem;border-bottom:2px solid var(--line);padding:14px;width:260px;">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $i)
            <tr style="border-top:1px solid var(--line);">
              <td style="padding:14px;color:#64748b;font-weight:700;">{{ $i->id }}</td>
              <td style="padding:14px;font-weight:600;">{{ $i->name }}</td>
              <td style="padding:14px;">{{ $i->type }}</td>
              <td style="padding:14px;">{{ $i->code }}</td>
              <td style="padding:14px;">{{ $i->category?->name }}</td>
              <td style="padding:14px;">
                @if ($i->status)
                  <span style="display:inline-block;background:var(--green-50);color:var(--green-700);border:2px solid #a7f3d0;border-radius:6px;padding:6px 12px;font-weight:800;">مفعّلة</span>
                @else
                  <span style="display:inline-block;background:var(--gray-50);color:var(--gray-700);border:2px solid #d1d5db;border-radius:6px;padding:6px 12px;font-weight:800;">موقوفة</span>
                @endif
              </td>
              <td style="padding:14px;">
                <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                  <a href="{{ route('beneficiaries-sup-categories.show', $i->id) }}" style="background:var(--blue-50);border:2px solid var(--blue-200);color:#0f172a;padding:7px 14px;border-radius:999px;font-weight:800;text-decoration:none;cursor:pointer;">
                    عرض <i class="fa fa-eye"></i>
                  </a>
                  <a href="{{ route('beneficiaries-sup-categories.edit', $i->id) }}" style="background:var(--amber-50);border:2px solid var(--amber-200);color:var(--amber-800);padding:7px 14px;border-radius:999px;font-weight:800;text-decoration:none;cursor:pointer;">
                    تعديل <i class="fa fa-edit"></i>
                  </a>
                  <form action="{{ route('beneficiaries-sup-categories.destroy', $i->id) }}" method="POST" onsubmit="return confirm('تأكيد الحذف؟');" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:var(--red-50);border:2px solid var(--red-200);color:var(--red-700);padding:7px 14px;border-radius:999px;font-weight:800;cursor:pointer;">
                      حذف <i class="fa fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="padding:20px;text-align:center;color:var(--gray-700);">لا توجد بيانات</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="padding:14px;border-top:1px solid var(--line);background:white;">
      {{-- {{ $items->withQueryString()->links() }} --}}
    </div>
  </div>
</div>
@endsection
