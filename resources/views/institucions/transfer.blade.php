@extends('layouts.master')

@section('title', 'نقل المشتركين')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
    <div class="container py-5">

        <!-- العنوان -->
        <div class="mb-4 text-center">
            <h2 style="font-weight:800;color:#333;">
                <i class="fa-solid fa-people-arrows"></i>
                نقل المشتركين من: <span style="color:#a9adb1;">{{ $institucion->name }}</span>
            </h2>
            <p class="text-muted">اختر الجهة التي تريد نقل المشتركين إليها:</p>
        </div>

        <!-- الجدول -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <table id="institutions-table" class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
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
                                <td class="fw-bold">{{ $other->name }}</td>
                                <td>{{ $other->customers_count }}</td>
                                <td>
                                    {{ $other->status ? 'نشطة' : 'موقوفة' }}
                                </td>
                                <td>
                                    <form action="{{ route('institucions.transferstore', $institucion) }}" method="POST"
                                        class="transfer-form">
                                        @csrf
                                        <input type="hidden" name="to_id" value="{{ $other->id }}">
                                        <button type="button" class="btn btn-sm"
                                            style="background:#e9ecef; color:#333; border:1px solid #ced4da; font-weight:600;">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#institutions-table').DataTable({
                language: {
                    url: "../Arabic.json",
                    search: "بحث:" // نص البحث بالعربي
                },
                dom: '<"d-flex justify-content-between mb-3"f l>tip',
                // f = filter (البحث) و l = length (عدد الصفوف)
                // justify-content-between يخلي البحث على اليمين وعدد الصفوف على اليسار
                pageLength: 5,
                lengthChange: true,
                ordering: true,
            });

            // SweetAlert تأكيد
            $('.transfer-form button').on('click', function(e) {
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "سيتم نقل جميع المشتركين إلى الجهة المحددة",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#F58220',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'نعم، انقلهم',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
