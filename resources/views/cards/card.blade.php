@extends('layouts.master')
@section('title', 'التقاط / طباعة البطاقة')

@section('content')
<style>
    :root {
        --brand: #F58220;
        --brand-dark: #d96b00;
        --muted: #6b7280;
        --ink: #1f2937;
        --panel: #fff;
        --bg: #FFF9F3;
        --border: #E5E7EB;
        --shadow: 0 10px 30px rgba(17, 24, 39, 0.08);
    }

    body {
        font-family: 'Tajawal', sans-serif;
        background: var(--bg);
        color: var(--ink);
    }

    .card {
        background: var(--panel);
        border-radius: 22px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: box-shadow .25s ease;
    }

    .card:hover {
        box-shadow: 0 10px 40px rgba(0, 0, 0, .09);
    }

    .card-head {
        background: linear-gradient(90deg, var(--brand-dark), var(--brand));
        padding: 16px;
        font-weight: 800;
        font-size: 1.15rem;
        color: #fff;
        text-align: center;
        letter-spacing: .3px;
    }

    .card-body {
        padding: 28px;
        text-align: center;
    }

    .photo-box {
        border: 2px dashed var(--brand);
        border-radius: 16px;
        background: #fff;
        padding: 10px;
        min-height: 240px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: border-color .2s ease;
    }

    .photo-box:hover {
        border-color: var(--brand-dark);
    }

    .photo-box img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        object-fit: cover;
    }

    .card-info {
        text-align: right;
        background: #fdfdfd;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        padding: 16px;
        font-size: .98rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
    }

    .card-info div {
        margin-bottom: 6px;
    }

    .card-info b {
        color: var(--brand);
        font-weight: 700;
        margin-left: 6px;
    }

    .actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1.8rem;
    }

    .btn {
        all: unset;
        cursor: pointer;
        background: var(--brand);
        color: #fff;
        font-weight: 800;
        border-radius: 999px;
        padding: .9rem 2.4rem;
        font-size: 1rem;
        box-shadow: 0 8px 22px rgba(245, 130, 32, 0.25);
        transition: background .2s, transform .15s;
    }

    .btn:hover {
        background: var(--brand-dark);
        transform: translateY(-2px);
    }

    .row.g-4 {
        row-gap: 1.5rem;
    }

    .section-divider {
        height: 1px;
        background: #eee;
        margin: 25px 0;
    }

    .page-wrapper {
        max-width: 850px;
        margin: 0 auto;
        padding: 30px 10px;
    }

    .page-title {
        text-align: center;
        color: var(--brand);
        font-weight: 800;
        font-size: 1.8rem;
        margin-bottom: 10px;
    }

    .page-sub {
        color: var(--muted);
        text-align: center;
        font-size: 1rem;
        margin-bottom: 30px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}" />

<div class="page-wrapper">
    <h3 class="page-title">التقاط صورة البطاقة</h3>
    <p class="page-sub">يرجى التأكد من وضوح الصورة قبل حفظها أو طباعتها.</p>

    <div class="card">
        <div class="card-head">الكاميرا</div>
        <div class="card-body">
            <div id="my_camera" class="photo-box" style="max-width:400px; margin:auto;"></div>

            <div class="actions mt-3">
                <button type="button" onClick="take_snapshot()" class="btn"> التقاط صورة</button>
            </div>

            <div class="row g-4 mt-4 align-items-start">
                <div class="col-md-6">
                    <div id="gift" class="photo-box">
                        @if (isset($pers->image))
                            <img id="selfImg"
                                style="width: 120px; height: 120px; border-radius: 8px; object-fit: cover;"
                                src="https://his.phif.gov.ly/photo/personalphotos/{{ $pers->image . '.jpeg' }}" />
                        @else
                            <span style="color: var(--muted);">لم يتم التقاط صورة بعد</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card-info">
                        <div><b>الاسم الكامل:</b> {{ $customer->fullnamea }}</div>
                        <div><b>الرقم الوطني:</b> {{ $customer->nationalID }}</div>
                        <div><b>تاريخ الميلاد:</b> {{ $customer->yearbitrh }}</div>
                        <div><b>الفئة:</b> {{ $beneficiary->name }}</div>
                        <div><b>البلدية:</b> {{ $customer->municipals->name }}</div>
                        <div><b>رقم القيد:</b> {{ $customer->registrationnumbers }}</div>
                        <div><b>رقم التأمين:</b> {{ $customer->regnumber }}</div>
                    </div>
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="actions">
                <button type="button" onclick="storeCard('gift', {{ $customer->id }})"
                    class="btn">
                    حفظ الصورة
                </button>
            </div>

            <input type="hidden" id="img" name="img" value="">
        </div>
    </div>
</div>

<script>
var img;

Webcam.set({
    width: 700,
    height: 380,
    image_format: 'jpeg',
    jpeg_quality: 1080
});
Webcam.attach('#my_camera');

function take_snapshot() {
    Webcam.snap(function(data_uri) {
        document.getElementById("img").value = data_uri;
        document.getElementById('gift').innerHTML =
            '<img id="selfImg" style="border-radius: 8px; object-fit: cover; max-width:100%;" src="' +
            data_uri + '"/>';
    });
}

function storeCard(el, id) {
    const image = document.getElementById('img').value;

    if (image.length < 1) {
        Swal.fire({
            icon: "error",
            timer: 5000,
            text: "الرجاء التقاط الصورة أولاً",
            confirmButtonText: 'موافق'
        });
        return;
    }

    $.ajax({
        url: "{!! route('cards/store', ['id' => $customer->id]) !!}",
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            selfImg: image,
        },
        success: function() {
            Swal.fire({
                icon: "success",
                timer: 3000,
                text: "تم الحفظ بنجاح ✅",
                confirmButtonText: 'موافق',
            }).then(() => {
                window.location.href = "{{ route('home') }}";
            });
        },
        error: function() {
            Swal.fire({
                icon: "error",
                timer: 4000,
                text: "فشل عملية الحفظ ❌",
                confirmButtonText: 'موافق'
            });
        }
    });
}
</script>
@endsection
