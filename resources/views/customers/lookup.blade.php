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

            .cardx-body {
                padding: 16px
            }

            .btn {
                all: unset;
                display: block;
                width: 100%;
                height: 52px;
                line-height: 52px;
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
                background: var(--brand-700)
            }

            .alert-slim {
                border-radius: 12px;
                padding: .75rem 1rem;
                margin-bottom: 12px
            }
        </style>

        {{-- عنوان الصفحة --}}
        <div class="mb-3 text-center">
            <h3 class="page-title">بحث عن مشترك / منتفع</h3>
            <div class="page-sub">املأ خانة واحدة فقط (الوطني أو التأميني)</div>
        </div>

        <div id="alert-box"></div>

        {{-- نموذج البحث --}}
        <div class="cardx mb-4">
            <div class="cardx-body">
                <style>
                    #lookup-form .form-label {
                        font-weight: 700;
                        color: var(--muted);
                        margin-bottom: 6px;
                        font-size: 1rem
                    }

                    #lookup-form .form-control {
                        height: 56px;
                        font-size: 1rem;
                        padding: .8rem 1rem;
                        border-radius: 12px
                    }

                    #lookup-form .form-control::placeholder {
                        color: #9ca3af;
                        opacity: 0.8
                    }
                </style>

                <form id="lookup-form" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">الرقم الوطني</label>
                        <input type="text" name="nationalID" class="form-control" placeholder="مثال: 12 رقم">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الرقم التأميني</label>
                        <input type="text" name="regnumber" class="form-control" placeholder="مثال: 13 رقم">
                    </div>

                    <button type="button" class="btn" id="submitBtn">
                        <span class="btn-text">بحث</span>
                        <span class="btn-spin d-none" style="margin-inline-start:8px">⏳</span>
                    </button>

                </form>
            </div>
        </div>

        {{-- النتيجة --}}
        <div id="lookupresult"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('lookup-form');
            const resultBox = document.getElementById('lookup-result');
            const alertBox = document.getElementById('alert-box');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnSpin = submitBtn.querySelector('.btn-spin');

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

            submitBtn.addEventListener('click', function() {
                resultBox.innerHTML = '';

                const payload = {
                    nationalID: form.nationalID.value.trim(),
                    regnumber: form.regnumber.value.trim()
                };

                if (!payload.nationalID && !payload.regnumber) {
                    showInfo('warning', 'الرجاء إدخال الرقم الوطني أو التأميني.');
                    return;
                }
                if (payload.nationalID && payload.regnumber) {
                    showInfo('warning', 'يرجى تعبئة خانة واحدة فقط.');
                    return;
                }

                startLoading();
                fetch("{{ route('customers.get') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(r => r.json())
                    .then(data => {
                        stopLoading();
                        if (data.status === 'error') {
                            showInfo('danger', data.message ||
                                'لم يتم العثور على مشترك بهذه البيانات.');
                            return;
                        }
                        resultBox.innerHTML = data.html;
                    })
                    .catch(err => {
                        stopLoading();
                        showInfo('danger', 'خطأ في الاتصال: ' + err);
                    });
            });
        });
    </script>

@endsection
