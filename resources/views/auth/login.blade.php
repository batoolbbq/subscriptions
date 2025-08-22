<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- أيقونات FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #ffffff, #ffe9dd);
            /* أبيض وبرتقالي فاتح */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 400px;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .login-card h3 {
            color: #f97424;
            font-weight: bold;
            text-align: center;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
        }

        .form-control:focus {
            border-color: #f97424;
            box-shadow: 0 0 8px rgba(249, 116, 36, 0.3);
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

        .form-check-label {
            font-size: 14px;
        }

        .text-links {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }

        .text-links a {
            color: #f97424;
            text-decoration: none;
            font-weight: bold;
        }

        .text-links a:hover {
            text-decoration: underline;
        }

        .input-group-text {
            background: #f3f3f3;
            border-radius: 8px;
            border: none;
        }

        .register-text {
            text-align: center;
            margin-top: 15px;
            font-size: 15px;
        }

        .register-text a {
            color: #f97424;
            font-weight: bold;
            text-decoration: none;
        }

        .register-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body dir="rtl">

    <div class="login-card">
        <h3>تسجيل الدخول</h3>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- البريد الإلكتروني -->
            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}"
                        required autofocus>
                </div>
                @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- كلمة المرور -->
            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- تذكرني -->
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">تذكرني</label>
            </div>

            <!-- روابط إضافية -->
            <div class="text-links mb-3">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">نسيت كلمة المرور؟</a>
                @endif
            </div>

            <!-- زر تسجيل الدخول -->
            <button type="submit" class="btn btn-primary">تسجيل الدخول</button>


        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
