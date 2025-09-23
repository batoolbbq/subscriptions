@extends('layouts.master')
@section('title', 'قائمة وكلاء التأمين')

@section('content')
    <div class="container py-4"
        style="--ink:#111827;--line:#e5e7eb;
            --amber-50:#fff5e6;--amber-200:#ffd8a8;--amber-800:#92400E;
            --gray-50:#eff2f6;--gray-700:#374151;--brand:#F58220;">

        {{-- العنوان --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 style="color:#92400E;font-weight:700;margin:0; margin-bottom:14px;">
                قائمة وكلاء التأمين
            </h3>
        </div>

        {{-- جدول البيانات --}}
        <div style="border:2px solid var(--line);
                box-shadow:0 6px 20px rgba(17,24,39,.05);overflow:hidden;">
            <div class="table-responsive">
                <table id="datatable1" class="table table-bordered table-hover table-custom" style="margin:0;">
                    <thead>
                        <tr>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">
                                الاسم</th>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">
                                رقم الهاتف</th>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">
                                العنوان</th>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">
                                البريد الالكتروني</th>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">
                                تاريخ التسجيل</th>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">
                                الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTables سيملأ البيانات --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <style>
        #datatable1 {
            font-size: 15px;
        }

        #datatable1 thead th {
            font-size: 16px;
        }

        /* ستايل الأزرار (نسخ - إكسل - الأعمدة) */
        .btn-light-brown {
            background: #faf3ef !important;
            border: 1.5px solid #f5cbaa !important;
            color: #92400E !important;
            /* border-radius: 999px !important; */
            padding: 8px 16px !important;
            font-weight: 900 !important;
            margin: 2px !important;
            margin-left: 14px !important;
            /* ← مارجن يسار */

            display: inline-flex !important;
            align-items: center;
            gap: 6px;
        }

        /* فلتر البحث */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            /* border-radius: 8px; */
            padding: 6px 10px;
            margin-right: 6px;
            font-size: 14px;
        }

        .dataTables_wrapper .dataTables_filter label {
            font-weight: 700;
            color: #374151;
        }

        /* توزيع الطول والبحث في نفس السطر */
        .dataTables_wrapper .top {
            margin-bottom: 10px;
            align-items: center;
        }

        /* محاذاة الباجينيت يسار مع إزاحة أكبر */
        .dataTables_wrapper .dataTables_paginate {
            float: left;
            text-align: left;
            margin-top: 10px;
            margin-left: 50px;
            /* إزاحة أكبر لليسار */
        }

        /* info يمين */
        .dataTables_wrapper .dataTables_info {
            float: right;
            margin-top: 14px;
            margin-right: 20px;
            font-weight: 600;
            color: #374151;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#datatable1').DataTable({
                language: {
                    url: "../Arabic.json",
                    search: "بحث:",
                    info: "إظهار _START_ إلى _END_ من أصل _TOTAL_ وكيل",
                    infoEmpty: "لا توجد سجلات",
                    lengthMenu: "إظهار _MENU_ سجلات لكل صفحة",
                    paginate: {
                        first: "الأول",
                        last: "الأخير",
                        next: "التالي",
                        previous: "السابق"
                    }
                },
                lengthMenu: [5, 10],
                bLengthChange: true,
                serverSide: false,
                paging: true,
                searching: true,
                ordering: true,
                ajax: '{!! route('InsuranceAgents-get-index') !!}',
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'phone_number'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'id',
                        render: function(data) {
                            return `
                        <div style="display:flex;gap:.5rem;flex-wrap:nowrap;align-items:center;">
                            <a href="/insurance-agents/${data}" 
                                style="display:inline-flex;align-items:center;gap:6px;
                                       background:#F3F4F6;border:1.5px solid #E5E7EB;
                                       padding:6px 12px;border-radius:999px;font-weight:700;
                                       font-size:14px;text-decoration:none;color:#4b5563">
                                <i class="fa fa-eye"></i> عرض
                            </a>
                            <a href="/insurance-agents/${data}/edit"
                                style="display:inline-flex;align-items:center;gap:6px;
                                       background:#fff7ee;border:1.5px solid #f58220;color:#f58220;
                                       padding:6px 12px;border-radius:999px;font-weight:700;
                                       font-size:14px;text-decoration:none;">
                                <i class="fa fa-edit"></i> تعديل
                            </a>
                        </div>`;
                        }
                    }
                ],
                dom: '<"top d-flex justify-content-between"lfB>rtip',
                 buttons: [
                //         extend: 'copyHtml5',
                //         text: '<i class="fa fa-copy"></i> نسخ',
                //         className: 'btn-light-brown'
                //     },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel"></i> تصدير Excel',
                        className: 'btn-light-brown'
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fa fa-columns"></i> الأعمدة',
                        className: 'btn-light-brown'
                    },
                ],
            });
        });
    </script>
@endsection
