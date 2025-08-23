<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>بوابة تسجيل المشتركين</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

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
            /* ارتفاع الحقول المضغوطة */
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
            background:
                radial-gradient(1100px 560px at 85% 12%, rgba(245, 130, 32, .18), transparent 60%),
                radial-gradient(900px 520px at 12% 88%, rgba(109, 7, 26, .18), transparent 60%),
                linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 42%, #ffd8b6 78%, #ffe4cc 100%);
            background-attachment: fixed;
        }

        .page {
            min-height: 100dvh;
            display: grid;
            place-items: center;
            padding: 24px
        }

        .wrap {
            width: 100%;
            max-width: 820px;
            margin-inline: auto
        }

        .title-area {
            text-align: center;
            margin-bottom: 18px
        }

        .title-area h3 {
            margin: 0 0 .25rem;
            font-weight: 800;
            color: var(--brand);
            font-size: 1.6rem
        }

        .card {
            background: var(--panel);
            border: 1.5px solid var(--border);
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden
        }

        .card-head {
            background: linear-gradient(135deg, #F58220 0%, #c24515 40%, #6D071A 100%);
            padding: 14px;
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
            font-weight: 800;
            color: #fff;
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

        /* ===== حقول مضغوطة + أيقونات داخلة ===== */
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
            font-family: 'Tajawal', sans-serif;
            transition: border-color .2s, box-shadow .2s;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            line-height: 1;
        }

        input:focus,
        select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .18)
        }

        select,
        select option {
            font-family: 'Tajawal', sans-serif;
            font-weight: 500
        }

        input::placeholder,
        select::placeholder {
            font-family: 'Tajawal', sans-serif;
            font-weight: 400;
            color: #9aa0a6;
            font-size: .9rem
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
            font-size: .9rem;
            pointer-events: none
        }

        .input-icon input,
        .input-icon select {
            padding-right: 38px !important;
            height: var(--control-h)
        }

        /* textarea خفيف */
        textarea {
            width: 100%;
            min-height: 80px;
            border: 1px solid #d7dbe0;
            border-radius: 14px;
            padding: 8px 12px;
            font-family: 'Tajawal', sans-serif;
            font-size: .9rem;
            line-height: 1.4;
        }

        .help {
            color: #9aa0a6;
            font-size: .85rem;
            margin-top: 6px;
            display: block
        }

        .error-text {
            color: #b91c1c;
            font-size: .85rem;
            margin-top: 6px;
            font-weight: 700
        }

        .actions {
            text-align: center;
            margin-top: 10px
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
            transition: transform .15s, background .15s;
        }

        .btn:hover {
            transform: translateY(-1px);
            background: var(--brand-600)
        }

        /* زر OTP مختلف (Outline) */
        .btn-otp {
            all: unset;
            cursor: pointer;
            padding: 0 16px;
            height: var(--control-h);
            line-height: var(--control-h);
            border-radius: 999px;
            border: 2px solid var(--brand);
            color: var(--brand);
            font-weight: 700;
            font-size: .85rem;
            background: transparent;
            text-align: center;
            transition: all .2s;
        }

        .btn-otp:hover {
            background: rgba(245, 130, 32, .1)
        }

        .btn-otp:active {
            transform: scale(.97)
        }

        /* حقل + زر بجانب بعض */
        .inline-grid {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: end
        }

        @media (max-width:719px) {
            .inline-grid {
                grid-template-columns: 1fr
            }

            .btn-otp {
                width: 100%
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="wrap">

            <div class="title-area">
                <h3>تسجيل مشترك</h3>
            </div>

            <div class="card">
                <div class="card-head">
                    <span class="icon"><i class="fa-solid fa-id-card"></i></span>
                    <span>بيانات التحقق</span>
                </div>

                <div class="card-body">
                    <form action="{{ route('check-customer') }}" method="GET" id="signUp-form">
                        @csrf

                        <!-- الرقم الوطني -->
                        <div class="form-group">
                            <label for="nationalID">الرقم الوطني</label>
                            <div class="input-icon">
                                <i class="fa fa-id-card"></i>
                                <input name="nationalID" id="nationalID" maxlength="12" minlength="12"
                                    onkeypress="return onlyNumberKey(event)" value="{{ old('nationalID') }}"
                                    placeholder="XXXXXXXXXXXX">
                            </div>
                            @error('nationalID')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- رقم الهاتف + زر تحقق (نفس السطر) -->
                        <div class="form-group">
                            <label for="phone">رقم الهاتف</label>
                            <div class="inline-grid">
                                <div class="input-icon">
                                    <i class="fa fa-phone"></i>
                                    <input name="phone" id="phone" required maxlength="9" minlength="9"
                                        onkeypress="return onlyNumberKey(event)"
                                        title="يرجي كتابة رقم الهاتف مطابقا (xxx xx xx )91-92-94"
                                        pattern="(92|91|94|93)?[0-9]{7}" value="{{ old('phone') }}"
                                        placeholder="9xxxxxxx">
                                </div>
                                <button type="button" id="btn" onclick="sendotp()" class="btn-otp">تحقّق</button>
                            </div>
                            @error('phone')
                                <span class="error-text" role="alert">{{ $message }}</span>
                            @enderror
                            <small class="help">يرجي كتابة رقم الهاتف مطابقا (xxx xx xx )91-92-94</small>
                        </div>

                        <!-- رمز التحقق -->
                        <div class="form-group">
                            <label for="otp">رمز التحقق</label>
                            <div class="input-icon">
                                <i class="fa fa-lock"></i>
                                <input type="text" required title="رمز التحقق متكون من سته أرقام" pattern="[0-9]{6}"
                                    minlength="6" maxlength="6" name="otp"
                                    onkeypress="return onlyNumberKey(event)"
                                    class="form-control @error('otp') is-invalid @enderror" value="{{ old('otp') }}"
                                    id="otp" placeholder="XXXXXX">
                            </div>
                            @error('otp')
                                <span class="error-text" role="alert">{{ $message }}</span>
                            @enderror
                            <small class="help">رمز التحقق XXXXXX مدة استخدام الرمز دقيقة واحدة</small>
                        </div>

                        <!-- الفئة (بعد OTP) -->
                        <div class="form-group">
                            <label for="beneficiariesSupCategories">الفئة</label>
                            <div class="input-icon">
                                <i class="fa fa-caret-down"></i>
                                <select id="beneficiariesSupCategories" name="beneficiariesSupCategories" required>
                                    <option selected value="">الرجاء اختر الفئة</option>
                                    @foreach ($customer as $items)
                                        <option value="{{ $items->id }}">{{ $items->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('beneficiariesSupCategories')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- يظهر فقط عند اختيار الفئة 7 أو 8 -->
                        <div id="workCategoryBlock" class="form-group" style="display:none;">
                            <label for="work_category_id">نوع جهة العمل (work_categories)</label>
                            <div class="input-icon">
                                <i class="fa fa-briefcase"></i>
                                <select id="work_category_id" name="work_category_id">
                                    <option value="">اختر نوع الجهة...</option>
                                    @foreach ($workCategories as $wc)
                                        <option value="{{ $wc->id }}">{{ $wc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="help">اختر نوع الجهة ليتم تحميل جهات العمل المرتبطة.</small>
                        </div>

                        <div id="institutionBlock" class="form-group" style="display:none;">
                            <label for="institution_id">جهة العمل</label>
                            <div class="input-icon">
                                <i class="fa fa-building"></i>
                                <select id="institution_id" name="institution_id">
                                    <option value="">اختر جهة العمل...</option>
                                </select>
                            </div>
                        </div>

                        <div class="actions">
                            <button id="btnSubmit" class="btn" type="submit">تأكيد</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        // أرقام فقط
        function onlyNumberKey(e) {
            const code = e.which ? e.which : e.keyCode;
            const allowed = [8, 9, 37, 39, 46];
            if (allowed.includes(code)) return true;
            if (code < 48 || code > 57) return false;
            return true;
        }

        // بيانات محملة مسبقًا من السيرفر
        const INSTITUCIONS = @json($institucions);

        (function() {
            const catSelect = document.getElementById('beneficiariesSupCategories');
            const wcBlock = document.getElementById('workCategoryBlock');
            const wcSelect = document.getElementById('work_category_id');
            const instBlock = document.getElementById('institutionBlock');
            const instSelect = document.getElementById('institution_id');

            const needsWorkCat = id => id === '7' || id === '8';

            function fillOptions(selectEl, items, placeholder) {
                const opts = [`<option value="">${placeholder}</option>`];
                for (const it of items) {
                    const id = (it.id ?? '').toString();
                    const name = (it.name ?? it.title ?? '').toString();
                    opts.push(`<option value="${id}">${name}</option>`);
                }
                selectEl.innerHTML = opts.join('');
            }

            catSelect.addEventListener('change', () => {
                const selected = catSelect.value || '';
                const show = needsWorkCat(selected);

                wcBlock.style.display = show ? '' : 'none';
                instBlock.style.display = show ? '' : 'none';

                wcSelect.toggleAttribute('required', show);
                instSelect.toggleAttribute('required', show);

                if (!show) {
                    wcSelect.value = '';
                    instSelect.innerHTML = `<option value="">اختر جهة العمل...</option>`;
                    return;
                }
                instSelect.innerHTML = `<option value="">اختر جهة العمل...</option>`;
            });

            wcSelect.addEventListener('change', () => {
                const wcId = wcSelect.value || '';
                if (!wcId) {
                    instSelect.innerHTML = `<option value="">اختر جهة العمل...</option>`;
                    return;
                }
                const list = INSTITUCIONS.filter(x => String(x.work_categories_id) === String(wcId));
                fillOptions(instSelect, list, 'اختر جهة العمل...');
            });

            // دعم old() لو رجعت من فشل فاليديشن
            document.addEventListener('DOMContentLoaded', () => {
                const oldCat = "{{ old('beneficiariesSupCategories') }}";
                const oldWc = "{{ old('work_category_id') }}";
                const oldInst = "{{ old('institution_id') }}";

                if (oldCat && needsWorkCat(oldCat)) {
                    wcBlock.style.display = '';
                    instBlock.style.display = '';
                    wcSelect.setAttribute('required', 'required');
                    instSelect.setAttribute('required', 'required');

                    if (oldWc) {
                        wcSelect.value = oldWc;
                        const list = INSTITUCIONS.filter(x => String(x.work_categories_id) === String(oldWc));
                        fillOptions(instSelect, list, 'اختر جهة العمل...');
                        if (oldInst) {
                            instSelect.value = oldInst;
                        }
                    }
                }
            });
        })();
    </script>
</body>

</html>
