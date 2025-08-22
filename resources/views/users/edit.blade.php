@extends('layouts.app')
@section('title',trans('users.edit'))
@section('content')
<style>
*,
*:before,
*:after {
    box-sizing: border-box;
    outline: none;
}

button {
    cursor: pointer;
}

.pdfobject-container {
    height: 30rem;
    border: 1rem solid rgba(0, 0, 0, 0.1);
}

.model__trigger {
    /* margin: 0 0.75rem; */
    padding: 0.625rem 1.25rem;
    border: none;
    border-radius: 0.25rem;
    box-shadow: 0 0.0625rem 0.1875rem rgba(0, 0, 0, 0.12), 0 0.0625rem 0.125rem rgba(0, 0, 0, 0.24);
    transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
    /* font-size: 0.875rem; */
    /* font-weight: 300; */
}

.model__trigger:hover {
    box-shadow: 0 0.875rem 1.75rem rgba(0, 0, 0, 0.25), 0 0.625rem 0.625rem rgba(0, 0, 0, 0.22);
}

.phif__modal {
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 0vh;
    background-color: transparent;
    overflow: hidden;
    transition: background-color 0.25s ease;
    z-index: 9999;
}

.phif__modal.open {
    position: fixed;
    width: 100%;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    transition: background-color 0.25s;
}

.phif__modal.open>.content-wrapper {
    transform: scale(1);
}

.phif__modal .content-wrapper {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    width: 40%;
    margin: 0;
    padding: 2.5rem;
    background-color: white;
    border-radius: 0.3125rem;
    box-shadow: 0 0 2.5rem rgba(0, 0, 0, 0.5);
    transform: scale(0);
    transition: transform 0.25s;
    transition-delay: 0.15s;
}

.phif__modal .content-wrapper .close {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border: none;
    background-color: transparent;
    font-size: 1.5rem;
    transition: 0.25s linear;
}

.phif__modal .content-wrapper .close:before,
.phif__modal .content-wrapper .close:after {
    position: absolute;
    content: "";
    width: 1.25rem;
    height: 0.125rem;
    background-color: black;
}

.phif__modal .content-wrapper .close:before {
    transform: rotate(-45deg);
}

.phif__modal .content-wrapper .close:after {
    transform: rotate(45deg);
}

.phif__modal .content-wrapper .close:hover {
    transform: rotate(360deg);
}

.phif__modal .content-wrapper .close:hover:before,
.phif__modal .content-wrapper .close:hover:after {
    background-color: tomato;
}

.phif__modal .content-wrapper .phif__modal-header {
    position: relative;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    margin: 0;
    padding: 0 0 1.25rem;
}

.phif__modal .content-wrapper .phif__modal-header h2 {
    font-size: 1.5rem;
    font-weight: bold;
}

.phif__modal .content-wrapper .__content {
    position: relative;
    display: flex;
    width: 100%;

}

.phif__modal .content-wrapper .__content p {
    font-size: 0.875rem;
    line-height: 1.75;
}

.phif__modal .content-wrapper .phif__modal-footer {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 100%;
    margin-top: 2em;
    padding: 1.875rem 0 0;
}

.phif__modal .content-wrapper .phif__modal-footer .action {
    position: relative;
    margin-left: 0.625rem;
    padding: .925rem 2rem;
    border: none;
    background-color: slategray;
    border-radius: 0.5rem;
    color: white;
    font-size: 1.5rem;
    font-weight: 300;
    overflow: hidden;
    z-index: 1
}

.phif__modal .content-wrapper .phif__modal-footer .action:before {
    position: absolute;
    content: "";
    top: 0;
    left: 0;
    width: 0%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.2);
    transition: width 0.25s;
    z-index: 0;
}

.phif__modal .content-wrapper .phif__modal-footer .action:first-child {
    background-color: #2ecc71;
}

.phif__modal .content-wrapper .phif__modal-footer .action:last-child {
    background-color: #e74c3c;
}

.phif__modal .content-wrapper .phif__modal-footer .action:hover:before {
    width: 100%;
}
</style>
<div class="row small-spacing">
    @can('add_user_permission')
    <div class="col-md-12">
        <div class="row box-content" style="display: flex; align-items: center;">
            <div class="col-md-6">
                <h4 class="box-title"><a href="{{ route('users') }}">{{trans('app.users')}}</a>/{{trans('users.edit')}}
                </h4>
            </div>

            <div class="col-md-6 ">
                <button id="btn-permission" type="button" style="display: block; margin-right:auto"
                    data-modal-trigger="trigger-1" class="model__trigger btn btn-success btn-md">
                    إضافة صلاحية
                </button>
                <!-- <button class="model__trigger" data-modal-trigger="trigger-1"><i class="fa fa-fire" aria-hidden="true"></i>	phif__Modal 1</button> -->
                <div class="phif__modal" data-modal="trigger-1">
                    <article class="content-wrapper">
                        <button class="close"></button>
                        <header class="phif__modal-header">
                            <h2>اضافة صلاحية جديدة</h2>
                        </header>
                        <div class="__content">
                            <form method="POST" id='addPermission' action="" style="width:100%; padding-top: 2em">
                                @csrf
                                <select id="js-example-basic-multiple" name="permissions[]" multiple="multiple"
                                    style="width: 100%;">
                                    @foreach ($permissions as $permission)
                                    <option value="{{$permission->name}}">
                                        {{$permission->name}}
                                    </option>
                                    @endforeach
                                </select>
                                <footer class="phif__modal-footer">
                                    <button class="action" type="submit">موافق</button>
                                    <button class="action btn__close">إلغاء</button>
                                </footer>
                            </form>
                        </div>
                    </article>
                </div>

            </div>
        </div>
    </div>
    @endcan
    <div class="col-md-6">
        <div class="box-content">
            <form method="POST" class="" action="">
                @csrf
                <div class="form-group">
                    <label for="inputName" class="control-label">{{trans('users.username')}}</label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ $user->username }}" id="username" placeholder="{{trans('users.username')}}" >
                    @error('username')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">{{trans('users.first_name')}}</label>
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ $user->first_name }}" id="first_name" placeholder="{{trans('users.first_name')}}" >
                    @error('first_name')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">{{trans('users.last_name')}}</label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ $user->last_name }}" id="last_name" placeholder="{{trans('users.last_name')}}" >
                    @error('last_name')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="inputEmail" class="control-label">{{trans('users.email')}}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $user->email }}" id="email" placeholder="{{trans('users.email')}}" >
                    @error('email')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">{{trans('users.phonenumber')}}</label>
                    <input type="text" name="phonenumber" class="form-control @error('phonenumber') is-invalid @enderror" value="{{ $user->phonenumber }}" id="phonenumber" placeholder="{{trans('users.phonenumber')}}" >
                    @error('phonenumber')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">{{trans('users.address')}}</label>
                    <select name="address" class="form-control @error('address') is-invalid @enderror  select2  wd-250"  data-placeholder="Choose one" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>
                        @forelse ($Cities as $City)
                        <option value="{{encrypt($City->id)}}" {{$City->id == $user->cities_id  ? 'selected' : ''}}>{{$City->name}}</option>
                        @endforeach

                    </select>
                    @error('address')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="inputEmail" class="control-label">{{trans('users.user_types')}}</label>
                    <select name="user_type_id" class="form-control @error('user_type_id') is-invalid @enderror  select2  wd-250"  data-placeholder="Choose one" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" required>
                        @foreach($user_types as $user_type)
                        <option value="{{encrypt($user_type->id)}}" {{$user_type->id == $user->user_type_id  ? 'selected' : ''}}>{{ $user_type->slug}}</option>
                        
                        @endforeach
                       
                    </select>

                        @error('user_type_id')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                    
                    <div class="help-block with-errors"></div>
                    <div class="mb-3">
                    <label for="role" class="form-label">الدور</label>
                    <select class="form-control" 
                        name="role" required>
                        <option value="">Select role</option>
                        @foreach($roles as $role)
                            <option {{ in_array($role->id, $user->roles->pluck('id')->toArray() )?"selected":"" }} value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('role'))
                        <span class="text-danger text-left">{{ $errors->first('role') }}</span>
                    @endif
                </div>
                <!--<div class="mb-3">-->
                <!--    <label for="role" class="form-label">المستشفى</label>-->
                <!--    <select class="form-control" -->
                <!--        name="hospital_id" required>-->
                <!--        <option value="">Select hospital</option>-->
                <!--        @foreach($hospitals as $hospital)-->
                <!--            <option value="{{ $hospital->id }}">{{ $hospital->hospital_name }}</option>-->
                <!--        @endforeach-->
                <!--    </select>-->
                <!--    @if ($errors->has('hospital_id'))-->
                <!--        <span class="text-danger text-left">{{ $errors->first('hospital_id') }}</span>-->
                <!--    @endif-->
                <!--</div>-->
                
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{trans('users.editbtn')}}</button>
                </div>
            </form>
        </div>
        <!-- /.box-content -->
    </div>
    <!-- /.col-xs-12 -->
    <script>
    const buttons = document.querySelectorAll('.model__trigger[data-modal-trigger]');

    for (let button of buttons) {
        modalEvent(button);
    }

    function modalEvent(button) {
        button.addEventListener('click', () => {
            const trigger = button.getAttribute('data-modal-trigger');
            // console.log('trigger', trigger)
            const modal = document.querySelector(`[data-modal=${trigger}]`);
            console.log('modal', modal)
            const contentWrapper = modal.querySelector('.content-wrapper');
            const close = modal.querySelector('.close');


            close.addEventListener('click', () => modal.classList.remove('open'));
            modal.addEventListener('click', () => modal.classList.remove('open'));
            contentWrapper.addEventListener('click', (e) => e.stopPropagation());

            modal.classList.toggle('open');
        });
    }

    $(document).ready(function() {


        $('#addPermission').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: "{!! route('add_user_permission', ['id' => $user->id]) !!}",
                type: 'POST',
                data: formData,
                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        timer: 20000,
                        text: "تمت عملية الإضافة بنجاح",
                        confirmButtonText: 'موافق',
                    })
                    $(this)[0].reset();
                    $('.open').hide();
                },
                error: function(error) {
                    Swal.fire({
                        icon: "error",
                        timer: 20000,
                        text: "فشل العملية ",
                        confirmButtonText: 'موافق'
                    })
                    $(this)[0].reset();
                    $('.open').hide();
                }
            });
        })
        // const image = document.getElementById('img').value;


        $('#js-example-basic-multiple').select2({
            placeholder: "اختر الصلاحية"
        });

    });
</script>
</div>
@endsection