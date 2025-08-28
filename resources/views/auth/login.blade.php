<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- أيقونات FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-1: #FFF7EE;
            --bg-2: #FCE8D6;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background:
                radial-gradient(1100px 560px at 85% 12%, rgba(140, 83, 70, .18), transparent 60%),
                radial-gradient(900px 520px at 12% 88%, rgba(245, 130, 32, .22), transparent 60%),
                linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 42%, #ffd8b6 78%, #ffe4cc 100%);
        }

        .login-card {
            width: 500px;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
            text-align: center;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .login-logo {
            width: 360px;
            margin-bottom: 20px;
        }

        .login-card h4 {
            color: #505458;
            /* font-weight: bold; */

            margin-bottom:30px !important;
        }

        .form-label {
            /* font-weight: bold; */
            color: #505458;
            display: block;
            text-align: right;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
        }

        .form-control:focus {
            border-color: #F58220;
            box-shadow: 0 0 8px rgba(249, 116, 36, 0.3);
        }

        .input-group-text {
            background: #f3f3f3;
            border-radius: 8px;
            border: none;
        }

        .btn-primary {
            background-color: #f97424;
            border: none;
            padding: 12px;
            border-radius: 8px;
            transition: background 0.3s ease-in-out;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #d65d0d;
        }

        .extra-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .extra-links a {
            color: #f97424;
            text-decoration: none;
            font-weight: bold;
        }

        .extra-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <!-- اللوجو -->
        <img src="{{ asset('assets/images/pre-loader/logo05.svg') }}" alt="Logo" class="login-logo">

        <h4>نظام الاشتراكات </h4>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- البريد الإلكتروني -->
            <div class="mb-3 text-start position-relative">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <div class="position-relative">
                    <input type="email" id="email" name="email" class="form-control pe-5"
                        value="{{ old('email') }}" required autofocus>
                    <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">
                        <i class="fas fa-envelope"></i>
                    </span>
                </div>
                @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- كلمة المرور -->
            <div class="mb-3 text-start position-relative">
                <label for="password" class="form-label">كلمة المرور</label>
                <div class="position-relative">
                    <input type="password" id="password" name="password" class="form-control pe-5" required>
                    <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>


            <!-- تذكرني + نسيت كلمة المرور -->
            <div class="extra-links">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">تذكرني</label>
                </div>
                {{-- @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">نسيت كلمة المرور؟</a>
                @endif --}}
            </div>

            <!-- زر تسجيل الدخول -->
            <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
