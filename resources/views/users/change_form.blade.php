@extends('layouts.app')
@section('title',trans('users.changepass'))

@section('content')
<div class="row small-spacing">
    <div class="col-md-6">
        <div class="box-content">
            <form method="POST" class="" action="">
                @csrf
                <div class="form-group">
                    <label for="inputName" class="control-label">{{trans('users.current-password')}}</label>
                    <input type="password" name="current-password" class="form-control @error('current-password') is-invalid @enderror" value="{{ old('current-password') }}" id="current-password" placeholder="{{trans('users.current-password')}}" >
                    @error('current-password')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">{{trans('users.new-password')}}</label>
                    <input type="password" name="new-password" class="form-control @error('new-password') is-invalid @enderror" value="{{ old('new-password') }}" id="new-password" placeholder="{{trans('users.new-password')}}" >
                    @error('new-password')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">{{trans('users.new-password-confirm')}}</label>
                    <input type="password" name="new-password-confirm" class="form-control @error('new-password-confirm') is-invalid @enderror" value="{{ old('new-password-confirm') }}" id="new-password-confirm" placeholder="{{trans('users.new-password-confirm')}}" >
                    @error('new-password-confirm')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>       
                <div class="form-group">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{trans('users.passbtn')}}</button>
                </div>
            </form>
        </div>
        <!-- /.box-content -->
    </div>
    <!-- /.col-xs-12 -->
</div>
@endsection