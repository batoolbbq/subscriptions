@extends('layouts.master')

@section('title', 'نقل المشتركين')

@section('content')
<div class="container py-4">
    <h3 style="font-weight:800;color:#8C5346;">
        نقل المشتركين من: {{ $institucion->name }}
    </h3>
    <p style="color:#6b7280;">اختر الجهة التي تريد نقل المشتركين إليها:</p>

    <table id="institutions-table" class="table table-bordered">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>عدد المشتركين</th>
                <th>الحالة</th>
                <th>إجراء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($others as $other)
                <tr>
                    <td>{{ $other->name }}</td>
                    <td>{{ $other->customers_count }}</td>
                    <td>
                        @if($other->status)
                            <span class="badge bg-success">نشطة</span>
                        @else
                            <span class="badge bg-secondary">موقوفة</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('institucions.transferstore', $institucion) }}" method="POST"
                              onsubmit="return confirm('هل أنت متأكد من نقل المشتركين؟');">
                            @csrf
                            <input type="hidden" name="to_id" value="{{ $other->id }}">
                            <button type="submit" class="btn btn-warning btn-sm">نقل إلى هنا</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#institutions-table').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"
            }
        });
    });
</script>
@endpush
