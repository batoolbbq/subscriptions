@extends('layouts.app')
@section('title',"إضافة بلدية")

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content ">
            <h4 class="box-title"><a href="{{ route('socialstatuses') }}">البلديات </a>/إضافة بلدية</h4>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box-content">
            <form method="POST" class="" action="">
                @csrf
                <div class="form-group">
                    <label for="inputName" class="control-label">المنطقة الصحية</label>

                    <select id="city_id"  class="form-control @error('city_id') is-invalid @enderror" name="city_id"   required autofocus>
<option  value="" selected >من فضلك اختر المنطقة الصحية </option>

@forelse ($city as $ci)
<option value="{{encrypt($ci->id)}}"> {{$ci->name}}</option>
@empty
<option value="">لا يوجد  مدن</option>
@endforelse

                    </select>
                    @error('city_id')
                        <span class="invalid-feedback has-error" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">البلدية</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" id="name" placeholder="بلدية" >
                    @error('name')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">إضافة</button>
                </div>
            </form>
        </div>
        <!-- /.box-content -->
    </div>
    <!-- /.col-xs-12 -->
</div>
@endsection