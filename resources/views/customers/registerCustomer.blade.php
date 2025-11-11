<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>بوابة تسجيل المشتركين</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
            --control-h: 38px
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
            background: radial-gradient(1100px 560px at 85% 12%, rgba(245, 130, 32, .18), transparent 60%),
                radial-gradient(900px 520px at 12% 88%, rgba(109, 7, 26, .18), transparent 60%),
                linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 42%, #ffd8b6 78%, #ffe4cc 100%);
            background-attachment: fixed
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
            font-family: 'Tajawal', sans-serif;
            transition: border-color .2s, box-shadow .2s;
            appearance: none;
            line-height: 1
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
            transition: transform .15s, background .15s
        }

        .btn:hover {
            transform: translateY(-1px);
            background: var(--brand-600)
        }

        .panel-error {
            margin-top: 12px;
            background: #fff5f5;
            border: 1px solid #fca5a5;
            color: #991b1b;
            border-radius: 12px;
            padding: 10px
        }

        .panel-success {
            margin-top: 12px;
            background: #ecfdf5;
            border: 1px solid #34d399;
            color: #065f46;
            border-radius: 12px;
            padding: 10px
        }

        .table-wrap {
            margin-top: 10px;
            overflow: auto;
            border: 1px solid #eee;
            border-radius: 12px
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        th,
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #f3f4f6;
            white-space: nowrap
        }

        th {
            text-align: right;
            background: #f9fafb
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
                    {{-- يرسل إلى الدالة الموحدة verifyAll --}}
                    <form action="{{ route('check-customer') }}" id="signUp-form" method="POST">
                        @csrf

                        {{-- نوع المشترك --}}
                        <div class="form-group">
                            <label for="subscriber_type">نوع المشترك</label>
                            <div class="input-icon">
                                <i class="fa fa-user"></i>
                                <select id="subscriber_type" name="subscriber_type" required>
                                    <option value="">اختر نوع المشترك...</option>
                                    <option value="husband" {{ old('subscriber_type') == 'husband' ? 'selected' : '' }}>
                                        مشترك</option>
                                    <option value="wife" {{ old('subscriber_type') == 'wife' ? 'selected' : '' }}>
                                        مشتركة
                                    </option>
                                    <option value="single" {{ old('subscriber_type') == 'single' ? 'selected' : '' }}>
                                        مشترك اعزب</option>
                                </select>
                            </div>
                            @error('subscriber_type')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- الرقم الوطني للزوج --}}
                        <div class="form-group" id="spouse-nationalid-block" style="display:none;">
                            <label for="spouse_national_id">الرقم الوطني للزوج</label>
                            <div class="input-icon">
                                <i class="fa fa-id-card"></i>
                                <input type="text" name="spouse_national_id" id="spouse_national_id"
                                    value="{{ old('spouse_national_id') }}" placeholder="XXXXXXXXXXXX" maxlength="13"
                                    minlength="12" pattern="\d{12,13}" onkeypress="return onlyNumberKey(event)">
                            </div>
                            @error('spouse_national_id')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                            <small class="help">أدخل الرقم الوطني للزوج في حالة اختيار (زوجة)</small>
                        </div>


                        {{-- الرقم الوطني (12 أو 13 رقم) --}}
                        <div class="form-group">
                            <label for="nationalID">الرقم الوطني</label>
                            <div class="input-icon">
                                <i class="fa fa-id-card"></i>
                                <input name="nationalID" id="nationalID" maxlength="13" minlength="12"
                                    pattern="\d{12,13}" onkeypress="return onlyNumberKey(event)"
                                    value="{{ old('nationalID') }}" placeholder="XXXXXXXXXXXX">
                            </div>
                            @error('nationalID')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- رقم الهاتف --}}
                        <div class="form-group">
                            <label for="phone">رقم الهاتف</label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="input-icon" style="flex: 1;">
                                    <i class="fa fa-phone"></i>
                                    <input name="phone" id="phone" maxlength="9" minlength="9"
                                        onkeypress="return onlyNumberKey(event)"
                                        title="يرجي كتابة رقم الهاتف مطابقا (xxx xx xx )91-92-93-94-21"
                                        pattern="(92|91|94|93|21)?[0-9]{7}" value="{{ old('phone') }}"
                                        placeholder="9xxxxxxx" class="form-control">
                                </div>
                                <button type="button" id="btn" onclick="sendotp()"
                                    class="btn btn-primary">تحقق</button>
                            </div>
                            @error('phone')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                            <small class="help">يرجي كتابة رقم الهاتف مطابقا (xxx xx xx )91-92-93-94</small>
                        </div>

                        {{-- رمز التحقق (اختياري حالياً) --}}
                        {{-- رمز التحقق --}}
                        <div class="form-group" id="otp-block" style="display:none;">
                            <label for="otp">رمز التحقق</label>
                            <div class="input-icon">
                                <i class="fa fa-lock"></i>
                                <input type="text" name="otp" pattern="[0-9]{6}" minlength="6" maxlength="6"
                                    value="{{ old('otp') }}" id="otp" placeholder="XXXXXX">
                            </div>
                            @error('otp')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                            <small class="help">رمز التحقق صالح لمدة دقيقة واحدة</small>
                        </div>

                        {{-- رقم القيد (6 أرقام) --}}
                        <div class="form-group">
                            <label for="family_registry_no">رقم القيد</label>
                            <div class="input-icon">
                                <i class="fa fa-hashtag"></i>
                                <input type="text" name="family_registry_no" id="family_registry_no"
                                    value="{{ old('family_registry_no') }}" placeholder="XXXXXX">
                            </div>
                            @error('family_registry_no')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                            <small class="help">رقم القيد مكوّن من 6 الى 8 ارقام</small>
                        </div>

                        {{-- الفئة --}}
                        <div class="form-group">
                            <label for="beneficiariesSupCategories">الفئة</label>
                            <div class="input-icon">
                                <i class="fa fa-caret-down"></i>
                                <select id="beneficiariesSupCategories" name="beneficiariesSupCategories">
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

                        {{-- نوع جهة العمل (7/8) --}}
                        <div id="workCategoryBlock" class="form-group" style="display:none;">
                            <label for="work_category_id">نوع جهة العمل</label>
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

                        {{-- جهة العمل --}}
                        <div id="institutionBlock" class="form-group" style="display:none;">
                            <label for="institution_id">جهة العمل</label>
                            <div class="input-icon">
                                <i class="fa fa-building"></i>
                                <select id="institution_id" name="institution_id" class="form-control select2">
                                    <option value="">اختر جهة العمل...</option>
                                    <option value="__new__">+ إضافة جهة عمل جديدة</option>
                                    @foreach ($institucions as $inst)
                                        <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('institution_id')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>


                        {{-- زر الإرسال الوحيد (CRA + الشيت) --}}
                        <div class="actions" style="margin-top:14px;">
                            <button id="btnSubmit" class="btn" type="submit">تأكيد</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- SweetAlert2 --}}
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            {{-- إظهار أخطاء Laravel --}}
            @if ($errors->any())
                <script>
                    Swal.fire({
                        icon: 'error',
                        html: `
                    <ul style="text-align:center; list-style:none; padding:0; margin:0;">
                        @foreach ($errors->all() as $error)
                            <li style="margin:5px 0;">{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#F58220'
                    });
                </script>
            @endif

            <script>
                // ----------------------------
                // أرقام فقط
                // ----------------------------
                function onlyNumberKey(e) {
                    const c = e.which ? e.which : e.keyCode;
                    const ok = [8, 9, 37, 39, 46];
                    if (ok.includes(c)) return true;
                    return !(c < 48 || c > 57);
                }
            </script>

            <script>
                // ----------------------------
                // منطق إظهار/إخفاء الرقم الوطني للزوجة
                // ----------------------------
                document.addEventListener('DOMContentLoaded', () => {
                    const typeSelect = document.getElementById('subscriber_type');
                    const spouseBlock = document.getElementById('spouse-nationalid-block');
                    const spouseInput = document.getElementById('spouse_national_id');

                    function toggleSpouseField() {
                        if (typeSelect.value === 'wife') {
                            spouseBlock.style.display = '';
                            spouseInput.setAttribute('required', true);
                        } else {
                            spouseBlock.style.display = 'none';
                            spouseInput.removeAttribute('required');
                            spouseInput.value = '';
                        }
                    }
                    typeSelect.addEventListener('change', toggleSpouseField);
                    toggleSpouseField(); // تشغيل أولي عند تحميل الصفحة
                });
            </script>

            <script>
                // ----------------------------
                // إرسال رمز التحقق OTP
                // ----------------------------
                function sendotp() {
                    const phone = document.getElementById('phone').value;
                    fetch("{{ route('customers.send-otp') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                phone
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('otp-block').style.display = 'block';
                                Swal.fire({
                                    title: "تم الإرسال ✅",
                                    text: "تم إرسال رمز التحقق إلى رقمك. صالح لمدة دقيقة واحدة.",
                                    icon: "success",
                                    confirmButtonText: "حسنًا",
                                    confirmButtonColor: "#F58220"
                                });
                                startOtpTimer();
                            } else {
                                Swal.fire({
                                    title: "خطأ!",
                                    text: data.message || "حدث خطأ أثناء إرسال الرمز.",
                                    icon: "error",
                                    confirmButtonText: "حسنًا",
                                    confirmButtonColor: "#F58220"
                                });
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                title: "⚠️ خطأ في الاتصال",
                                text: "تعذر الاتصال بالسيرفر، حاول لاحقًا.",
                                icon: "warning",
                                confirmButtonText: "موافق",
                                confirmButtonColor: "#F58220"
                            });
                        });
                }

                function startOtpTimer() {
                    const help = document.querySelector('#otp-block .help');
                    let seconds = 60;
                    const interval = setInterval(() => {
                        seconds--;
                        help.textContent = `رمز التحقق صالح لمدة ${seconds} ثانية`;
                        if (seconds <= 0) {
                            clearInterval(interval);
                            help.textContent = "انتهت صلاحية الرمز، يرجى إعادة الإرسال.";
                        }
                    }, 1000);
                }
            </script>

            <script>
                // ----------------------------
                // منطق الفئات 7/8 + أنواع العمل + جهة العمل
                // ----------------------------
                const INSTITUCIONS = @json($institucions);

                document.addEventListener('DOMContentLoaded', () => {
                    const catSelect = document.getElementById('beneficiariesSupCategories');
                    const wcBlock = document.getElementById('workCategoryBlock');
                    const wcSelect = document.getElementById('work_category_id');
                    const instBlock = document.getElementById('institutionBlock');
                    const instSelect = document.getElementById('institution_id');

                    const needsWorkCat = id => id === '7' || id === '8';
                    const ALLOWED_BY_CAT = {
                        '7': new Set(['19', '20']),
                        '8': new Set(['21'])
                    };

                    // فلترة أنواع جهة العمل حسب الفئة
                    function filterWorkCategories() {
                        const cat = catSelect.value || '';
                        const allowed = ALLOWED_BY_CAT[cat];
                        Array.from(wcSelect.options).forEach(opt => {
                            if (!opt.value) return opt.style.display = '';
                            opt.style.display = !allowed ? '' : (allowed.has(String(opt.value)) ? '' : 'none');
                        });
                        const hiddenSelected = wcSelect.selectedOptions[0] && wcSelect.selectedOptions[0].style.display ===
                            'none';
                        if (hiddenSelected) {
                            wcSelect.value = '';
                            instSelect.innerHTML = '<option value="">اختر جهة العمل...</option>';
                            wcSelect.dispatchEvent(new Event('change'));
                        }
                    }

                    function lockIfSingle() {
                        const cat = catSelect.value || '';
                        const allowed = ALLOWED_BY_CAT[cat];
                        if (allowed && allowed.size === 1) {
                            const only = [...allowed][0];
                            wcSelect.value = only;
                            wcSelect.disabled = true;
                            wcSelect.dispatchEvent(new Event('change'));
                        } else {
                            wcSelect.disabled = false;
                        }
                    }

                    catSelect.addEventListener('change', () => {
                        const show = needsWorkCat(catSelect.value || '');
                        wcBlock.style.display = show ? '' : 'none';
                        instBlock.style.display = show ? '' : 'none';
                        wcSelect.toggleAttribute('required', show);
                        instSelect.toggleAttribute('required', show);
                        if (!show) {
                            wcSelect.value = '';
                            wcSelect.disabled = false;
                            instSelect.innerHTML = '<option value="">اختر جهة العمل...</option>';
                        }
                        filterWorkCategories();
                        lockIfSingle();
                    });

                    wcSelect.addEventListener('change', () => {
                        const wcId = wcSelect.value || '';
                        if (!wcId) {
                            instSelect.innerHTML = '<option value="">اختر جهة العمل...</option>';
                            return;
                        }
                        const list = INSTITUCIONS.filter(x => String(x.work_categories_id) === String(wcId));
                        const opts = ['<option value="">اختر جهة العمل...</option>'];
                        for (const it of list) {
                            opts.push(`<option value="${it.id}">${it.name ?? it.title ?? ''}</option>`);
                        }
                        opts.push('<option value="__new__">+ إضافة جهة عمل جديدة</option>');
                        instSelect.innerHTML = opts.join('');
                    });
                    // ----------------------------
                    // ✅ نافذة إضافة جهة عمل جديدة (متوافقة مع Select2 + تصميم PHIF)
                    // ----------------------------
                    $('#institution_id').on('select2:select', function(e) {
                        const val = e.params.data.id;
                        if (val === '__new__') {
                            Swal.fire({
                                title: 'إضافة جهة عمل جديدة',
                                html: `
                                <div dir="rtl" style="text-align:right; font-family:'Tajawal', sans-serif;">
                                    <label style="font-weight:600; color:#8C5346;">اسم الجهة</label>
                                    <input id="new_inst_name" type="text" class="swal2-input" placeholder="أدخل اسم الجهة"
                                        style="border-radius:10px; border:1px solid #ddd; direction:rtl; text-align:right;">
                
                                    <label style="font-weight:600; color:#8C5346;">نوع جهة العمل</label>
                                    <select id="new_inst_wc" class="swal2-select" style="
                                        width:250px; border-radius:10px; border:1px solid #ddd;
                                        font-family:'Tajawal'; direction:rtl; text-align:right;">
                                        <option value="">— اختر النوع —</option>
                                        @foreach ($workCategories as $wc)
                                            <option value="{{ $wc->id }}">{{ $wc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            `,
                                background: '#fff',
                                showCancelButton: true,
                                confirmButtonText: '💾 حفظ',
                                cancelButtonText: 'إلغاء',
                                confirmButtonColor: '#F58220',
                                cancelButtonColor: '#ccc',
                                customClass: {
                                    popup: 'phif-popup'
                                },
                                preConfirm: () => {
                                    const name = document.getElementById('new_inst_name').value.trim();
                                    const wc = document.getElementById('new_inst_wc').value;
                                    if (!name || !wc) {
                                        Swal.showValidationMessage(
                                            'الرجاء إدخال الاسم واختيار نوع الجهة');
                                        return false;
                                    }
                                    return {
                                        name,
                                        wc
                                    };
                                }
                            }).then(result => {
                                if (result.isConfirmed) {
                                    fetch("{{ route('institucion.storefromsubscriberview') }}", {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/json",
                                                "Accept": "application/json",
                                                "X-CSRF-TOKEN": document.querySelector(
                                                    'meta[name="csrf-token"]').getAttribute(
                                                    'content')
                                            },
                                            credentials: "same-origin", // ✅ يسمح بإرسال الكوكيز الخاصة بالـ session
                                            body: JSON.stringify({
                                                name: result.value.name,
                                                work_categories_id: result.value.wc
                                            })
                                        })

                                        .then(res => res.json())
                                        .then(data => {
                                            if (data.id) {
                                                const newOption = new Option(data.name, data.id, true,
                                                    true);
                                                $('#institution_id').append(newOption).trigger(
                                                'change');
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'تم الحفظ بنجاح ✅',
                                                    text: 'تمت إضافة جهة العمل الجديدة بنجاح.',
                                                    confirmButtonText: 'حسناً',
                                                    confirmButtonColor: '#F58220'
                                                });
                                            } else {
                                                Swal.fire('خطأ', data.message || 'تعذر حفظ الجهة',
                                                    'error');
                                                $('#institution_id').val(null).trigger('change');
                                            }
                                        })
                                        .catch(() => {
                                            Swal.fire('خطأ', 'تعذر الاتصال بالسيرفر', 'error');
                                            $('#institution_id').val(null).trigger('change');
                                        });
                                } else {
                                    $('#institution_id').val(null).trigger('change');
                                }
                            });
                        }
                    });

                    // ----------------------------
                    // استرجاع old inputs
                    // ----------------------------
                    const oldCat = "{{ old('beneficiariesSupCategories') }}";
                    const show = needsWorkCat(oldCat);
                    wcBlock.style.display = show ? '' : 'none';
                    instBlock.style.display = show ? '' : 'none';
                    wcSelect.toggleAttribute('required', show);
                    instSelect.toggleAttribute('required', show);
                    filterWorkCategories();
                    lockIfSingle();

                    const oldWc = "{{ old('work_category_id') }}";
                    if (show && oldWc) {
                        wcSelect.value = oldWc;
                        wcSelect.dispatchEvent(new Event('change'));
                        const list = INSTITUCIONS.filter(x => String(x.work_categories_id) === String(oldWc));
                        const opts = ['<option value="">اختر جهة العمل...</option>'];
                        for (const it of list) {
                            opts.push(`<option value="${it.id}">${it.name ?? it.title ?? ''}</option>`);
                        }
                        opts.push('<option value="__new__">+ إضافة جهة عمل جديدة</option>');
                        instSelect.innerHTML = opts.join('');
                        const oldInst = "{{ old('institution_id') }}";
                        if (oldInst) instSelect.value = oldInst;
                    }
                });
            </script>

            <script>
                // ----------------------------
                // تهيئة Select2
                // ----------------------------
                $(document).ready(function() {
                    $('#institution_id').select2({
                        placeholder: "اختر جهة العمل...",
                        allowClear: true,
                        width: '100%'
                    });
                });
            </script>

</body>

</html>
