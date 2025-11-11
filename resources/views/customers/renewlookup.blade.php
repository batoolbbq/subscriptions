@extends('layouts.master')
@section('title', 'تجديد البطاقة')

@section('content')
<div class="container py-5" style="direction: rtl; max-width: 750px;">

    {{-- ========== CSS ========== --}}
    <style>
        :root {
            --brand: #F58220;
            --brand-dark: #d96b00;
            --bg: #fffdf9;
            --muted: #6b7280;
            --ink: #1f2937;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--bg);
            color: var(--ink);
        }

        .page-title {
            color: var(--brand);
            font-weight: 800;
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: .25rem;
        }

        .page-sub {
            text-align: center;
            color: var(--muted);
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        .cardx {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .05);
            background: #fff;
            padding: 1.8rem 1.5rem;
            transition: box-shadow .2s;
        }

        .cardx:hover {
            box-shadow: 0 10px 28px rgba(0, 0, 0, .08);
        }

        .form-label {
            font-weight: 700;
            color: var(--muted);
            margin-bottom: .4rem;
        }

        input.form-control {
            height: 52px;
            font-size: 1rem;
            border-radius: 12px;
            border: 1px solid #ddd;
            transition: border-color .2s, box-shadow .2s;
        }

        input.form-control:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(245, 130, 32, .15);
        }

        .btn-brand {
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: 999px;
            font-weight: 800;
            font-size: 1.05rem;
            padding: 0.9rem 2.4rem;
            transition: background .2s, transform .15s;
            box-shadow: 0 8px 20px rgba(245, 130, 32, .25);
        }

        .btn-brand:hover {
            background: var(--brand-dark);
            transform: translateY(-2px);
        }

        #alert-box .alert {
            border-radius: 14px;
            font-weight: 600;
            padding: .9rem 1.2rem;
            margin-bottom: 1.2rem;
        }

        #result-box {
            margin-top: 1.5rem;
        }

        .otp-input {
            max-width: 180px;
            text-align: center;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .otp-card label {
            font-weight: 700;
            color: var(--muted);
            display: block;
            margin-bottom: .5rem;
        }
    </style>

    {{-- ========== HEADER ========== --}}
    <h3 class="page-title">تجديد البطاقة</h3>
    <p class="page-sub">الرجاء إدخال الرقم الوطني أو التأميني فقط</p>

    <div id="alert-box"></div>

    {{-- ========== نموذج البحث ========== --}}
    <div class="cardx mb-4">
        <form id="renew-form">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">الرقم الوطني</label>
                    <input type="text" name="nationalID" class="form-control" placeholder="1XXXXXXXXXXXXXX">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الرقم التأميني</label>
                    <input type="text" name="regnumber" class="form-control" placeholder="13 رقم">
                </div>
            </div>

            <div class="mt-5 text-center">
                <button type="submit" class="btn-brand px-5" id="submitBtn">
                    بحث
                </button>
            </div>
        </form>
    </div>

    <div id="result-box"></div>
</div>

{{-- ========== Script ========== --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('renew-form');
    const alertBox = document.getElementById('alert-box');
    const resultBox = document.getElementById('result-box');
    const btn = document.getElementById('submitBtn');

    function showAlert(type, msg) {
        const colors = {
            success: 'alert-success',
            error: 'alert-danger',
            info: 'alert-info',
            warning: 'alert-warning'
        };
        alertBox.innerHTML = `<div class="alert ${colors[type] || 'alert-info'} text-center">${msg}</div>`;
    }

    function clearAlert() {
        alertBox.innerHTML = '';
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearAlert();
        resultBox.innerHTML = '';

        const nat = form.nationalID.value.trim();
        const reg = form.regnumber.value.trim();

        if (!nat && !reg) {
            showAlert('warning', '⚠️ الرجاء إدخال الرقم الوطني أو التأميني');
            return;
        }
        if (nat && reg) {
            showAlert('warning', '⚠️ يرجى تعبئة خانة واحدة فقط');
            return;
        }

        btn.disabled = true;
        btn.textContent = "⏳ جاري البحث...";
        showAlert('info', 'جاري التحقق من البيانات...');

        try {
            const res = await fetch("{{ route('renew.search') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ nationalID: nat, regnumber: reg })
            });

            const data = await res.json();
            btn.disabled = false;
            btn.textContent = "بحث";

            if (data.status === 'error') {
                showAlert('error', data.message || 'حدث خطأ أثناء البحث.');
                return;
            }

            if (data.needOtp) {
                showAlert('success', data.message);

                resultBox.innerHTML = `
                    <div class="cardx otp-card mt-3">
                        <label>📱 أدخل رمز التحقق المرسل إلى الهاتف <b>${data.customer.phone}</b></label>
                        <div class="d-flex justify-content-center align-items-center gap-3 mt-2">
                            <input type="text" id="otpInput" class="form-control otp-input" placeholder="رمز التحقق" maxlength="6">
                            <button id="verifyBtn" class="btn-brand px-4">تأكيد</button>
                        </div>
                    </div>
                `;

                document.getElementById('verifyBtn').onclick = async () => {
                    const otp = document.getElementById('otpInput').value.trim();
                    if (!otp) {
                        showAlert('warning', 'الرجاء إدخال رمز التحقق');
                        return;
                    }

                    const res2 = await fetch("{{ route('renew.verify') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ phone: data.customer.phone, otp })
                    });

                    const verify = await res2.json();
                    if (!verify.success) {
                        showAlert('error', verify.message || 'فشل التحقق');
                    } else {
                        showAlert('success', verify.message);
                        setTimeout(() => {
                            window.location.href = verify.redirect;
                        }, 1500);
                    }
                };
            }

        } catch (err) {
            btn.disabled = false;
            btn.textContent = "بحث";
            showAlert('error', `⚠️ فشل الاتصال: ${err.message}`);
        }
    });
});
</script>
@endsection
