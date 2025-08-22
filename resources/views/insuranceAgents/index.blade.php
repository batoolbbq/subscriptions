@extends('layouts.master')
@section('title', 'قائمة وكلاء التأمين')

@section('content')
<div class="container py-4" style="--ink:#111827;--line:#e5e7eb;--blue-50:#f3f6ff;--blue-200:#cfd8ff;--blue-700:#1d4ed8;--amber-50:#fff5e6;--amber-200:#ffd8a8;--amber-800:#92400e;--red-50:#fff1f1;--red-200:#ffc9c9;--red-700:#b42318;--gray-50:#eff2f6;--gray-700:#374151;">

    {{-- العنوان وزر الإضافة --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 style="color:var(--ink);font-weight:700;margin:0;">قائمة وكلاء التأمين</h3>
       
    </div>

    {{-- جدول البيانات --}}
    <div style="border:2px solid var(--line);border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);overflow:hidden;">
        <div class="table-responsive">
            <table id="datatable1" class="table table-bordered table-hover table-custom" style="margin:0;">
                <thead>
                    <tr>
                        <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">الاسم</th>
                        <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">رقم الهاتف</th>
                        <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">العنوان</th>
                        <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">البريد الالكتروني</th>
                        <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">تاريخ التسجيل</th>
                        <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);font-weight:800;color:#4b5563;">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- DataTables JS will fill this --}}
                    <script>
                        $(document).ready(function() {
                            $('#datatable1').dataTable({
                                language: {
                                    url: "../Arabic.json"
                                },
                                lengthMenu: [5, 10],
                                bLengthChange: true,
                                serverSide: false,
                                paging: true,
                                searching: true,
                                ordering: true,
                                ajax: '{!! route('InsuranceAgents-get-index') !!}',
                                columns: [
                                    { data: 'name' },
                                    { data: 'phone_number' },
                                    { data: 'address' },
                                    { data: 'email' },
                                    { data: 'created_at' },
                                    { data: 'action' }
                                ],
                                dom: 'Blfrtip',
                                buttons: [
                                    { extend: 'copyHtml5', exportOptions: { columns: [':visible'] }, text: 'نسخ' },
                                    { extend: 'excelHtml5', exportOptions: { columns: ':visible' }, text: 'تصدير Excel' },
                                    { extend: 'colvis', text: 'الأعمدة' },
                                ],
                            });
                        });
                    </script>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
