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
        /* ====== Theme (Logo colors + modern UI) ====== */
        :root {
            --brand: #F58220;
            /* برتقالي أساسي */
            --brand-600: #ff8f34;
            /* أغمق للهوفر */
            --brown: #8C5346;
            /* بني سابق للعناوين */
            --ink: #1F2328;
            --muted: #6b7280;
            --muted-2: #9ca3af;
            --panel: #ffffff;
            --border: #E5E7EB;
            --bg-1: #FFF7EE;
            /* خلفية دافئة */
            --bg-2: #FCE8D6;
            --radius-xl: 24px;
            --radius-md: 16px;
            --radius-sm: 12px;
            --shadow-lg: 0 18px 40px rgba(0, 0, 0, .12);
            --shadow-md: 0 10px 28px rgba(0, 0, 0, .08);
            --focus: 0 0 0 4px rgba(245, 130, 32, .18);
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
            color: var(--ink);
            font-family: 'Tajawal', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
            line-height: 1.65;
            /* خلفية لامعة بألوان الشعار */
            background:
                radial-gradient(1100px 560px at 85% 12%, rgba(140, 83, 70, .18), transparent 60%),
                radial-gradient(900px 520px at 12% 88%, rgba(245, 130, 32, .22), transparent 60%),
                linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 42%, #ffd8b6 78%, #ffe4cc 100%);
            background-attachment: fixed;
        }

        .page {
            min-height: 100dvh;
            display: grid;
            place-items: center;
            padding: 28px;
        }

        .form-wrap {
            width: 100%;
            max-width: 780px;
            margin-inline: auto
        }

        .title-area {
            text-align: center;
            margin-bottom: 16px
        }

        .title-area h3 {
            margin: 0 0 6px;
            font-weight: 800;
            letter-spacing: .2px;
            color: var(--brown);
            font-size: 1.7rem;
        }

        .title-area p {
            margin: 0;
            color: var(--muted);
            font-size: .98rem
        }

        .cardy {
            background: var(--panel);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            margin-bottom: 18px;
        }

        /* ترويسة الكارد برتقالي متدرج قوي */
        .cardy-header {
            background: linear-gradient(135deg,
                    #d95b00 0%,
                    /* غامق */
                    #F58220 35%,
                    /* برتقالي الشعار */
                    #FF8F34 70%,
                    /* أفتح */
                    #ffb066 100%
                    /* أفتح جداً */
                );
            color: #fff;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 800;
        }

        .cardy-header .icon {
            background: #FF8F34;
            /* وسط التدرج */
            color: #fff;
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            font-size: .95rem;
            box-shadow: 0 10px 22px rgba(245, 130, 32, .35);
        }

        .cardy-body {
            padding: 22px 20px 26px
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px
        }

        @media (min-width:720px) {
            .grid-2 {
                grid-template-columns: 1fr 1fr
            }
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: var(--muted);
            font-size: .95rem;
            font-weight: 700;
        }

        /* حقول بشكل pill */
        .form-control,
        .form-select,
        textarea {
            width: 100%;
            border: 1px solid #d7dbe0;
            background: #fdfdfd;
            border-radius: 999px;
            /* pill */
            padding: 12px 14px;
            font-size: 1rem;
            transition: border-color .2s, box-shadow .2s, background .2s, transform .1s;
            outline: none;
        }

        textarea {
            border-radius: var(--radius-md)
        }

        /* خلي خيارات الـselect و النص الافتراضي بنفس خط Tajawal */
        .form-select,
        .form-select option {
            font-family: 'Tajawal', sans-serif;
            font-weight: 500;
        }

        /* مساحة نص أكبر بحواف لينة */
        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            border-color: var(--brand);
            box-shadow: var(--focus);
        }

        .help {
            color: #9aa0a6;
            font-size: .85rem;
            margin-top: 6px;
            display: block
        }

        #docs-card {
            border: 1.5px solid var(--border);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            margin-bottom: 16px;
            background: #fff;
            overflow: hidden;
        }

        .btn {
            all: unset;
            display: inline-block;
            cursor: pointer;
            text-align: center;
            padding: 13px 26px;
            border-radius: 999px;
            font-weight: 900;
            font-size: 1rem;
            letter-spacing: .3px;
            transition: transform .15s, background .15s, box-shadow .15s, color .15s;
        }

        .btn-primary {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 12px 26px rgba(245, 130, 32, .30);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            background: var(--brand-600)
        }

        .btn-primary:active {
            transform: translateY(0)
        }

        .text-center {
            text-align: center
        }

        /* تنبيهات */
        .alert {
            padding: 12px 14px;
            border-radius: 14px;
            font-weight: 800;
            margin-bottom: 1rem;
            text-align: center;
            border: 1.5px solid;
            box-shadow: var(--shadow-md);
        }

        .alert.ok {
            background: #e9fbf2;
            border-color: #86efac;
            color: #10734a
        }

        .alert.err {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b
        }

        .error-text {
            color: #b91c1c;
            font-size: .85rem;
            margin-top: 6px;
            font-weight: 700
        }

        .skeleton {
            display: none;
            height: 42px;
            border: 1px dashed var(--border);
            border-radius: 999px;
            background: #fff5eb;
            color: #a0765f;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
        }

        .skeleton.show {
            display: flex
        }

        /* Placeholder text inside inputs & textarea & select */
        .form-control::placeholder,
        textarea::placeholder,
        .form-select::placeholder {
            font-family: 'Tajawal', sans-serif;
            font-weight: 400;
            color: var(--muted);
            font-size: 0.95rem;
        }

        /* ====== رفع الملفات: زر أنيق مع أيقونة ====== */
        input[type="file"] {
            display: none
        }

        /* نخفي الحقل الأصلي */
        .file-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .file-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 12px 18px;
            border-radius: 999px;
            /* pill */
            background: var(--brand);
            color: #fff;
            font-weight: 700;
            font-size: .95rem;
            transition: background .2s, transform .2s, box-shadow .2s;
            box-shadow: 0 8px 18px rgba(245, 130, 32, .22);
        }

        .file-label:hover {
            background: var(--brand-600);
            transform: translateY(-1px)
        }

        .file-label i {
            font-size: 1rem
        }

        /* اسم الملف بعد الاختيار */
        .file-name {
            margin-top: 6px;
            font-size: .85rem;
            color: #555;
            font-weight: 600;
            display: none;
        }

        .file-hint {
            color: #9aa0a6;
            font-size: .8rem;
            margin-top: 4px
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
                            rows="4" placeholder="مثال: مكتب بمساحة 40م²،     ..." required>{{ old('description') }}</textarea>
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

                                <!-- شهادة ميلاد -->
                                <div class="form-group">
                                    <label>شهادة ميلاد</label>
                                    <div class="file-actions">
                                        <label for="Birth_creature" class="file-label">
                                            <i class="fa fa-id-card"></i> اختر ملف
                                        </label>
                                    </div>
                                    <input class="@error('Birth_creature') is-invalid @enderror" type="file"
                                        id="Birth_creature" name="Birth_creature"  required>
                                    <div class="file-name" data-file="Birth_creature"></div>
                                    <div class="file-hint"></div>
                                    @error('Birth_creature')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- المؤهل العلمي -->
                                <div class="form-group">
                                    <label>المؤهل العلمي</label>
                                    <div class="file-actions">
                                        <label for="qualification" class="file-label">
                                            <i class="fa fa-graduation-cap"></i> اختر ملف
                                        </label>
                                    </div>
                                    <input class="@error('qualification') is-invalid @enderror" type="file"
                                        id="qualification" name="qualification"  required>
                                    <div class="file-name" data-file="qualification"></div>
                                    <div class="file-hint"></div>
                                    @error('qualification')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- صورة للمكان -->
                                <div class="form-group">
                                    <label>صورة للمكان</label>
                                    <div class="file-actions">
                                        <label for="image" class="file-label">
                                            <i class="fa fa-building"></i> اختر ملف
                                        </label>
                                    </div>
                                    <input class="@error('image') is-invalid @enderror" type="file" id="image"
                                        name="image" accept=".jpg,.jpeg,.png" required>
                                    <div class="file-name" data-file="image"></div>
                                    <div class="file-hint">الامتدادات المسموحة: JPG, JPEG, PNG</div>
                                    @error('image')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- شهادة التأمين -->
                                {{-- <div class="form-group">
                                    <label>شهادة التأمين</label>
                                    <div class="file-actions">
                                        <label for="Insurance_certificate" class="file-label">
                                            <i class="fa fa-shield-alt"></i> اختر ملف
                                        </label>
                                    </div>
                                    <input class="@error('Insurance_certificate') is-invalid @enderror"
                                        type="file" id="Insurance_certificate" name="Insurance_certificate"
                                        accept=".jpg,.jpeg,.png" required>
                                    <div class="file-name" data-file="Insurance_certificate"></div>
                                    <div class="file-hint">الامتدادات المسموحة: JPG, JPEG, PNG</div>
                                    @error('Insurance_certificate')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div> --}}

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
