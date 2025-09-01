<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>استكمال بيانات التسجيل</title>
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
            max-width: 940px;
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
            overflow: hidden;
            margin-bottom: 16px
        }

        .card-head {
            background: linear-gradient(135deg, #d95b00 0%, #F58220 35%, #FF8F34 70%, #ffb066 100%);
            padding: 12px 16px;
            color: #fff;
            font-weight: 800;
            display: flex;
            gap: 10px;
            align-items: center
        }

        .card-head .icon {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: #ff8f34;
            display: grid;
            place-items: center
        }

        .card-body {
            padding: 16px
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

        input[readonly] {
            background: #f9fafb
        }

        input:focus,
        select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .18)
        }

        .row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px
        }

        @media (max-width:560px) {
            .row {
                grid-template-columns: 1fr
            }
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 16px
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

        .btn.secondary {
            background: #111827;
            color: #fff;
            box-shadow: 0 10px 20px rgba(17, 24, 39, .15)
        }

        .btn:hover {
            transform: translateY(-1px);
            background: var(--brand-600)
        }

        .panel-error {
            background: #fff5f5;
            border: 1px solid #fca5a5;
            color: #991b1b;
            border-radius: 12px;
            padding: 10px;
            margin-bottom: 16px
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="wrap">
            <div class="title-area">
                <h3>استكمال بيانات التسجيل</h3>
            </div>

            @php($main = session('cra_main'))
            @php($deps = collect(session('cra_dependents', []))->values())

            @if (!session('cra_ok') || !$main)
                <div class="panel-error">
                    لا توجد بيانات محمّلة من مصلحة الأحوال. الرجاء الرجوع للخطوة السابقة وإجراء التحقق أولاً.
                </div>
            @else
                <form action="#" method="POST" id="step2-form">
                    @csrf

                    {{-- بطاقة المشترك الرئيسي --}}
                    <div class="card">
                        <div class="card-head">
                            <span class="icon"><i class="fa-solid fa-user-shield"></i></span>
                            بيانات المشترك الرئيسي
                        </div>
                        <div class="card-body">
                            {{-- نفس الحقول السابقة للمشترك الرئيسي --}}
                            <div class="row">
                                <div>
                                    <label>الاسم (من الأحوال)</label>
                                    <input type="text" value="{{ $main['name'] ?? '-' }}" readonly>
                                    <input type="hidden" name="main[nationalID]"
                                        value="{{ $main['nationalID'] ?? '' }}">
                                </div>
                                <div>
                                    <label>الاسم بالإنجليزي</label>
                                    <input type="text" name="main[name_en]" placeholder="Full name in English">
                                </div>
                            </div>

                            <div class="row" style="margin-top:10px">
                                <div>
                                    <label>اسم الأم</label>
                                    <input type="text" value="{{ $main['mother'] ?? '-' }}" readonly>
                                </div>
                                <div>
                                    <label>تاريخ الميلاد</label>
                                    <input type="text" value="{{ $main['birthDate'] ?? '-' }}" readonly>
                                </div>
                            </div>

                            <div class="row" style="margin-top:10px">
                                <div>
                                    <label>مكان الميلاد</label>
                                    <input type="text" value="{{ $main['birthPlace'] ?? '-' }}" readonly>
                                </div>
                                <div>
                                    <label>الجنس</label>
                                    <input type="text" value="{{ $main['gender'] ?? '-' }}" readonly>
                                </div>
                            </div>

                            <div class="row" style="margin-top:16px">
                                <div>
                                    <label>فصيلة الدم</label>
                                    <select name="bloodtypes_id"
                                        style="height: calc(3.25rem + 3px);
                    "
                                        class="form-control @error('bloodtypes_id') is-invalid @enderror"
                                        name="bloodtypes_id" id="bloodtypes_id" required>
                                        <option value=""> فصيلة الدم </option>

                                        @forelse ($bloodtype as $bl)
                                            <option value="{{ $bl->id }}"
                                                {{ old('bloodtypes_id') == $bl->id ? 'selected' : '' }}>
                                                {{ $bl->name }}
                                            </option>
                                        @empty
                                            <option value="">لا يوجد فصائل</option>
                                        @endforelse

                                    </select>

                                </div>
                                <div>
                                    <label>أقرب نقطة بلدية</label>
                                    <input type="text" name="main[nearest_municipal_point]"
                                        placeholder="مثال: نقطة بلدية ...">
                                </div>
                            </div>

                            <div class="row" style="margin-top:10px">
                                <div>
                                    <label>المنطقة الصحية</label>
                                    <select name="cities_id" style="height: calc(3.25rem + 3px);"
                                        class="form-control @error('cities_id') is-invalid @enderror" name="cities_id"
                                        id="cities_id" required>
                                        <option value=""> المنطقة الصحية </option>
                                        @forelse ($city as $ci)
                                            <option value="{{ $ci->id }}"
                                                {{ old('cities_id') == $ci->id ? 'selected' : '' }}>
                                                {{ $ci->name }}</option>
                                        @empty
                                            <option value="">لا يوجد مدن</option>
                                        @endforelse

                                    </select>
                                </div>
                                <div>
                                    <label>رقم الجواز</label>
                                    <input type="text" name="main[passport_no]" placeholder="مثال: LXXXXXXXX">
                                </div>
                            </div>

                            <div class="row" style="margin-top:10px">
                                <div>
                                    <label>الحالة الاجتماعية</label>
                                    <select name="main[marital_status]">
                                        <option value="">— اختر —</option>
                                        <option value="single">أعزب/عزباء</option>
                                        <option value="married">متزوج/متزوجة</option>
                                        <option value="divorced">مطلق/مطلقة</option>
                                        <option value="widowed">أرمل/أرملة</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- بطاقات المشتركين الفرعيين --}}
                    @if ($deps->count())
                        @foreach ($deps as $i => $d)
                            <div class="card">
                                <div class="card-head">
                                    <span class="icon"><i class="fa-solid fa-user"></i></span>
                                    مشترك فرعي
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div>
                                            <label>الاسم</label>
                                            <input type="text" value="{{ $d['name'] ?? '-' }}" readonly>
                                            <input type="hidden" name="dependents[{{ $i }}][nationalID]"
                                                value="{{ $d['nationalID'] ?? '' }}">
                                        </div>
                                        <div>
                                            <label>الاسم بالإنجليزي</label>
                                            <input type="text" name="dependents[{{ $i }}][name_en]"
                                                placeholder="Name in English">
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top:10px">
                                        <div>
                                            <label>اسم الأم</label>
                                            <input type="text" value="{{ $d['mother'] ?? '-' }}" readonly>
                                        </div>
                                        <div>
                                            <label>تاريخ الميلاد</label>
                                            <input type="text" value="{{ $d['birthDate'] ?? '-' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top:10px">
                                        <div>
                                            <label>مكان الميلاد</label>
                                            <input type="text" value="{{ $d['birthPlace'] ?? '-' }}" readonly>
                                        </div>
                                        <div>
                                            <label>الجنس</label>
                                            <input type="text" value="{{ $d['gender'] ?? '-' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top:16px">
                                        <div>
                                            <label>فصيلة الدم</label>
                                            <select name="bloodtypes_id"
                                                style="height: calc(3.25rem + 3px);
                    "
                                                class="form-control @error('bloodtypes_id') is-invalid @enderror"
                                                name="bloodtypes_id" id="bloodtypes_id" required>
                                                <option value=""> فصيلة الدم </option>

                                                @forelse ($bloodtype as $bl)
                                                    <option value="{{ $bl->id }}"
                                                        {{ old('bloodtypes_id') == $bl->id ? 'selected' : '' }}>
                                                        {{ $bl->name }}
                                                    </option>
                                                @empty
                                                    <option value="">لا يوجد فصائل</option>
                                                @endforelse

                                            </select>
                                        </div>
                                        <div>
                                            <label>أقرب نقطة بلدية</label>
                                            <input type="text"
                                                name="dependents[{{ $i }}][nearest_municipal_point]"
                                                placeholder="مثال: نقطة بلدية ...">
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top:10px">
                                        <div>
                                            <label>المنطقة الصحية</label>
                                            <select name="cities_id" style="height: calc(3.25rem + 3px);"
                                                class="form-control @error('cities_id') is-invalid @enderror"
                                                name="cities_id" id="cities_id" required>
                                                <option value=""> المنطقة الصحية </option>
                                                @forelse ($city as $ci)
                                                    <option value="{{ $ci->id }}"
                                                        {{ old('cities_id') == $ci->id ? 'selected' : '' }}>
                                                        {{ $ci->name }}</option>
                                                @empty
                                                    <option value="">لا يوجد مدن</option>
                                                @endforelse

                                            </select>
                                        </div>
                                        <div>
                                            <label>رقم الجواز</label>
                                            <input type="text" name="dependents[{{ $i }}][passport_no]"
                                                placeholder="مثال: LXXXXXXXX">
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top:10px">
                                        <div>
                                            <label>الحالة الاجتماعية</label>
                                            <select name="dependents[{{ $i }}][marital_status]">
                                                <option value="">— اختر —</option>
                                                <option value="single">أعزب/عزباء</option>
                                                <option value="married">متزوج/متزوجة</option>
                                                <option value="divorced">مطلق/مطلقة</option>
                                                <option value="widowed">أرمل/أرملة</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <div class="actions">
                        <a href="{{ url()->previous() }}" class="btn secondary">رجوع</a>
                        <button type="submit" class="btn"
                            onclick="event.preventDefault(); alert('لا يوجد تخزين الآن — هذه واجهة تجريبية فقط');">متابعة
                            (تجريبي)</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</body>

</html>
