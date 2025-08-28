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
            max-width: 500px;
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

    <!-- jQuery (لو مش مضاف) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>

    <!-- دعم RTL لSelect2 -->
    <style>
        /* خليه يركب كويس مع مدخلاتك الدائرية */
        .select2-container--default .select2-selection--single {
            height: var(--control-h);
            border: 1px solid #d7dbe0;
            border-radius: 999px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection__rendered {
            line-height: var(--control-h);
            padding-inline: 14px;
            font-family: 'Tajawal', sans-serif;
        }

        .select2-container--default .select2-selection__arrow {
            height: var(--control-h);
        }

        /* فوكس */
        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default .select2-selection--single:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .18);
            outline: none;
        }

        /* خلي العرض 100% */
        .select2-container {
            width: 100% !important;
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
                    <form action="{{ route('check-customer') }}" method="GET" id="signUp-form" method="GET">

                        @csrf

                        {{-- الرقم الوطني --}}
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

                        {{-- رقم الهاتف + زر تحقق (OTP) --}}
                        <div class="form-group">
                            <label for="phone">رقم الهاتف</label>
                            <div class="inline-grid">
                                <div class="input-icon">
                                    <i class="fa fa-phone"></i>
                                    <input name="phone" id="phone" required maxlength="9" minlength="9"
                                        onkeypress="return onlyNumberKey(event)"
                                        title="يرجي كتابة رقم الهاتف مطابقا (xxx xx xx )91-92-93-94-21"
                                        pattern="(92|91|94|93|21)?[0-9]{7}" value="{{ old('phone') }}"
                                        placeholder="9xxxxxxx">
                                </div>
                                <button type="button" id="btn" onclick="sendotp()" class="btn-otp">تحقّق</button>
                            </div>
                            @error('phone')
                                <span class="error-text" role="alert">{{ $message }}</span>
                            @enderror
                            <small class="help">يرجي كتابة رقم الهاتف مطابقا (xxx xx xx )91-92-93-94-21</small>
                        </div>

                        {{-- رمز التحقق --}}
                        <div class="form-group">
                            <label for="otp">رمز التحقق</label>
                            <div class="input-icon">
                                <i class="fa fa-lock"></i>
                                <input type="text" name="otp" pattern="[0-9]{6}" minlength="6" maxlength="6"
                                    class="form-control @error('otp') is-invalid @enderror" value="{{ old('otp') }}"
                                    id="otp" placeholder="XXXXXX">

                            </div>
                            @error('otp')
                                <span class="error-text" role="alert">{{ $message }}</span>
                            @enderror
                            <small class="help">رمز التحقق XXXXXX مدة استخدام الرمز دقيقة واحدة</small>
                        </div>

                        {{-- الفئة --}}
                        <div class="form-group">
                            <label for="beneficiariesSupCategories">الفئة</label>
                            <div class="input-icon">
                                <i class="fa fa-caret-down"></i>
                                <select id="beneficiariesSupCategories" name="beneficiariesSupCategories" required>
                                    <option selected value="">الرجاء اختر الفئة</option>
                                    @foreach ($customer as $items)
                                        <option value="{{ $items->id }}"
                                            {{ old('beneficiariesSupCategories') == $items->id ? 'selected' : '' }}>
                                            {{ $items->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('beneficiariesSupCategories')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        @php($s = session('sheetMatch'))

                        @if (session('verified_ok') && session('sheetMatch'))
                            <div
                                style="margin-top:14px; background:#ecfdf5; border:1px solid #34d399; color:#065f46; border-radius:12px; padding:12px;">
                                <strong> بيانات المطابقة </strong>
                                <ul style="margin:8px 0 0; padding-right:18px; line-height:1.9">
                                    <li>رقم الضمان: <b>{{ session('insured_no') ?? '—' }}</b></li>
                                    @if (session('pension_no'))
                                        <li>رقم المعاش: <b>{{ session('pension_no') }}</b></li>
                                    @endif
                                    <li>رقم الحساب: <b>{{ session('account_no') ?? '—' }}</b></li>
                                    <li>إجمالي المرتب: <b>{{ session('total_pension') ?? '—' }}</b></li>
                                </ul>
                            </div>
                        @endif


                        {{-- نوع جهة العمل (يظهر فقط عند 7 أو 8) --}}
                        <div id="workCategoryBlock" class="form-group" style="display:none;">
                            <label for="work_category_id">نوع جهة العمل </label>
                            <div class="input-icon">
                                <i class="fa fa-briefcase"></i>
                                <select id="work_category_id" name="work_category_id">
                                    <option value="">اختر نوع الجهة...</option>
                                    @foreach ($workCategories as $wc)
                                        <option value="{{ $wc->id }}"
                                            {{ old('work_category_id') == $wc->id ? 'selected' : '' }}>
                                            {{ $wc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="help">اختر نوع الجهة ليتم تحميل جهات العمل المرتبطة.</small>
                            @error('work_category_id')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- جهة العمل (يظهر فقط عند 7 أو 8) --}}
                        <div id="institutionBlock" class="form-group" style="display:none;">
                            <label for="institution_id">جهة العمل</label>
                            <div class="input-icon">
                                <i class="fa fa-building"></i>
                                <select id="institution_id" name="institution_id">
                                    <option value="">اختر جهة العمل...</option>
                                </select>
                            </div>
                            @error('institution_id')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>



                        <div class="actions">
                            <button id="btnSubmit" class="btn" type="submit">تأكيد</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- عرض أخطاء Laravel الافتراضية (إن وُجدت) --}}
            @if ($errors->any())
                <div
                    style="margin-top:14px; background:#fff; border:1px solid #fca5a5; color:#991b1b; border-radius:12px; padding:10px;">
                    <ul style="margin:0; padding-right:18px;">
                        @foreach ($errors->all() as $error)
                            <li style="margin:4px 0;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#institution_id').select2({
                dir: "rtl", // يمين لليسار
                placeholder: "اختر جهة العمل...",
                allowClear: true, // يسمح تمسح الاختيار
                width: "100%" // ياخذ عرض كامل
            });
        });
    </script>

    <script>
        (function() {
            const catSelect = document.getElementById('beneficiariesSupCategories');
            const wcBlock = document.getElementById('workCategoryBlock');
            const wcSelect = document.getElementById('work_category_id');
            const instBlock = document.getElementById('institutionBlock');
            const instSelect = document.getElementById('institution_id');

            const needsWorkCat = id => id === '7' || id === '8';

            // ✅ خريطة الخيارات المسموحة لكل فئة
            const ALLOWED_BY_CAT = {
                '7': new Set(['19', '20']), // قطاع عام
                '8': new Set(['21']), // قطاع خاص (خيار واحد)
            };

            function filterWorkCategories() {
                const cat = catSelect.value || '';
                const allowed = ALLOWED_BY_CAT[cat];

                // أظهر/أخفِ خيارات نوع جهة العمل حسب الفئة
                Array.from(wcSelect.options).forEach(opt => {
                    if (!opt.value) {
                        opt.style.display = '';
                        return;
                    } // placeholder
                    if (!allowed) {
                        opt.style.display = '';
                        return;
                    } // لو مش 7 أو 8: أظهر الكل
                    opt.style.display = allowed.has(String(opt.value)) ? '' : 'none';
                });

                // لو الخيار الحالي أصبح مخفي، فضّي الاختيار ونظّف جهات العمل
                const selectedHidden = wcSelect.selectedOptions[0] && wcSelect.selectedOptions[0].style.display ===
                    'none';
                if (selectedHidden) {
                    wcSelect.value = '';
                    instSelect.innerHTML = `<option value="">اختر جهة العمل...</option>`;
                    wcSelect.dispatchEvent(new Event('change'));
                }
            }

            // ✅ قفل/فكّ قفل "نوع جهة العمل" بناءً على الفئة
            function lockWorkCategoryIfSingle() {
                const cat = catSelect.value || '';
                const allowed = ALLOWED_BY_CAT[cat];

                // لو الفئة 8 (خاص) وفيه خيار واحد فقط: ثبّت القيمة واعمِل disable
                if (allowed && allowed.size === 1) {
                    const only = [...allowed][0];
                    // تأكد أنه ظاهر بعد الفلترة
                    wcSelect.value = only;
                    wcSelect.disabled = true;
                    // فعّل تحميل المؤسسات للخيار المثبّت
                    wcSelect.dispatchEvent(new Event('change'));
                } else {
                    // باقي الحالات: خليه قابل للتعديل
                    wcSelect.disabled = false;
                }
            }

            // ✅ نفس منطقك لإظهار بلوكات الجهة للفئات 7 و8 + مع القفل
            catSelect.addEventListener('change', () => {
                const selected = catSelect.value || '';
                const show = needsWorkCat(selected);

                wcBlock.style.display = show ? '' : 'none';
                instBlock.style.display = show ? '' : 'none';

                wcSelect.toggleAttribute('required', show);
                instSelect.toggleAttribute('required', show);

                if (!show) {
                    wcSelect.value = '';
                    wcSelect.disabled = false; // فك القفل عند الخروج من 7/8
                    instSelect.innerHTML = `<option value="">اختر جهة العمل...</option>`;
                }

                filterWorkCategories();
                lockWorkCategoryIfSingle(); // << القفل هنا
            });

            // عند تغيير نوع الجهة، حمّل المؤسسات حسب النوع
            wcSelect.addEventListener('change', () => {
                const wcId = wcSelect.value || '';
                if (!wcId) {
                    instSelect.innerHTML = `<option value="">اختر جهة العمل...</option>`;
                    return;
                }
                const list = INSTITUCIONS.filter(x => String(x.work_categories_id) === String(wcId));
                const opts = [`<option value="">اختر جهة العمل...</option>`];
                for (const it of list) {
                    opts.push(`<option value="${it.id}">${it.name ?? it.title ?? ''}</option>`);
                }
                instSelect.innerHTML = opts.join('');
            });

            // ✅ عند التحميل (لدعم old())
            document.addEventListener('DOMContentLoaded', () => {
                const oldCat = "{{ old('beneficiariesSupCategories') }}";
                const show = needsWorkCat(oldCat);

                wcBlock.style.display = show ? '' : 'none';
                instBlock.style.display = show ? '' : 'none';
                wcSelect.toggleAttribute('required', show);
                instSelect.toggleAttribute('required', show);

                filterWorkCategories();
                lockWorkCategoryIfSingle(); // << يطبّق القفل في حالة old() أيضاً
            });
        })();
    </script>



    <script>
        // إدخال أرقام فقط
        function onlyNumberKey(e) {
            const code = e.which ? e.which : e.keyCode;
            const allowed = [8, 9, 37, 39, 46];
            if (allowed.includes(code)) return true;
            if (code < 48 || code > 57) return false;
            return true;
        }

        // زر إرسال OTP (عدّليها حسب API عندك)
        function sendotp() {
            // TODO: Ajax لطلب إرسال OTP حسب نظامك
            alert('سيتم إرسال رمز تحقق عبر SMS (مثال)');
        }

        // بيانات الجهات محمّلة مسبقًا
        const INSTITUCIONS = @json($institucions);

        (function() {
            const catSelect = document.getElementById('beneficiariesSupCategories');
            const wcBlock = document.getElementById('workCategoryBlock');
            const wcSelect = document.getElementById('work_category_id');
            const instBlock = document.getElementById('institutionBlock');
            const instSelect = document.getElementById('institution_id');

            // الفئات التي تتبع جهة عمل (عدّلي IDs لو مختلفة)
            const needsWorkCat = id => id === '7' || id === '8';

            function fillOptions(selectEl, items, placeholder) {
                const opts = [`<option value="">${placeholder}</option>`];
                for (const it of items) {
                    const id = (it.id ?? '').toString();
                    const name = (it.name ?? it.title ?? '').toString();
                    const selected = "{{ old('institution_id') }}" === id ? 'selected' : '';
                    opts.push(`<option value="${id}" ${selected}>${name}</option>`);
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

            // دعم old() بعد فشل التحقق — يعيد إظهار خيارات جهة العمل تلقائيًا
            document.addEventListener('DOMContentLoaded', () => {
                const oldCat = "{{ old('beneficiariesSupCategories') }}";
                const oldWc = "{{ old('work_category_id') }}";

                if (oldCat && needsWorkCat(oldCat)) {
                    wcBlock.style.display = '';
                    instBlock.style.display = '';
                    wcSelect.setAttribute('required', 'required');
                    instSelect.setAttribute('required', 'required');

                    if (oldWc) {
                        wcSelect.value = oldWc;
                        const list = INSTITUCIONS.filter(x => String(x.work_categories_id) === String(oldWc));
                        fillOptions(instSelect, list, 'اختر جهة العمل...');
                    }
                }
            });
        })();
    </script>
</body>

<script>
    function sendotp() {
        let phone = document.getElementById('phone').value;

        if (phone === '') {
            alert('الرجاء إدخال رقم الهاتف');
            return;
        }

        fetch("{{ url('/sendotp') }}/" + phone)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("demo").innerText = "✅ " + data.message + " (OTP: " + data.otp + ")";
                } else {
                    document.getElementById("demo").innerText = "❌ " + data.message;
                }
            })
            .catch(error => {
                console.error("Error:", error);
                document.getElementById("demo").innerText = "❌ حدث خطأ غير متوقع";
            });
    }
</script>

</html>
