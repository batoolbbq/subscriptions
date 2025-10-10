<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --orange: #F58220;
            --brown: #8C5346;
            --gray: #f9fafb;
        }

        * {
            font-family: 'Tajawal', sans-serif;
        }

        body {
            background-color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .login-card {
            width: 550px;
            background: white;
            border-radius: 20px;
            padding: 50px 50px 45px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: 0.3s ease-in-out;
            border: 1px solid #eee;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 45px rgba(0, 0, 0, 0.1);
        }

        .login-logo {
            width: 300px;
            margin-bottom: 25px;
        }

        h4 {
            color: var(--brown);
            font-weight: 800;
            margin-bottom: 40px;
            letter-spacing: 0.5px;
        }

        .form-control {
            border-radius: 12px;
            padding: 14px 45px 14px 12px;
            border: 1px solid #ddd;
            font-size: 15px;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: var(--orange);
            box-shadow: 0 0 10px rgba(245, 130, 32, 0.2);
        }

        .input-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 1rem;
        }

        .btn-login {
            width: 100%;
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            padding: 14px;
            font-size: 17px;
            margin-top: 25px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #d86a15;
            transform: translateY(-2px);
        }

        .extra-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            margin-top: 15px;
        }

        .extra-links a {
            color: var(--orange);
            text-decoration: none;
            font-weight: 600;
        }

        .extra-links a:hover {
            color: var(--brown);
        }
    </style>
</head>

<body>
    <div class="login-card text-center">
        <img src="{{ asset('assets/images/pre-loader/logo05.svg') }}" class="login-logo" alt="Logo">
        <h4>نظام الاشتراكات</h4>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3 position-relative text-start">
                <input type="email" name="email" id="email" class="form-control" placeholder="البريد الإلكتروني"
                    value="{{ old('email') }}" required autofocus>
                <i class="fa-solid fa-envelope input-icon"></i>
                @error('email')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3 position-relative text-start">
                <input type="password" name="password" id="password" class="form-control" placeholder="كلمة المرور"
                    required>
                <i class="fa-solid fa-lock input-icon"></i>
                @error('password')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            <div class="extra-links">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">تذكرني</label>
                </div>
                {{-- <a href="{{ route('password.request') }}">نسيت كلمة المرور؟</a> --}}
            </div>

            <button type="submit" class="btn btn-login">تسجيل الدخول</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
