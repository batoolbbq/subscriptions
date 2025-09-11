@extends('layouts.master')

@section('title', ' تعديل مشترك / منتفع')

@section('content')
    <style>
        :root {
            --brand: #F58220;
            --brand-600: #ff8f34;
            --ink: #111827;
            --muted: #6b7280;
            --border: #E5E7EB;
            --panel: #fff;
            --bg-1: #FFF7EE;
            --bg-2: #FCE8D6;
            --shadow: 0 10px 28px rgba(17, 24, 39, .07);
            --control-h: 38px;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--bg-1);
        }

        .page {
            min-height: 100dvh;
            display: flex;
            justify-content: center;
            /* يخليه في وسط العرض */
            align-items: flex-start;
            /* يخليه أعلى الصفحة */
            padding: 24px
        }

        .wrap {
            width: 100%;
            max-width: 500px;
            margin-inline: auto
        }

        .title-area {
            text-align: center;
            margin-bottom: 18px
        }

        .title-area h3 {
            margin: 0;
            font-weight: 800;
            color: var(--brand)
        }

        .card {
            background: var(--panel);
            border: 1.5px solid var(--border);
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden
        }

        .card-head {
            background: linear-gradient(135deg, #d95b00 0%, #F58220 35%, #FF8F34 70%, #ffb066 100%);
            padding: 14px;
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
            font-weight: 800;
            color: #fff
        }

        .card-head .icon {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            background: #ff8f34;
            color: #fff;
            display: grid;
            place-items: center;
            box-shadow: 0 8px 18px rgba(245, 130, 32, .28)
        }

        .card-body {
            padding: 20px
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: var(--muted);
            font-weight: 700
        }

        input,
        select {
            width: 100%;
            height: var(--control-h);
            border: 1px solid #d7dbe0;
            border-radius: 999px;
            padding: 0 14px;
            font-size: .9rem;
            background: #fff;
            outline: none;
            transition: border-color .2s, box-shadow .2s
        }

        input:focus,
        select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .18)
        }

        .input-icon {
            position: relative;
            width: 100%
        }

        .input-icon i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: .9rem
        }

        .input-icon input {
            padding-right: 38px !important
        }

        .btn {
            all: unset;
            display: inline-block;
            padding: 0 22px;
            height: 42px;
            line-height: 42px;
            border-radius: 999px;
            background: var(--brand);
            color: #fff;
            font-weight: 800;
            cursor: pointer;
            text-align: center;
            box-shadow: 0 10px 20px rgba(245, 130, 32, .25);
            transition: transform .15s, background .15s
        }

        .btn:hover {
            transform: translateY(-1px);
            background: var(--brand-600)
        }
    </style>

    <div class="page">
        <div class="wrap">
            <div class="title-area">
                <h3> تعديل مشترك / منتفع</h3>
            </div>


            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif
            <div class="card">
                <div class="card-head">
                    <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <span>أدخل رقم التأميني</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.searchEdit') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="insured_no">الرقم التأميني</label>
                            <div class="input-icon">
                                <i class="fa fa-id-card"></i>
                                <input type="text" name="regnumber" id="regnumber" value="{{ old('regnumber') }}"
                                    required>
                            </div>
                            @error('regnumber')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="actions" style="margin-top: 14px;">
                            <button type="submit" class="btn">بحث</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
