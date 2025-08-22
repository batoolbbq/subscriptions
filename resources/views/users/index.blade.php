@extends('layouts.master')
@section('title', 'إدارة المستخدمين')

@section('content')
<div class="container py-4" style="font-family: sans-serif;">

    {{-- العنوان + زر إضافة مستخدم --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <div>
            <h3 style="margin:0;font-weight:800;color:#111827;">إدارة المستخدمين</h3>
            <div style="color:#6b7280;font-size:14px;">عرض وتحرير بيانات جميع المستخدمين.</div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            <a href="{{ route('users.create') }}"
               style="background:#f3f6ff;border:2px solid #cfd8ff;color:#1d4ed8;padding:8px 14px;border-radius:12px;font-weight:800;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="fa fa-plus"></i> إضافة مستخدم
            </a>
        
        </div>
    </div>

    {{-- البطاقة --}}
    <div style="border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);overflow:hidden;">
        <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid #D0D5DD;padding:10px 14px;display:flex;align-items:center;gap:8px;">
            <span style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid #FFD8A8;color:#92400E;font-weight:800;">
                <i class="fa fa-users"></i>
            </span>
            <h6 style="margin:0;font-weight:800;color:#374151;">قائمة المستخدمين</h6>
        </div>
        <div style="padding:16px;">

            <div class="table-responsive" data-pattern="priority-columns">
                <table id="dataTable" class="table table-bordered table-hover js-basic-example dataTable table-custom"
                    style="cursor: pointer; margin:0;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <script>
                            $(document).ready(function() {
                                $('#dataTable').dataTable({
                                    language: {
                                        url: '../../Arabic.json'
                                    },
                                    lengthMenu: [20, 50, 100],
                                    bLengthChange: true,
                                    processing: true,
                                    serverSide: true,
                                    ajax: '{{ route('users.users') }}',
                                    columns: [
                                        { data: 'id' },
                                        { data: 'first_name' },
                                        { data: 'email' },
                                        { data: 'role' },
                                        { data: 'created_at' },
                                        { data: 'action', orderable: false, searchable: false },
                                    ],
                                    dom: 'Blfrtip',
                                    buttons: [
                                        { extend: 'copyHtml5', exportOptions: { columns: [':visible'] }, text: 'نسخ' },
                                        { extend: 'excelHtml5', exportOptions: { columns: ':visible' }, text: 'تصدير Excel' },
                                        { extend: 'colvis', text: 'إظهار/إخفاء الأعمدة' }
                                    ],
                                });
                            });
                        </script>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
