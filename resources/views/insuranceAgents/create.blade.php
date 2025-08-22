<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>بوابة تسجيل وكلاء التأمين</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- مسار جلب البلديات -->
    <meta name="municipals-url-template" content="{{ route('municipals.byCity', ['city' => 'CITY_ID__PLACEHOLDER']) }}">

    <!-- Google Fonts: Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <!-- أيقونات -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --brand: #F58220;
            --ink: #111827;
            --muted: #6b7280;
            --muted-2: #9ca3af;
            --border: #E5E7EB;
            --panel: #ffffff;
            --bg: #f8fafc;
            --soft-1: #FFF7EE;
            --soft-2: #FCE8D6;
            --shadow: 0 10px 28px rgba(17, 24, 39, .07);
            --radius: 14px;
            --radius-sm: 10px;
            --focus: 0 0 0 4px rgba(245, 130, 32, .16);
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            height: 100%
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: 'Tajawal', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
            line-height: 1.65;
        }

        .page {
            min-height: 100dvh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .form-wrap {
            width: 100%;
            max-width: 780px;
            margin-inline: auto
        }

        .title-area {
            text-align: center;
            margin-bottom: 18px;
        }

        .title-area h3 {
            margin: 0 0 .25rem;
            font-weight: 800;
            letter-spacing: .2px;
            color: #ac584b;
            font-size: 1.55rem;
        }

        .title-area p {
            margin: 0;
            color: var(--muted);
            font-size: .98rem;
        }

        .cardy {
            background: var(--panel);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 18px;
        }

        .cardy-header {
            background: linear-gradient(180deg, var(--soft-1), var(--soft-2));
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 800;
            color: var(--ink);
        }

        .cardy-header .icon {
            background: var(--brand);
            color: #fff;
            width: 30px;
            height: 30px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            font-size: .9rem;
            box-shadow: 0 6px 14px rgba(245, 130, 32, .28);
        }

        .cardy-body {
            padding: 18px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        @media (min-width:720px) {
            .grid-2 {
                grid-template-columns: 1fr 1fr;
            }
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: var(--muted);
            font-size: .95rem;
            font-weight: 700;
        }

        .form-control,
        .form-select,
        textarea {
            width: 100%;
            border: 1px solid #d7dbe0;
            border-radius: var(--radius-sm);
            padding: 11px 12px;
            font-size: 1rem;
            background: #fff;
            transition: border-color .2s, box-shadow .2s, background .2s;
            outline: none;
        }

        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            border-color: var(--brand);
            box-shadow: var(--focus);
        }

        /* ملف مرفوع – اسم الملف */
        .file-name {
            margin-top: 6px;
            font-size: .85rem;
            color: #7b7f86;
            display: none;
        }

        .help {
            color: #9aa0a6;
            font-size: .85rem;
            margin-top: 6px;
            display: block;
        }

        #docs-card {
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 16px;
            background: #fff;
            overflow: hidden;
        }

        .btn {
            all: unset;
            display: inline-block;
            cursor: pointer;
            padding: 12px 22px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 1rem;
            text-align: center;
            transition: transform .18s ease, background .18s ease, box-shadow .18s ease, color .18s ease;
        }

        .btn-primary {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 8px 18px rgba(245, 130, 32, .25);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            background: #ff8f34;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .text-center {
            text-align: center;
        }

        /* تنبيهات */
        .alert {
            padding: 12px 14px;
            border-radius: 10px;
            font-weight: 700;
            margin-bottom: 1rem;
            text-align: center;
            border: 1.5px solid;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .03);
        }

        .alert.ok {
            background: #e9fbf2;
            border-color: #86efac;
            color: #10734a;
        }

        .alert.err {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .error-text {
            color: #b91c1c;
            font-size: .85rem;
            margin-top: 6px;
            font-weight: 700;
        }

        .skeleton {
            display: none;
            height: 40px;
            border: 1px dashed var(--border);
            border-radius: var(--radius-sm);
            background: #f3f6fa;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            color: var(--muted-2);
        }

        .skeleton.show {
            display: flex;
        }
    </style>
</head>

<body>

    <main class="page">
        <div class="form-wrap">
            <div class="title-area">
                <h3>بوابة تسجيل وكلاء التأمين</h3>
                <p>مرحباً بك</p>
            </div>

            @if (session('success'))
                <div class="alert ok">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert err">يوجد أخطاء في الإدخال، يرجى المراجعة.</div>
            @endif

            <div class="cardy">
                <div class="cardy-header">
                    <span class="icon"><i class="fa fa-id-card"></i></span>
                    <span>معلومات عامة</span>
                </div>

                <form id="signupSupplier" class="cardy-body" method="POST" enctype="multipart/form-data"
                    action="{{ route('insuranceAgents.store') }}">
                    @csrf

                    <div class="grid grid-2">
                        <div class="form-group">
                            <label for="name">الاسم رباعي</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text"
                                id="name" name="name" maxlength="50" placeholder="الاسم رباعي"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone_number">رقم الهاتف</label>
                            <input class="form-control @error('phone_number') is-invalid @enderror" type="text"
                                id="phone_number" name="phone_number" maxlength="9" placeholder="مثال: 9xxxxxxxx"
                                value="{{ old('phone_number') }}" onkeypress="return onlyNumberKey(event)" required>
                            <small class="help">يرجى كتابة رقم الهاتف (91/92/94) بتسعة أرقام.</small>
                            @error('phone_number')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">العنوان</label>
                            <input class="form-control @error('address') is-invalid @enderror" type="text"
                                id="address" name="address" maxlength="150" placeholder="العنوان"
                                value="{{ old('address') }}" required>
                            @error('address')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">البريد الإلكتروني</label>
                            <input class="form-control @error('email') is-invalid @enderror" type="email"
                                id="email" name="email" maxlength="50" placeholder="example@mail.com"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="city">المنطقة الصحية</label>
                            <select id="city" class="form-select city @error('cities_id') is-invalid @enderror"
                                name="cities_id" required>
                                <option value="" selected disabled>اختر المنطقة الصحية</option>
                                @foreach ($city as $item)
                                    <option value="{{ $item->id }}" @selected(old('cities_id') == $item->id)>
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('cities_id')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="Municipal">البلدية</label>
                            <select id="Municipal"
                                class="form-select Municipal @error('municipals_id') is-invalid @enderror"
                                name="municipals_id" disabled required>
                                <option value="" selected>اختر البلدية</option>
                            </select>
                            <div id="municipalSkeleton" class="skeleton">جاري التحميل…</div>
                            @error('municipals_id')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:14px;">
                        <label for="description">وصف لحجم ومساحة المكان</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="4" placeholder="مثال: مكتب بمساحة 40م²، به قاعة استقبال وغرفة أرشيف..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- الوثائق -->
                    <div id="docs-card" style="margin-top:16px;">
                        <div class="cardy-header" style="justify-content:flex-start;">
                            <span class="icon"><i class="fa fa-file"></i></span>
                            <span>الوثائق</span>
                        </div>
                        <div class="cardy-body">
                            <div class="grid grid-2">
                                <div class="form-group">
                                    <label for="Birth_creature">شهادة ميلاد</label>
                                    <input class="form-control @error('Birth_creature') is-invalid @enderror"
                                        type="file" id="Birth_creature" name="Birth_creature"
                                        accept=".jpg,.jpeg,.png" required>
                                    <div class="file-name" data-file="Birth_creature"></div>
                                    @error('Birth_creature')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="qualification">المؤهل العلمي</label>
                                    <input class="form-control @error('qualification') is-invalid @enderror"
                                        type="file" id="qualification" name="qualification"
                                        accept=".jpg,.jpeg,.png" required>
                                    <div class="file-name" data-file="qualification"></div>
                                    @error('qualification')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="image">صورة للمكان</label>
                                    <input class="form-control @error('image') is-invalid @enderror" type="file"
                                        id="image" name="image" accept=".jpg,.jpeg,.png" required>
                                    <div class="file-name" data-file="image"></div>
                                    @error('image')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="Insurance_certificate">شهادة التأمين</label>
                                    <input class="form-control @error('Insurance_certificate') is-invalid @enderror"
                                        type="file" id="Insurance_certificate" name="Insurance_certificate"
                                        accept=".jpg,.jpeg,.png" required>
                                    <div class="file-name" data-file="Insurance_certificate"></div>
                                    @error('Insurance_certificate')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="text-center" style="margin-top:22px;">
                        <button type="submit" class="btn btn-primary" style="min-width:52%;">تسجيل</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function onlyNumberKey(evt) {
            const c = evt.which ? evt.which : evt.keyCode;
            if (c > 31 && (c < 48 || c > 57)) return false;
            return true;
        }

        // إظهار اسم الملف المختار تحت كل حقل
        document.addEventListener('change', function(e) {
            if (e.target.type === 'file') {
                const id = e.target.id;
                const box = document.querySelector('.file-name[data-file="' + id + '"]');
                if (box) {
                    const name = e.target.files?.[0]?.name || '';
                    box.textContent = name ? ('الملف: ' + name) : '';
                    box.style.display = name ? 'block' : 'none';
                }
            }
        });

        // تحميل البلديات عند تغيير المنطقة الصحية
        document.addEventListener('change', function(e) {
            if (!e.target.classList.contains('city')) return;

            const citySelect = e.target;
            const municipalSelect = document.getElementById('Municipal');
            const skeleton = document.getElementById('municipalSkeleton');

            municipalSelect.disabled = true;
            municipalSelect.innerHTML = '<option value="" selected>جاري التحميل...</option>';
            skeleton?.classList.add('show');

            const tpl = document.querySelector('meta[name="municipals-url-template"]').content;
            const url = tpl.replace('CITY_ID__PLACEHOLDER', encodeURIComponent(citySelect.value));

            fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    },
                    cache: 'no-store'
                })
                .then(r => {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(data => {
                    municipalSelect.innerHTML = '<option value="" selected>اختر البلدية</option>';
                    if (Array.isArray(data) && data.length) {
                        data.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.id;
                            opt.textContent = item.name;
                            municipalSelect.appendChild(opt);
                        });
                        municipalSelect.disabled = false;

                        @if (old('municipals_id'))
                            municipalSelect.value = "{{ old('municipals_id') }}";
                        @endif
                    } else {
                        municipalSelect.innerHTML = '<option value="" selected>لا توجد بلديات متاحة</option>';
                    }
                })
                .catch(err => {
                    console.error('[Municipals] error:', err);
                    municipalSelect.innerHTML = '<option value="" selected>تعذر التحميل، حاول مجدداً</option>';
                })
                .finally(() => {
                    skeleton?.classList.remove('show');
                });
        });

        // عند التحميل: استرجاع المدينة القديمة إن وجدت
        document.addEventListener('DOMContentLoaded', () => {
            const city = document.getElementById('city');
            const oldCity = "{{ old('cities_id') }}";
            if (oldCity) {
                city.value = oldCity;
                city.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>

</html>
