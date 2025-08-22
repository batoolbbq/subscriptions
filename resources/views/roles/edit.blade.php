@extends('layouts.master')
@section('css')
@section('title')
تعديل دور
@stop
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="mb-0">تعديل دور</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
                <li class="breadcrumb-item"><a href="" class="default-color">الرئيسية</a></li>
                <li class="breadcrumb-item active">تعديل دور</li>
            </ol>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection


@section('content')
<div class="bg-light p-4 rounded">
    <h1>تعديل دور</h1>


    <div class="container mt-4">

        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('roles.update', $role->id) }}">
            @method('patch')
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">الاسم</label>
                <input value="{{ $role->name }}"
                    type="text"
                    class="form-control"
                    name="name"
                    placeholder="الاسم" required>
            </div>

            <label for="permissions" class="form-label">Assign Permissions</label>

            <table id="datatable" class="table table-striped table-bordered p-0 table-hover">
                <thead>
                    <th scope="col" width="1%"><input type="checkbox" name="all_permission"></th>
                    <th scope="col" width="20%">الاسم</th>
                    <th scope="col" width="20%">الاسم</th>
                    <th scope="col" width="1%">المنصة</th>
                </thead>

                @foreach($permissions->sortBy('arabic_name') as $permission)
                <tr>
                    <td>
                        <input type="checkbox"
                            name="permission[{{ $permission->name }}]"
                            value="{{ $permission->name }}"
                            class='permission'
                            {{ in_array($permission->name, $rolePermissions) 
                                    ? 'checked'
                                    : '' }}>
                    </td>
                    <td>{{ $permission->arabic_name ? $permission->arabic_name : $permission->name }}</td>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->guard_name }}</td>
                </tr>
                @endforeach
            </table>

            <button type="submit" class="btn btn-primary">تحديث الدور</button>
            <a href="{{ route('roles.index') }}" class="btn btn-default">للخلف</a>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('[name="all_permission"]').on('click', function() {

            if ($(this).is(':checked')) {
                $.each($('.permission'), function() {
                    $(this).prop('checked', true);
                });
            } else {
                $.each($('.permission'), function() {
                    $(this).prop('checked', false);
                });
            }

        });
    });
</script>
@endsection
@section('js')
@endsection