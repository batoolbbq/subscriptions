<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>بوابة تسجيل وكلاء التأمين</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- شكل/تنسيق فقط -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --brand: #F58220;
            --ink: #111827;
            --muted: #6b7280;
            --border: #E5E7EB;
            --bg: #f8fafc;
            --panel: #fff;
            --soft-1: #FFF7EE;
            --soft-2: #FCE8D6;
            --radius: 14px;
            --radius-sm: 10px;
            --shadow: 0 10px 28px rgba(17, 24, 39, .07)
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
            line-height: 1.65
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
            color: #ac584b;
            font-size: 1.6rem
        }

        .card {
            background: var(--panel);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden
        }

        .card-head {
            background: linear-gradient(180deg, var(--soft-1), var(--soft-2));
            padding: 14px;
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
            font-weight: 800
        }

        .card-head .icon {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            background: var(--brand);
            color: #fff;
            display: grid;
            place-items: center
        }

        .card-body {
            padding: 20px
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px
        }

        @media (min-width:720px) {
            .grid-2 {
                grid-template-columns: 2fr 1fr
            }
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
            border: 1px solid #d7dbe0;
            border-radius: var(--radius-sm);
            padding: 11px 12px;
            font-size: 1rem;
            background: #fff;
            outline: none
        }

        input:focus,
        select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .18)
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
            margin-top: 6px
        }

        .btn {
            all: unset;
            display: inline-block;
            padding: 12px 24px;
            border-radius: 999px;
            background: var(--brand);
            color: #fff;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(245, 130, 32, .22)
        }

        .btn:hover {
            transform: translateY(-1px)
        }

        .row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px
        }

        @media (min-width:720px) {
            .row-2 {
                grid-template-columns: 2fr 1fr
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

                        <div class="grid">
                            <!-- الرقم الوطني -->
                            <div class="form-group">
                                <label for="nationalID">الرقم الوطني</label>
                                <input name="nationalID" id="nationalID" maxlength="12" minlength="12"
                                    onkeypress="return onlyNumberKey(event)" value="{{ old('nationalID') }}">
                                @error('nationalID')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- رقم الهاتف + زر تحقق -->
                            <div class="grid grid-2">
                                <div class="form-group">
                                    <label for="phone">رقم الهاتف</label>
                                    <input name="phone" id="phone" required maxlength="9" minlength="9"
                                        onkeypress="return onlyNumberKey(event)"
                                        title="يرجي كتابة رقم الهاتف مطابقا (xxx xx xx )91-92-94"
                                        pattern="(92|91|94|93)?[0-9]{7}" value="{{ old('phone') }}">
                                    @error('phone')
                                        <span class="error-text" role="alert">{{ $message }}</span>
                                    @enderror
                                    <small class="help">يرجي كتابة رقم الهاتف مطابقا (xxx xx xx )91-92-94</small>
                                </div>

                                <div class="form-group" style="align-self:end">
                                    <button type="button" id="btn" onclick="sendotp()"
                                        class="btn line-button-one button-6" style="width:100%">تحقق</button>
                                    <small id="demo" class="help"></small>
                                </div>
                            </div>

                            <!-- رمز التحقق -->
                            <div class="form-group">
                                <label for="otp">رمز التحقق</label>
                                <input type="text" required title="رمز التحقق متكون من سته أرقام" pattern="[0-9]{6}"
                                    minlength="6" maxlength="6" name="otp"
                                    onkeypress="return onlyNumberKey(event)"
                                    class="form-control @error('otp') is-invalid @enderror" value="{{ old('otp') }}"
                                    id="otp">
                                @error('otp')
                                    <span class="error-text" role="alert">{{ $message }}</span>
                                @enderror
                                <small class="help">رمز التحقق XXXXXX مدة استخدام الرمز دقيقة واحدة</small>
                            </div>

                            <!-- اختيار الفئة -->
                            <div class="form-group">
                                <label for="beneficiariesSupCategories">الفئة</label>
                                <div class="input-group w-100">
                                    <select id="beneficiariesSupCategories" name="beneficiariesSupCategories" required
                                        class="form-control @error('') is-invalid @enderror">
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
                                <select id="work_category_id" name="work_category_id" class="form-control">
                                    <option value="">اختر نوع الجهة...</option>
                                    @foreach ($workCategories as $wc)
                                        <option value="{{ $wc->id }}">{{ $wc->name }}</option>
                                    @endforeach
                                </select>
                                <small class="help">اختر نوع الجهة ليتم تحميل جهات العمل المرتبطة.</small>
                            </div>

                            <div id="institutionBlock" class="form-group" style="display:none;">
                                <label for="institution_id">جهة العمل</label>
                                <select id="institution_id" name="institution_id" class="form-control">
                                    <option value="">اختر جهة العمل...</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="actions">
                                <button id="btnSubmit" class="btn line-button-one" type="submit">تأكيد</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        // نفس دالتك لمنع غير الأرقام
        function onlyNumberKey(e) {
            const code = e.which ? e.which : e.keyCode;
            const allowed = [8, 9, 37, 39, 46];
            if (allowed.includes(code)) return true;
            if (code < 48 || code > 57) return false;
            return true;
        }

        // بيانات محمّلة مسبقًا من السيرفر (بدون Ajax)
        const INSTITUCIONS = @json($institucions);

        (function() {
            const catSelect = document.getElementById('beneficiariesSupCategories');
            const wcBlock = document.getElementById('workCategoryBlock');
            const wcSelect = document.getElementById('work_category_id');
            const instBlock = document.getElementById('institutionBlock');
            const instSelect = document.getElementById('institution_id');

            // الفئات التي تحتاج اختيار نوع جهة عمل
            const needsWorkCat = (id) => id === '7' || id === '8';

            // بناء خيارات لمصفوفة
            function fillOptions(selectEl, items, placeholder) {
                const opts = [`<option value="">${placeholder}</option>`];
                for (const it of items) {
                    const id = (it.id ?? '').toString();
                    const name = (it.name ?? it.title ?? '').toString();
                    opts.push(`<option value="${id}">${name}</option>`);
                }
                selectEl.innerHTML = opts.join('');
            }

            // عند تغيير الفئة
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

                // عند الحاجة فقط نعرض السلكت (الخيارـ options متولدة من السيرفر في الـBlade)
                instSelect.innerHTML = `<option value="">اختر جهة العمل...</option>`;
            });

            // عند تغيير نوع الجهة → صفّي المؤسسات حسب work_categories_id (⚠️ كما في DB لديك)
            wcSelect.addEventListener('change', () => {
                const wcId = wcSelect.value || '';
                if (!wcId) {
                    instSelect.innerHTML = `<option value="">اختر جهة العمل...</option>`;
                    return;
                }
                // ✅ استخدام work_categories_id بدل work_category_id
                const list = INSTITUCIONS.filter(x => String(x.work_categories_id) === String(wcId));
                fillOptions(instSelect, list, 'اختر جهة العمل...');
            });

            // دعم old() لو رجعتي من فشل فاليديشن
            document.addEventListener('DOMContentLoaded', () => {
                const oldCat = "{{ old('beneficiariesSupCategories') }}";
                const oldWc = "{{ old('work_category_id') }}";
                const oldInst = "{{ old('institution_id') }}";

                if (oldCat && needsWorkCat(oldCat)) {
                    wcBlock.style.display = '';
                    instBlock.style.display = '';
                    wcSelect.setAttribute('required', 'required');
                    instSelect.setAttribute('required', 'required');

                    // إعادة تعيين المؤسسة حسب النوع السابق
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
