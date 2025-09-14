@extends('layouts.master')
@section('title', 'التقاط / طباعة البطاقة')

@section('content')
    <style>
        :root {
            --brand: #F58220;
            --brand-600: #ff8f34;
            --ink: #111827;
            --muted: #6b7280;
            --border: #E5E7EB;
            --panel: #fff;
            --shadow: 0 10px 28px rgba(17, 24, 39, .07);
        }

        .card {
            background: var(--panel);
            border: 1.5px solid var(--border);
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-head {
            background: linear-gradient(135deg, #d95b00 0%, #F58220 35%, #FF8F34 70%, #ffb066 100%);
            padding: 14px;
            font-weight: 800;
            color: #fff;
            text-align: center;
        }

        .card-body {
            padding: 20px;
            text-align: center;
        }

        .photo-box {
            border: 2px dashed var(--brand);
            border-radius: 12px;
            padding: 8px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 240px;
        }

        .photo-box img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .card-info {
            padding: 14px;
            background: #fafafa;
            border: 1px solid var(--border);
            border-radius: 12px;
            line-height: 1.8;
            font-size: .95rem;
            text-align: right;
        }

        .card-info div {
            margin-bottom: 6px;
        }

        .card-info b {
            color: var(--brand);
            margin-left: 6px;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn {
            all: unset;
            cursor: pointer;
            padding: 0 22px;
            height: 42px;
            line-height: 42px;
            border-radius: 999px;
            background: var(--brand);
            color: #fff;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(245, 130, 32, .25);
            transition: background .2s;
        }

        .btn:hover {
            background: var(--brand-600);
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <div class="card">
        <div class="card-head">
            <i class="fa-solid fa-id-card"></i> التقاط صورة البطاقة
        </div>
        <div class="card-body">
            {{-- الكاميرا --}}
            <div id="my_camera" class="photo-box" style="max-width:400px; margin:auto;"></div>

            {{-- زر التقاط الصورة --}}
            <div class="actions mt-3">
                <button type="button" onClick="take_snapshot()" class="btn">التقاط صورة</button>
            </div>

            {{-- الصورة + البيانات بجانب بعض --}}
            <div class="row g-4 mt-4">
                <div class="col-md-6">
                    <div id="gift" class="photo-box">
                        @if (isset($pers->image))
                            <img id="selfImg"
                                style="width: 108.3px; border-radius: 5px; height: 113.5px; object-fit: cover;"
                                id="img_r"
                                src="https://his.phif.gov.ly/photo/personalphotos/{{ $pers->image . '.jpeg' }}" />
                            <span style="color: var(--muted);">لم يتم التقاط صورة بعد</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-info">
                        <div><b>الاسم الكامل:</b> {{ $customer->fullnamea }}</div>
                        <div><b>رقم الوطني:</b> {{ $customer->nationalID }}</div>
                        <div><b>تاريخ الميلاد:</b> {{ $customer->yearbitrh }}</div>
                        <div><b>الفئة:</b> {{ $beneficiary->name }}</div>
                        <div><b>البلدية:</b> {{ $customer->municipals->name }}</div>
                        <div><b>رقم القيد:</b> {{ $customer->registrationnumbers }}</div>

                        <div><b>رقم التأمين:</b> {{ $customer->regnumber }}</div>
                    </div>
                </div>
            </div>

            {{-- الأزرار --}}
            {{-- الأزرار --}}
            <div class="actions">
                <button type="button" onclick="storeCard('gift', {{ $customer->id }})"
                    class="{{ $pers && $pers->printed == 1 ? 'print__card' : 'show__print' }} btn">
                    حفظ
                </button>
                <button type="button" onclick="printCard('gift', {{ $customer->id }})"
                    class="{{ $pers && $pers->printed == 0 ? 'show__print' : 'print__card' }} btn">
                    طباعة
                </button>
            </div>

            <input type="hidden" id="img" name="img" value="">
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
            // take snapshot and get image data
            Webcam.snap(function(data_uri) {
                // const img_r = document.getElementById("img_r");
                // img_r.remove();
                document.getElementById("img").value = data_uri;
                // document.getElementById("selfImg").value = data_uri;
                console.log('done !');
                // display results in page
                document.getElementById('gift').innerHTML =
                    '<img id="selfImg" name="selfImg" style=" border-radius: 8px;  object-fit: cover;" src="' +
                    data_uri + '"/>';

            });


        }

        function printCard(el, id) {

            const image = document.getElementById('img').value;
            // if(image.length < 1){
            //     Swal.fire({
            //         icon: "error",
            //         timer: 20000,
            //         text: "الرجاء  قم بالتقاط صورة ",
            //         confirmButtonText: 'موافق'
            //     }) 
            //     return;
            // }

            var restorepage = document.body.innerHTML;
            var printcontent = document.getElementById(el).innerHTML;
            document.body.innerHTML = printcontent;
            window.print();
            document.body.innerHTML = restorepage;

            $.ajax({
                url: "{!! route('cards/printed', ['id' => $customer->id]) !!}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}", // 🟢 لازم

                    selfImg: image,
                },
                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        timer: 20000,
                        text: "تمت عملية الطباعة بنجاح",
                        confirmButtonText: 'موافق',
                        onClose: function() {
                            // Redirect to a new route after success
                            window.location.href = "{!! route('cards/index') !!}";

                        }
                    })

                },
                error: function(error) {
                    Swal.fire({
                        icon: "error",
                        timer: 20000,
                        text: "فشل عملية الطباعة",
                        confirmButtonText: 'موافق'
                    })
                }
            });




        }


        function storeCard(el, id) {
            const image = document.getElementById('img').value;

            if (image.length < 1) {
                Swal.fire({
                    icon: "error",
                    timer: 20000,
                    text: "الرجاء  قم بالتقاط صورة ",
                    confirmButtonText: 'موافق'
                })
                return;
            }
            $.ajax({
                url: "{!! route('cards/store', ['id' => $customer->id]) !!}",
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // ✅
                    selfImg: image,
                },
                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        timer: 20000,
                        text: "تم الحفظ بنجاح",
                        confirmButtonText: 'موافق',
                        onClose: function() {
                            // Redirect to a new route after success
                            $('.print__card').toggleClass('show__print');
                        }
                    })

                },
                error: function(error) {
                    Swal.fire({
                        icon: "error",
                        timer: 20000,
                        text: "فشل عملية الحفظ",
                        confirmButtonText: 'موافق'
                    })
                }
            });
        }

        function allowCard(el, id) {
            // const image = document.getElementById('img').value;
            $.ajax({
                url: "{!! route('print_allowed', ['id' => $customer->id]) !!}",
                type: 'POST',
                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        timer: 20000,
                        text: "تمت العملية بنجاح",
                        confirmButtonText: 'موافق',
                        onClose: function() {
                            // Redirect to a new route after success
                            window.location.href = "{!! route('cards/index') !!}";

                        }
                    })

                },
                error: function(error) {
                    Swal.fire({
                        icon: "error",
                        timer: 20000,
                        text: "فشل العملية ",
                        confirmButtonText: 'موافق'
                    })
                }
            });
        }


        function printContent(el, id) {
            var restorepage = document.body.innerHTML;
            var printcontent = document.getElementById(el).innerHTML;
            document.body.innerHTML = printcontent;
            window.print();
            document.body.innerHTML = restorepage;

            $.ajax({
                url: '../../cards/printed/' + id,
                type: 'POST',
                success: function(data) {
                    console.log(data)
                }
            });

        }
    </script>

@endsection
