@extends('layouts.master')
@section('title', 'بحث عن مشترك / منتفع')

@section('content')
    <div class="container py-4" style="direction:rtl">
        {{-- ============ Style ============ --}}
        <style>
            :root {
                --ink: #1F2328;
                --muted: #6b7280;
                --line: #E5E7EB;
                --brand: #F58220;
                --brand-700: #d95b00;
                --brown: #8C5346;
                --bg-1: #FFF7EE;
                --card-shadow: 0 10px 24px rgba(0, 0, 0, .06);
                --radius: 16px;
                --gap: 14px;
            }

            .page-title {
                margin: 0;
                color: var(--brown);
                font-weight: 800
            }

            .page-sub {
                color: var(--muted);
                font-size: .92rem
            }

            .cardx {
                border: 0;
                border-radius: var(--radius);
                box-shadow: var(--card-shadow);
                background: #fff
            }

            .cardx-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 14px 16px;
                border-bottom: 1px solid var(--line);
                border-radius: var(--radius) var(--radius) 0 0;
                background: linear-gradient(180deg, #fff, #fff)
            }

            .cardx-header .title {
                color: var(--brown);
                font-weight: 800
            }

            .cardx-body {
                padding: 16px
            }

            .btn-brand {
                background: var(--brand);
                color: #fff;
                font-weight: 700;
                border-radius: 10px;
                padding: .6rem 1.2rem;
                border: none
            }

            .btn {
                all: unset;
                display: block;
                /* يخلي الزر ياخذ عرض كامل */
                width: 100%;
                /* يمتد عرض الفورم */
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




            .btn-brand:disabled {
                opacity: .6
            }

            .btn-brand:hover {
                background: var(--brand-700)
            }

            .grid {
                display: grid;
                grid-template-columns: repeat(12, 1fr);
                gap: var(--gap)
            }

            .col-4 {
                grid-column: span 4
            }

            .col-6 {
                grid-column: span 6
            }

            .col-12 {
                grid-column: span 12
            }

            @media (max-width: 992px) {

                .col-4,
                .col-6 {
                    grid-column: span 12
                }
            }

            .info-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: var(--gap)
            }

            .info-item {
                background: #fff;
                border: 1px solid var(--line);
                border-radius: 12px;
                padding: 12px;
                min-height: 72px;
                display: flex;
                flex-direction: column;
                justify-content: center
            }

            .info-item label {
                color: var(--muted);
                font-size: .85rem;
                margin-bottom: 6px
            }

            .info-item .val {
                color: var(--ink);
                font-weight: 800;
                word-break: break-word
            }

            .alert-slim {
                border-radius: 12px;
                padding: .75rem 1rem;
                margin-bottom: 12px
            }

            .sub-card {
                border: 0;
                border-radius: var(--radius);
                box-shadow: var(--card-shadow);
                overflow: hidden
            }

            .sub-head {
                background: linear-gradient(90deg, var(--brand), var(--brand-700));
                color: #fff;
                font-weight: 800;
                padding: 14px 16px
            }

            .sub-body {
                padding: 16px
            }

            .sub-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                border: 1px solid var(--line);
                border-radius: 12px;
                overflow: hidden
            }

            .sub-table th {
                background: linear-gradient(180deg, #FFF7EE, #FCE8D6);
                padding: 10px;
                text-align: right;
                font-weight: 800;
                border-bottom: 1px solid var(--line)
            }

            .sub-table td {
                padding: 10px;
                border-top: 1px solid var(--line)
            }

            @media print {
                .no-print {
                    display: none !important
                }

                .info-item {
                    border: 0
                }

                .cardx,
                .sub-card {
                    box-shadow: none;
                    border: 1px solid #ccc
                }
            }
        </style>

        {{-- عنوان الصفحة --}}
        <div class="mb-3 text-center">
            <h3 class="page-title"> بحث عن مشترك / منتفع</h3>
            <div class="page-sub" style="color:#9ca3af;">املأ خانة واحدة فقط (الوطني أو التأميني أو الهاتف)</div>
        </div>

        <div id="alert-box"></div>

        {{-- نموذج البحث --}}
        <div class="cardx mb-4">
            <div class="cardx-body">
                <style>
                    #search-form .form-label {
                        font-weight: 700;
                        color: var(--muted);
                        margin-bottom: 6px;
                        font-size: 1rem;
                    }

                    #search-form .form-control {
                        height: 56px;
                        font-size: 1rem;
                        padding: .8rem 1rem;
                        border-radius: 12px;
                    }

                    #search-form .form-control::placeholder {
                        color: #9ca3af;
                        opacity: 0.8;
                    }

                    #search-form .btn-brand {
                        height: 56px;
                        font-size: 1rem;
                        font-weight: 700;
                        border-radius: 12px;
                        width: 100%;
                    }
                </style>

                <form id="search-form" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">الرقم الوطني</label>
                        <input type="text" name="nationalID" class="form-control" placeholder="مثال: 1XXXXXXXXXXXXXX">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الرقم التأميني</label>
                        <input type="text" name="regnumber" class="form-control" placeholder="مثال: 13 رقم">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control" placeholder="09XXXXXXXX">
                    </div>

                    {{-- زر البحث في المنتصف --}}
                    <div class="col-12 mt-3 d-flex justify-content-center">
                        <button type="submit" class="btn" id="submitBtn">
                            <span class="btn-text">بحث</span>
                            <span class="btn-spin d-none" style="margin-inline-start:8px">⏳</span>
                        </button>

                    </div>
                </form>
            </div>
        </div>

        {{-- النتيجة --}}
        <div id="search-result"></div>
    </div>

    {{-- ============ Script ============ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('search-form');
            const resultBox = document.getElementById('search-result');
            const alertBox = document.getElementById('alert-box');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnSpin = submitBtn.querySelector('.btn-spin');

            const inputs = ['nationalID', 'regnumber', 'phone'].map(n => form.querySelector(`[name="${n}"]`));
            inputs.forEach(inp => {
                inp.addEventListener('input', () => {
                    const filled = inputs.filter(i => i.value.trim().length > 0);
                    inputs.forEach(i => i.disabled = (filled.length && i !== inp));
                    if (!inp.value.trim().length) inputs.forEach(i => i.disabled = false);
                });
            });

            function showInfo(type, msg) {
                const map = {
                    info: 'alert-info',
                    danger: 'alert-danger',
                    success: 'alert-success',
                    warning: 'alert-warning'
                };
                alertBox.innerHTML = `<div class="alert ${map[type]} alert-slim">${msg}</div>`;
            }

            function startLoading() {
                submitBtn.disabled = true;
                btnText.classList.add('d-none');
                btnSpin.classList.remove('d-none');
                showInfo('info', '⏳ جاري البحث...');
            }

            function stopLoading() {
                submitBtn.disabled = false;
                btnText.classList.remove('d-none');
                btnSpin.classList.add('d-none');
                alertBox.innerHTML = '';
            }

            function normalizePhone(p) {
                if (!p) return '';
                p = String(p);
                p = p.replace(/\D+/g, ''); // أرقام فقط
                p = p.replace(/^(00218|218)/, ''); // شيل مقدمة الدولة
                p = p.replace(/^0/, ''); // شيل صفر البداية
                return p;
            }

            // فالديشن حسب القواعد المتفق عليها
            function validateOneField(dataObj) {
                const filled = Object.entries(dataObj).filter(([_, v]) => String(v || '').trim().length > 0);
                if (filled.length === 0) return {
                    ok: false,
                    msg: 'الرجاء إدخال رقم وطني أو تأميني أو هاتف.'
                };
                if (filled.length > 1) return {
                    ok: false,
                    msg: 'يرجى تعبئة خانة واحدة فقط.'
                };

                const [field, rawVal] = filled[0];
                let val = String(rawVal).trim();

                if (field === 'nationalID') {
                    if (!/^\d{12}$/.test(val)) return {
                        ok: false,
                        msg: 'الرقم الوطني يجب أن يتكون من 12 رقمًا.'
                    };
                } else if (field === 'regnumber') {
                    if (!/^\d{13}$/.test(val)) return {
                        ok: false,
                        msg: 'الرقم التأميني يجب أن يتكون من 13 رقمًا.'
                    };
                } else if (field === 'phone') {
                    val = normalizePhone(val);
                    if (!/^(91|92|93|94)\d{7}$/.test(val)) {
                        return {
                            ok: false,
                            msg: 'رقم الهاتف يجب أن يبدأ بـ 91 أو 92 أو 93 أو 94 ويكون 9 أرقام فقط.'
                        };
                    }
                }

                return {
                    ok: true,
                    field,
                    value: (field === 'phone' ? normalizePhone(val) : val)
                };
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                resultBox.innerHTML = '';

                const dataObj = {
                    nationalID: form.nationalID.value,
                    regnumber: form.regnumber.value,
                    phone: form.phone.value,
                };

                // فالديشن عميل مطابق للسيرفر
                const v = validateOneField(dataObj);
                if (!v.ok) {
                    showInfo('warning', v.msg);
                    return;
                }

                const payload = {
                    nationalID: null,
                    regnumber: null,
                    phone: null
                };
                payload[v.field] = v.value;

                startLoading();
                fetch("{{ route('search.customers') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(data => {
                        stopLoading();

                        if (data.status === 'error') {
                            showInfo('danger', data.message ||
                                'لم يتم العثور على مشترك بهذه البيانات.');
                            return;
                        }

                        const c = data.customer || {};
                        const val = (v) => (v === null || v === undefined || String(v).trim() === '') ?
                            '—' : v;

                        const optional = [];
                        if (c.institucion?.name) optional.push(
                            `<div class="info-item"><label>جهة العمل</label><div class="val">${c.institucion.name}</div></div>`
                        );

                        const bankName = c.bank?.name,
                            branchName = c.bank_branch?.name;
                        if (bankName?.trim()) {
                            let txt = bankName;
                            if (branchName?.trim()) txt += ` (${branchName})`;
                            optional.push(
                                `<div class="info-item"><label>المصرف</label><div class="val">${txt}</div></div>`
                            );
                        }

                        if (String(c.iban || '').trim()) optional.push(
                            `<div class="info-item"><label>IBAN</label><div class="val" style="direction:ltr;display:inline-block">${c.iban}</div></div>`
                        );
                        if (c.total_pension !== null && String(c.total_pension).trim() !== '') optional
                            .push(
                                `<div class="info-item"><label>إجمالي المرتب</label><div class="val">${c.total_pension}</div></div>`
                            );
                        if (String(c.pension_no || '').trim()) optional.push(
                            `<div class="info-item"><label>رقم المضمون</label><div class="val">${c.pension_no}</div></div>`
                        );
                        if (String(c.account_no || '').trim()) optional.push(
                            `<div class="info-item"><label>رقم الحساب</label><div class="val">${c.account_no}</div></div>`
                        );
                        if (String(c.insured_no || '').trim()) optional.push(
                            `<div class="info-item"><label>رقم المعاش </label><div class="val">${c.insured_no}</div></div>`
                        );


                        // كارت الاشتراك

                        let subscriptionCard = '';
                        @role('insurance-manager')
                            if (c.subscription && (c.subscription.id || c.subscription.name || (c
                                    .subscription.values?.length))) {
                                const s = c.subscription;
                                subscriptionCard = `
                        <div class="sub-card" style="margin-top:18px">
                            <div class="sub-head">بيانات الاشتراك</div>
                            <div class="sub-body">
                                <div class="info-grid" style="margin-bottom:14px">
                                    <div class="info-item"><label>الاسم</label><div class="val">${val(s.name)}</div></div>
                                    <div class="info-item"><label>الفئة</label><div class="val">${val(s.beneficiaries_category?.name)}</div></div>
                                </div>
                                <h6 style="font-weight:800;color:var(--ink);margin-bottom:8px"> نسب الاشتراك</h6>
                                ${
                                    Array.isArray(s.values) && s.values.length
                                    ? `<table class="sub-table">
                                                      <thead><tr><th>النوع</th><th>القيمة</th></tr></thead>
                                                      <tbody>
                                                        ${s.values.map(v => `
                                                <tr>
                                                    <td>${val(v.type?.name)}</td>
                                                    <td>${v.is_percentage ? val(v.value)+'%' : val(v.value)+' دينار'}</td>
                                                </tr>
                                            `).join('')}
                                                      </tbody>
                                                   </table>`
                                    : `<p style="color:var(--muted);margin:0">لا توجد قيم مسجلة</p>`
                                }
                            </div>
                        </div>
                    `;
                            }
                        @endrole

                        // بطاقة بيانات المشترك
                        const html = `
                    <div class="cardx" id="customer-card">
                        <div class="cardx-header">
                            <div class="title">بيانات المشترك</div>
                            <div style="display:inline-flex; align-items:center; gap:6px;">
                                <a href="/customers/${c.id}/print-one" target="_blank" class="btn-brand no-print" style="padding:.45rem .9rem">طباعة بيانات</a>
                            </div>
                        </div>
                        <div class="cardx-body">
                            <div class="info-grid">
                                <div class="info-item"><label>رقم التأميني</label><div class="val">${val(c.regnumber)}</div></div>
                                <div class="info-item"><label>الاسم</label><div class="val">${val(c.fullnamea)}</div></div>
                                <div class="info-item"><label>الرقم الوطني</label><div class="val">${val(c.nationalID)}</div></div>
                                <div class="info-item"><label>الهاتف</label><div class="val">${val(c.phone)}</div></div>
                                <div class="info-item"><label>البريد</label><div class="val">${val(c.email)}</div></div>
                                <div class="info-item"><label>الجنس</label><div class="val">${c.gender == 1 ? 'ذكر' : (c.gender == 2 ? 'أنثى' : '—')}</div></div>
                                <div class="info-item"><label>اقرب نقطة</label><div class="val">${val(c.nearestpoint)}</div></div>
                                <div class="info-item"><label>تاريخ الميلاد</label><div class="val">${val(c.yearbitrh)}</div></div>
                                <div class="info-item"><label>رقم الجواز</label><div class="val">${val(c.passportnumber)}</div></div>
                                <div class="info-item"><label>الفئة الرئيسية</label><div class="val">${val(c.beneficiaries_category_relation?.name)}</div></div>
                                <div class="info-item"><label>الفئة الفرعية</label><div class="val">${val(c.beneficiaries_sup_category_relation?.name)}</div></div>
                                <div class="info-item"><label>الحالة الاجتماعية</label><div class="val">${val(c.socialstatuses?.name)}</div></div>
                                <div class="info-item"><label>فصيلة الدم</label><div class="val">${val(c.bloodtypes?.name)}</div></div>
                                <div class="info-item"><label>البلدية</label><div class="val">${val(c.municipals?.name)}</div></div>
                                <div class="info-item"><label>المنطقة الصحية</label><div class="val">${val(c.cities?.name)}</div></div>
                                ${optional.join('')}
                            </div>
                        </div>
                    </div>
                    ${subscriptionCard}
                `;
                        resultBox.innerHTML = html;
                    })
                    .catch(err => {
                        stopLoading();
                        showInfo('danger', `خطأ في الاتصال: ${err}`);
                    });
            });
        });

        // طباعة بطاقة المشترك فقط
        function printCustomer() {
            const card = document.getElementById('customer-card').innerHTML;
            const w = window.open('', '', 'width=900,height=650');
            w.document.write(`
            <html>
                <head>
                    <title>طباعة بيانات المشترك</title>
                    <style>
                        body { font-family: 'Tajawal', sans-serif; direction: rtl }
                        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px }
                        .info-item { border: 1px solid #ddd; border-radius: 10px; padding: 10px; min-height: 66px }
                        .info-item label { color: #6b7280; font-size:.85rem; margin-bottom:6px; display:block }
                        .info-item .val { font-weight:800; color:#1F2328 }
                        h3 { color: #8C5346; text-align:center; margin-bottom:12px }
                    </style>
                </head>
                <body>
                    <h3>بيانات المشترك</h3>
                    ${card}
                </body>
            </html>
        `);
            w.document.close();
            w.focus();
            w.print();
            w.close();
        }
    </script>
@endsection
