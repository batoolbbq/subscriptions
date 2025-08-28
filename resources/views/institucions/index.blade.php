@extends('layouts.master')

@section('title', 'جهات العمل')

@section('content')
    <div class="container py-4"
        style="--ink:#111827;--line:#e5e7eb;
            --amber-50:#fff5e6;--amber-200:#ffd8a8;--amber-800:#92400E;
            --gray-50:#eff2f6;--gray-700:#374151;--brand:#F58220;">

        {{-- رسالة نجاح --}}
        @if (session('success'))
            <div class="alert alert-success" style="border:1.5px solid var(--green-700);border-radius:8px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- العنوان + الفلاتر + زر إضافة --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap:10px;">
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <a href="{{ route('institucions.index') }}"
                    style="padding:8px 16px;border-radius:999px;font-weight:700;
                  background:{{ request('status') == null ? '#F58220' : '#f3f4f6' }};
                  color:{{ request('status') == null ? '#fff' : '#374151' }};
                  border:1.5px solid {{ request('status') == null ? '#F58220' : '#d1d5db' }};
                  text-decoration:none;">
                    الكل
                </a>
                <a href="{{ route('institucions.index', ['status' => 'active']) }}"
                    style="padding:8px 16px;border-radius:999px;font-weight:700;
                  background:{{ request('status') == 'active' ? '#F58220' : '#f3f4f6' }};
                  color:{{ request('status') == 'active' ? '#fff' : '#374151' }};
                  border:1.5px solid {{ request('status') == 'active' ? '#F58220' : '#d1d5db' }};
                  text-decoration:none;">
                    مفعلة
                </a>
                <a href="{{ route('institucions.index', ['status' => 'inactive']) }}"
                    style="padding:8px 16px;border-radius:999px;font-weight:700;
                  background:{{ request('status') == 'inactive' ? '#F58220' : '#f3f4f6' }};
                  color:{{ request('status') == 'inactive' ? '#fff' : '#374151' }};
                  border:1.5px solid {{ request('status') == 'inactive' ? '#F58220' : '#d1d5db' }};
                  text-decoration:none;">
                    غير مفعلة
                </a>
            </div>

            <a href="{{ route('institucions.create') }}"
                style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;
                       background:var(--brand);color:#fff;padding:10px 18px;border-radius:999px;
                       font-weight:900;text-decoration:none;box-shadow:0 12px 26px rgba(245,130,32,.30);">
                <i class="fa fa-plus"></i> إضافة جهة عمل
            </a>
        </div>

        {{-- جدول البيانات --}}
        <div
            style="border:2px solid var(--line);border-radius:14px;
                box-shadow:0 6px 20px rgba(17,24,39,.05);overflow:hidden;">
            <div class="table-responsive">
                <table id="institucionsTable" class="table table-bordered table-hover table-custom" style="margin:0;">
                    <thead>
                        <tr>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">#
                            </th>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">
                                الاسم</th>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">
                                نوع جهة العمل</th>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">
                                الاشتراك</th>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">
                                الرقم التجاري</th>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">
                                الحالة</th>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">
                                الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ optional($row->workCategory)->name }}</td>
                                <td>{{ optional($row->subscription)->name ?: '—' }}</td>
                                <td>{{ $row->commercial_number ?: '—' }}</td>
                                <td>
                                    @if ($row->status)
                                        <span
                                            style="display:inline-block;background:#e9fbf2;color:#10734a;
                                                     border:1.5px solid #86efac;border-radius:999px;
                                                     padding:6px 12px;font-weight:800;">نشطة</span>
                                    @else
                                        <span
                                            style="display:inline-block;background:#eff2f6;color:#374151;
                                                     border:1.5px solid #d1d5db;border-radius:999px;
                                                     padding:6px 12px;font-weight:800;">موقوفة</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex;gap:.5rem;flex-wrap:nowrap;align-items:center;">
                                        <a href="{{ route('institucions.show', $row) }}"
                                            style="display:inline-flex;align-items:center;gap:6px;
                                                   background:#faf3ef;border:1.5px solid #f5cbaa;color:#92400E;
                                                   padding:6px 12px;border-radius:999px;font-weight:700;
                                                   font-size:14px;text-decoration:none;">
                                            <i class="fa fa-eye"></i> عرض
                                        </a>
                                        <a href="{{ route('institucions.edit', $row) }}"
                                            style="display:inline-flex;align-items:center;gap:6px;
                                                   background:#fff5e6;border:1.5px solid #F58220;color:#F58220;
                                                   padding:6px 12px;border-radius:999px;font-weight:700;
                                                   font-size:14px;text-decoration:none;">
                                            <i class="fa fa-edit"></i> تعديل
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <style>
        #institucionsTable {
            font-size: 15px;
        }

        #institucionsTable thead th {
            font-size: 16px;
        }

        .btn-light-brown {
            background: #faf3ef !important;
            border: 1.5px solid #f5cbaa !important;
            color: #92400E !important;
            border-radius: 999px !important;
            padding: 8px 16px !important;
            font-weight: 900 !important;
            margin: 2px !important;
            display: inline-flex !important;
            align-items: center;
            gap: 6px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 6px 10px;
            margin-right: 6px;
            font-size: 14px;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#institucionsTable').DataTable({
                language: {
                    url: "../Arabic.json",
                    search: "بحث:",
                    info: "إظهار _START_ إلى _END_ من أصل _TOTAL_ جهة",
                    infoEmpty: "لا توجد سجلات",
                    lengthMenu: "إظهار _MENU_ سجلات لكل صفحة",
                    paginate: {
                        first: "الأول",
                        last: "الأخير",
                        next: "التالي",
                        previous: "السابق"
                    }
                },
                lengthMenu: [5, 10, 25, 50],
                paging: true,
                searching: true,
                ordering: true,
                dom: '<"top d-flex justify-content-between"lfB>rtip',
                buttons: [{
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-copy"></i> نسخ',
                        className: 'btn-light-brown'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        className: 'btn-light-brown'
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fa fa-columns"></i> الأعمدة',
                        className: 'btn-light-brown'
                    }
                ]
            });
        });
    </script>
@endsection
