@extends('layouts.master')

@section('title', 'الفئات الفرعية')

@section('content')
    <div class="container py-4"
        style="--ink:#111827;--line:#E5E7EB;
           --amber-50:#FFF7EE;--amber-200:#FFD8A8;--amber-800:#92400E;
           --brand:#F58220;--brand-600:#ff8f34;
           --red-50:#fff1f1;--red-200:#ffc9c9;--red-700:#b42318;
           --green-50:#e9fbf2;--green-700:#10734a;
           --gray-50:#eff2f6;--gray-700:#374151;">

        @if (session('success'))
            <div class="alert alert-success" style="border:1.5px solid var(--green-700);border-radius:8px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- العنوان + زر إضافة --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 style="color:#92400E;font-weight:700;margin:0;">الفئات الفرعية</h3>

            <a href="{{ route('beneficiaries-sup-categories.create') }}"
                style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;
                   background:var(--brand);color:#fff;padding:10px 18px;border-radius:999px;
                   font-weight:900;text-decoration:none;box-shadow:0 12px 26px rgba(245,130,32,.30);">
                <i class="fa fa-plus"></i> إضافة
            </a>
        </div>

        {{-- الجدول --}}
        <div class="table-responsive"
            style="border:1.5px solid var(--line);border-radius:14px;overflow:hidden;
               box-shadow:0 10px 28px rgba(0,0,0,.06);">
            <table style="width:100%;margin:0;color:var(--ink);border-collapse:collapse;">
                <thead>
                    <tr>
                        <th
                            style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                               color:#4b5563;font-weight:800;font-size:1.05rem;
                               border-bottom:1.5px solid var(--line);padding:14px;text-align:right;">
                            #</th>
                        <th
                            style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                               color:#4b5563;font-weight:800;font-size:1.05rem;
                               border-bottom:1.5px solid var(--line);padding:14px;text-align:right;">
                            الاسم</th>
                        <th
                            style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                               color:#4b5563;font-weight:800;font-size:1.05rem;
                               border-bottom:1.5px solid var(--line);padding:14px;text-align:right;">
                            النوع</th>
                        <th
                            style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                               color:#4b5563;font-weight:800;font-size:1.05rem;
                               border-bottom:1.5px solid var(--line);padding:14px;text-align:right;">
                            الكود</th>
                        <th
                            style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                               color:#4b5563;font-weight:800;font-size:1.05rem;
                               border-bottom:1.5px solid var(--line);padding:14px;text-align:right;">
                            الفئة الرئيسية</th>
                        <th
                            style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                               color:#4b5563;font-weight:800;font-size:1.05rem;
                               border-bottom:1.5px solid var(--line);padding:14px;text-align:right;">
                            الحالة</th>
                        <th
                            style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                               color:#4b5563;font-weight:800;font-size:1.05rem;
                               border-bottom:1.5px solid var(--line);padding:14px;text-align:right;width:260px;">
                            إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $i)
                        <tr style="border-top:1px solid var(--line);"
                            onmouseover="this.style.backgroundColor='var(--amber-50)'"
                            onmouseout="this.style.backgroundColor=''">
                            <td style="padding:14px;color:#64748b;font-weight:700;">{{ $i->id }}</td>
                            <td style="padding:14px;font-weight:700;color:#374151;">{{ $i->name }}</td>
                            <td style="padding:14px;">{{ $i->type }}</td>
                            <td style="padding:14px;">{{ $i->code }}</td>
                            <td style="padding:14px;">{{ $i->category?->name }}</td>
                            <td style="padding:14px;">
                                @if ($i->status)
                                    <span
                                        style="display:inline-block;background:var(--green-50);color:var(--green-700);
                                             border:1.5px solid #86efac;border-radius:999px;
                                             padding:6px 12px;font-weight:800;">مفعّلة</span>
                                @else
                                    <span
                                        style="display:inline-block;background:var(--gray-50);color:var(--gray-700);
                                             border:1.5px solid #d1d5db;border-radius:999px;
                                             padding:6px 12px;font-weight:800;">موقوفة</span>
                                @endif
                            </td>
                            <td style="padding:14px;">
                                <div style="display:flex;gap:.5rem;flex-wrap:nowrap;align-items:center;">
                                    {{-- زر عرض --}}
                                    <a href="{{ route('beneficiaries-sup-categories.show', $i->id) }}"
                                        style="display:inline-flex;align-items:center;gap:6px;
                                           background:#F3F4F6;border:1.5px solid #E5E7EB;color:#374151;
                                           padding:6px 12px;border-radius:999px;font-weight:700;
                                           font-size:14px;text-decoration:none;">
                                        <i class="fa fa-eye"></i> عرض
                                    </a>

                                    {{-- زر تعديل --}}
                                    <a href="{{ route('beneficiaries-sup-categories.edit', $i->id) }}"
                                        style="display:inline-flex;align-items:center;gap:6px;
                                           background:#fff7ee;border:1.5px solid #f58220;color:#f58220;
                                           padding:6px 12px;border-radius:999px;font-weight:700;
                                           font-size:14px;text-decoration:none;">
                                        <i class="fa fa-edit"></i> تعديل
                                    </a>

                                    {{-- زر حذف --}}
                                    <form action="{{ route('beneficiaries-sup-categories.destroy', $i->id) }}"
                                        method="POST" onsubmit="return confirm('تأكيد الحذف؟');" style="margin:0;">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            style="display:inline-flex;align-items:center;gap:6px;
                                               background:var(--red-50);border:1.5px solid var(--red-200);
                                               color:var(--red-700);padding:6px 12px;border-radius:999px;
                                               font-weight:700;font-size:14px;cursor:pointer;">
                                            <i class="fa fa-trash"></i> حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding:20px;text-align:center;color:var(--gray-700);">
                                لا توجد بيانات
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- الباجينيت --}}
        <div style="padding:14px;">
            {{-- {{ $items->withQueryString()->links() }} --}}
        </div>
    </div>
@endsection

@section('js')
    <style>
        /* ستايل الباجينيت */
        .pagination {
            margin-top: 15px;
        }

        .pagination .page-item .page-link {
            padding: .6rem 1rem;
            color: #4b5563;
            border: 1px solid #f5cbaa;
            background: #fff;
            font-weight: 600;
            margin: 0 2px;
            border-radius: 6px;
        }

        .pagination .page-item.active .page-link {
            background: #FCE8D6;
            border-color: #F58220;
            color: #92400E;
            font-weight: 700;
        }

        .pagination .page-item .page-link:hover {
            background: #FFD8A8;
            border-color: #F58220;
            color: #92400E;
        }
    </style>
@endsection
