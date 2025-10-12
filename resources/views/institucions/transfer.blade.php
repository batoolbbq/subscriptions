@extends('layouts.master')

@section('title', 'نقل المشتركين')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --brand: #F58220;
            --brand-light: #ffb066;
            --brown: #8C5346;
            --muted: #6b7280;
            --bg: #f9fafb;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--bg);
        }

        .page-title {
            font-weight: 800;
            color: var(--brown);
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        table thead {
            background: linear-gradient(135deg, #d95b00 0%, var(--brand) 40%, var(--brand-light) 100%);
            color: #fff;
        }

        table.dataTable tbody tr:hover {
            background-color: #fff7f2 !important;
        }

        .btn-transfer {
            background: var(--brand);
            color: #fff;
            border-radius: 999px;
            padding: 6px 14px;
            font-weight: 700;
            transition: 0.3s;
            border: none;
        }

        .btn-transfer:hover {
            background: #d95b00;
            transform: translateY(-1px);
        }

        .swal2-confirm {
            background: var(--brand) !important;
            border-radius: 999px !important;
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">

        <!-- العنوان -->
        <div class="text-center mb-4">
            <h2 class="page-title mb-1">
                <i class="fa-solid fa-people-arrows"></i>
                نقل المشتركين من:
                <span style="color:#a9adb1;">{{ $institucion->name }}</span>
            </h2>
            <p style="color:var(--muted);font-size:.95rem;">اختر الجهة التي تريد نقل المشتركين إليها:</p>
        </div>

        <!-- الجدول -->
        <div class="card">
            <div class="card-body p-4">
                <table id="institutions-table" class="table table-striped table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>عدد المشتركين</th>
                            <th>الحالة</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($others as $other)
                            <tr>
                                <td class="fw-bold" style="color:var(--brown);">{{ $other->name }}</td>
                                <td>{{ $other->customers_count }}</td>
                                <td>
                                    <span style="font-weight:700; color:{{ $other->status ? '#10734a' : '#b42318' }};">
                                        {{ $other->status ? 'نشطة' : 'موقوفة' }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('institucions.transferstore', $institucion) }}" method="POST"
                                        class="transfer-form">
                                        @csrf
                                        <input type="hidden" name="to_id" value="{{ $other->id }}">
                                        <button type="button" class="btn-transfer btn-sm">
                                            <i class="fa-solid fa-share"></i> نقل إلى هنا
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 🔸 تهيئة الجدول
            $('#institutions-table').DataTable({
                language: {
                    url: "../Arabic.json"
                },
                pageLength: 5,
                lengthChange: true,
                ordering: true,
                dom: '<"d-flex justify-content-between align-items-center mb-3"f l>tip'
            });

            // 🔸 تأكيد النقل عبر SweetAlert
            $(document).on('click', '.transfer-form button', function(e) {
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    html: "<b>سيتم نقل جميع المشتركين</b> إلى الجهة المحددة.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، انقلهم',
                    cancelButtonText: 'إلغاء',
                    confirmButtonColor: '#F58220',
                    cancelButtonColor: '#6c757d',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
