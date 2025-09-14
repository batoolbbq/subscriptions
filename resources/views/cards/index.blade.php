@extends('layouts.master')
@section('title', 'إصدار بطاقة')

@section('content')
    <style>
        :root {
            --brand: #F58220;
            --brand-600: #ff8f34;
            --brand-700: #d95b00;
            --ink: #1F2328;
            --muted: #6b7280;
            --border: #E5E7EB;
            --radius: 16px;
            --shadow: 0 10px 24px rgba(0, 0, 0, .06);
        }

        .cardx {
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 24px;
            margin: auto;
            max-width: 600px;
        }

        .cardx h3 {
            margin: 0 0 1rem 0;
            font-weight: 800;
            color: var(--brand-700);
            text-align: center;
        }

        .form-label {
            font-weight: 700;
            color: var(--ink);
            margin-bottom: .35rem;
        }

        .form-control {
            border-radius: 10px;
            padding: .65rem .9rem;
            border: 1.5px solid var(--border);
            font-size: .95rem;
        }

        .btn-brand {
            background: var(--brand);
            color: #fff;
            font-weight: 700;
            border-radius: 999px;
            padding: .6rem 1.4rem;
            border: none;
            box-shadow: 0 8px 18px rgba(245, 130, 32, .25);
            transition: background .2s;
        }

        .btn-brand:hover {
            background: var(--brand-600);
        }
    </style>

    <div class="cardx">
        <h3>إصدار بطاقة</h3>

        <form method="POST" action="">
            @csrf

            <div class="mb-3">
                <label for="regnumber" class="form-label">رقم التسجيل</label>
                <input type="text" id="regnumber" name="regnumber" minlength="13" maxlength="13"
                    onkeypress="return onlyNumberKey(event)" class="form-control @error('regnumber') is-invalid @enderror"
                    value="{{ old('regnumber') }}">
                @error('regnumber')
                    <span class="invalid-feedback" style="color: red" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn-brand">بحث</button>
            </div>
        </form>
    </div>
@endsection
