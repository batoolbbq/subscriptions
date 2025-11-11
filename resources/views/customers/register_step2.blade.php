<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>استكمال بيانات التسجيل</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="municipals-url-template" content="{{ route('municipals.byCity', 'CITY_ID__PLACEHOLDER') }}">

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
            background: radial-gradient(1100px 560px at 85% 12%, rgba(245, 130, 32, .18), transparent 60%), radial-gradient(900px 520px at 12% 88%, rgba(109, 7, 26, .18), transparent 60%), linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 42%, #ffd8b6 78%, #ffe4cc 100%);
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

        .card-head {
            background: linear-gradient(135deg, #d95b00 0%, #F58220 35%, #FF8F34 70%, #ffb066 100%);
            padding: 12px 16px;
            color: #fff;
            font-weight: 800;
            display: flex;
            justify-content: space-between;
            /* يخلي الاسم والزر بعيدين */
            align-items: center;
            cursor: pointer;
        }

        .card-head .toggle-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: #fff;
            font-size: 1.1rem;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .3s;
        }

        .card-head .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .card-body {
            display: none;
            /* مغلق افتراضيًا */
        }

        .card.open .card-body {
            display: block;
            animation: fadeIn .3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="wrap">
            <div class="title-area">
                <h3>استكمال بيانات التسجيل</h3>
            </div>

            @if ($errors->any())
                <div class="panel-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif




            <form action="{{ route('customers.register.step3') }}" method="POST">
                @csrf
                @method('POST')

                {{-- بطاقة المشترك الرئيسي --}}
                <div class="card open" id="main-card">

                    <div class="card-head" dir="ltr">
                        <span class="icon"><i class="fa-solid fa-user-shield"></i></span>
                        بيانات المشترك الرئيسي
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="main[beneficiaries_categories_id]"
                            value="{{ session('beneficiariesSupCategories') }}">

                        <input type="hidden" name="institutionId" value="{{ session('institution_id') }}">


                        <div class="row g-3">
                            <div class="col-md-6 form-group">
                                <label>الاسم (من الأحوال)</label>
                                <input type="text" class="form-control" value="{{ $main['name'] ?? '-' }}" readonly>
                                <input type="hidden" name="main[nationalID]" value="{{ $main['nationalID'] ?? '' }}">
                                <input type="hidden" name="main[name]" value="{{ $main['name'] ?? '' }}">
                                <input type="hidden" name="main[name_en]" value="{{ $main['name_en'] ?? '' }}">
                                <input type="hidden" readonly name="main[registry_number44]"
                                    value="{{ session('registryNumber') }}">
                                <input type="hidden" name="main[birthDate]" value="{{ $main['birthDate'] ?? '' }}">
                                <input type="hidden" name="main[gender]" value="{{ $main['gender'] ?? '' }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>الاسم (بالإنجليزية)</label>
                                <input type="text" class="form-control" value="{{ $main['name_en'] ?? '-' }}"
                                    readonly>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6 form-group">
                                <label>تاريخ الميلاد</label>
                                <input type="text" class="form-control" value="{{ $main['birthDate'] ?? '-' }}"
                                    readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>الرقم الوطني</label>
                                <input type="text" class="form-control" value="{{ $main['nationalID'] ?? '-' }}"
                                    readonly>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6 form-group">
                                <label>البريد الإلكتروني</label>
                                <input type="email" class="form-control" name="main[email]"
                                    value="{{ old('main.email') }}" placeholder="example@mail.com">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>رقم الهاتف</label>
                                <input type="text" class="form-control" value="{{ session('phone') }}" readonly>
                                <input type="hidden" name="main[phone]" value="{{ session('phone') }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6 form-group">
                                <label>فصيلة الدم</label>
                                <select name="main[bloodtypes_id]" class="form-control" required>
                                    <option value="">اختر الفصيلة</option>
                                    @foreach ($bloodtype as $bl)
                                        <option value="{{ $bl->id }}" @selected(old('main.bloodtypes_id') == $bl->id)>
                                            {{ $bl->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="city_main">المنطقة الصحية</label>
                                <select id="city_main" class="form-select city" name="main[cities_id]" required>
                                    <option value="" disabled selected>اختر المنطقة الصحية</option>
                                    @foreach ($city as $c)
                                        <option value="{{ $c->id }}" @selected(old('main.cities_id') == $c->id)>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6 form-group">
                                <label for="Municipal_main">البلدية</label>
                                <select id="Municipal_main" class="form-select municipal" name="main[municipals_id]"
                                    required disabled>
                                    <option value="">اختر البلدية</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>أقرب نقطة بلدية</label>
                                <input type="text" class="form-control" name="main[nearest_municipal_point22]"
                                    placeholder="مثال: نقطة بلدية ...">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6 form-group">
                                <label>رقم الجواز</label>
                                <input type="text" class="form-control" name="main[passport_no]"
                                    placeholder="LXXXXXXXX">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>الحالة الاجتماعية</label>
                                <select name="main[socialstatuses_id]" class="form-control" required>
                                    <option value="">اختر الحالة</option>
                                    @foreach ($socialstatuses as $s)
                                        <option value="{{ $s->id }}" @selected(old('main.socialstatuses_id') == $s->id)>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- المصرف وفرع المصرف جنب بعض -->

                        @if (!in_array(session('beneficiariesSupCategories'), [1, 12]))
                            <div class="row g-3 mt-1">
                                <div class="col-md-6 form-group">
                                    <label>المصرف</label>
                                    <select id="bank_id" name="main[bank_id]" class="form-select" required>
                                        <option value="">اختر المصرف</option>
                                        @foreach (\App\Models\Bank::orderBy('name')->get() as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>فرع المصرف</label>
                                    <select id="bank_branch_id" name="main[bank_branch_id]" class="form-select"
                                        required disabled>
                                        <option value="">اختر الفرع</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-12 form-group" id="ibanRow" style="display:none;">
                                    <label for="iban">رقم الحساب الدولي (IBAN)</label>
                                    <input type="text" class="form-control" id="iban" name="main[iban]"
                                        placeholder="LY3802100100000001234567890">
                                </div>
                            </div>
                        @endif


                        @if (in_array(session('beneficiariesSupCategories'), [7, 8]))
                            @if (session('verified_ok'))
                                {{-- ✅ فيه بيانات من الشيت → نعرضها مقفولة --}}
                                <div class="row g-3 mt-1">
                                    <div class="col-md-6 form-group">
                                        <label>رقم الضمان</label>
                                        <input type="text" class="form-control" name="main[insured_no]"
                                            value="{{ session('insured_no') }}" readonly>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>رقم الحساب</label>
                                        <input type="text" class="form-control" name="main[account_no]"
                                            value="{{ session('account_no') }}" readonly>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">

                                    <div class="col-md-6 form-group">
                                        <label>إجمالي المرتب</label>
                                        <input type="text" class="form-control" name="main[total_pension]"
                                            value="{{ session('total_pension') }}" readonly>
                                    </div>
                                </div>
                            @else
                                {{-- ❌ مفيش بيانات من الشيت → نعرض الحقول فاضية للتعبئة --}}
                                <div class="row g-3 mt-1">
                                    <div class="col-md-6 form-group">
                                        <label>رقم الضمان</label>
                                        <input type="text" class="form-control" name="main[insured_no]">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>رقم الحساب</label>
                                        <input type="text" class="form-control" name="main[account_no]">
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">

                                    <div class="col-md-6 form-group">
                                        <label>إجمالي المرتب</label>
                                        <input type="text" class="form-control" name="main[total_pension]">
                                    </div>
                                </div>
                            @endif
                        @endif




                        @if (session('beneficiariesSupCategories') == 1)
                            <div class="row" style="margin-top:15px">
                                <div>
                                    <label>رقم المعاش</label>
                                    <input type="text" name="main[warrantynumber]" placeholder="XXXXXXXXXXX">
                                </div>
                                <div>
                                    <label>مكتب الضمان</label>
                                    <select name="main[warrantyoffices_id]">
                                        @foreach ($warrantyOffices as $wo)
                                            <option value="{{ $wo->id }}">{{ $wo->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif



                    </div>
                </div>


                @if ($deps->count())
                    @foreach ($deps as $i => $d)
                        <div class="card" id="dep-card-{{ $i }}">
                            <div class="card-head" onclick="toggleDep({{ $i }})">
                                <div>

                                    <span class="fw-bold text-white-75">المنتفع:</span>

                                    <span class="fw-bold text-white">{{ $d['name'] ?? '—' }}</span>

                                    <small class="text-light fst-italic">
                                        @if ($d['relationship'] == 2)
                                            (زوجة)
                                        @elseif($d['relationship'] == 3)
                                            (ابن / ابنة)
                                        @endif
                                    </small>
                                </div>
                                <button type="button" class="toggle-btn" id="btn-{{ $i }}">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-body">

                                {{-- hidden values --}}
                                <input type="hidden" name="dependents[{{ $i }}][nationalID]"
                                    value="{{ $d['nationalID'] ?? '' }}">
                                <input type="hidden" name="dependents[{{ $i }}][name]"
                                    value="{{ $d['name'] ?? '' }}">
                                <input type="hidden" name="dependents[{{ $i }}][name_en]"
                                    value="{{ $d['name_en'] ?? '' }}">
                                <input type="hidden" name="dependents[{{ $i }}][birthDate]"
                                    value="{{ $d['birthDate'] ?? '' }}">
                                <input type="hidden" name="dependents[{{ $i }}][gender]"
                                    value="{{ $d['gender'] ?? '' }}">

                                <div class="row g-3">
                                    <div class="col-md-6 form-group">
                                        <label>الاسم</label>
                                        <input type="text" class="form-control" value="{{ $d['name'] ?? '-' }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>الاسم (بالإنجليزية)</label>
                                        <input type="text" class="form-control"
                                            value="{{ $d['name_en'] ?? '-' }}" readonly>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6 form-group">
                                        <label>الرقم الوطني</label>
                                        <input type="text" class="form-control"
                                            value="{{ $d['nationalID'] ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>تاريخ الميلاد</label>
                                        <input type="text" class="form-control"
                                            value="{{ $d['birthDate'] ?? '-' }}" readonly>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6 form-group">
                                        <label>الجنس</label>
                                        <input type="text" class="form-control" value="{{ $d['gender'] ?? '-' }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>البريد الإلكتروني</label>
                                        <input type="email" class="form-control"
                                            name="dependents[{{ $i }}][email]"
                                            placeholder="example@mail.com">
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6 form-group">
                                        <label>رقم الهاتف</label>
                                        <input type="text" class="form-control" value="{{ session('phone') }}"
                                            readonly>
                                        <input type="hidden" name="dependents[{{ $i }}][phone]"
                                            value="{{ session('phone') }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>فصيلة الدم</label>
                                        <select name="dependents[{{ $i }}][bloodtypes_id]"
                                            class="form-control">
                                            <option value="">اختر الفصيلة</option>
                                            @foreach ($bloodtype as $bl)
                                                <option value="{{ $bl->id }}" @selected(old("dependents.$i.bloodtypes_id") == $bl->id)>
                                                    {{ $bl->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6 form-group">
                                        <label for="city_dep_{{ $i }}">المنطقة الصحية</label>
                                        <select id="city_dep_{{ $i }}" class="form-select city"
                                            name="dependents[{{ $i }}][cities_id]">
                                            <option value="" disabled selected>اختر المنطقة الصحية</option>
                                            @foreach ($city as $c)
                                                <option value="{{ $c->id }}" @selected(old("dependents.$i.cities_id") == $c->id)>
                                                    {{ $c->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="Municipal_dep_{{ $i }}">البلدية</label>
                                        <select id="Municipal_dep_{{ $i }}" class="form-select municipal"
                                            name="dependents[{{ $i }}][municipals_id]" disabled>
                                            <option value="">اختر البلدية</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6 form-group">
                                        <label>أقرب نقطة بلدية</label>
                                        <input type="text" class="form-control"
                                            name="dependents[{{ $i }}][nearest_municipal_point33]"
                                            placeholder="مثال: نقطة بلدية ...">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>رقم الجواز</label>
                                        <input type="text" class="form-control"
                                            name="dependents[{{ $i }}][passport_no]"
                                            placeholder="LXXXXXXXX">
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-12 form-group">
                                        <label>الحالة الاجتماعية</label>
                                        <select name="dependents[{{ $i }}][socialstatuses_id]"
                                            class="form-control">
                                            <option value="">اختر الحالة</option>
                                            @foreach ($socialstatuses as $s)
                                                <option value="{{ $s->id }}" @selected(old("dependents.$i.socialstatuses_id") == $s->id)>
                                                    {{ $s->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="actions">
                    <button type="submit" class="btn btn-block ">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</body>
<script>
    function toggleDep(index) {
        const card = document.getElementById(`dep-card-${index}`);
        const btn = document.getElementById(`btn-${index}`);
        const icon = btn.querySelector('i');

        card.classList.toggle('open'); // يفتح أو يغلق الكارد

        // يبدل الأيقونة
        if (card.classList.contains('open')) {
            icon.classList.replace('fa-plus', 'fa-minus');
        } else {
            icon.classList.replace('fa-minus', 'fa-plus');
        }
    }
</script>

<script src="{{ URL::asset('assets/js/jquery-3.3.1.min.js') }}"></script>
<script>
    // تحميل فروع المصرف + إظهار IBAN
    document.addEventListener('DOMContentLoaded', function() {
        const bankSelect = document.getElementById('bank_id');
        const branchSelect = document.getElementById('bank_branch_id');
        const ibanRow = document.getElementById('ibanRow');

        if (bankSelect) {
            bankSelect.addEventListener('change', function() {
                branchSelect.disabled = true;
                branchSelect.innerHTML = '<option>جاري التحميل...</option>';
                fetch(`/banks/${this.value}/branches`)
                    .then(r => r.json())
                    .then(data => {
                        branchSelect.innerHTML = '<option value="">اختر الفرع</option>';
                        data.forEach(branch => {
                            const opt = document.createElement('option');
                            opt.value = branch.id;
                            opt.textContent = branch.name;
                            branchSelect.appendChild(opt);
                        });
                        branchSelect.disabled = false;
                    });
            });
        }

        if (branchSelect) {
            branchSelect.addEventListener('change', function() {
                ibanRow.style.display = this.value ? 'grid' : 'none';
            });
        }
    });

    // تحميل البلديات حسب المنطقة الصحية
    document.addEventListener('change', function(e) {
        if (!e.target.classList.contains('city')) return;
        const citySelect = e.target;
        const depIndex = citySelect.id.replace('city', 'Municipal');
        const municipalSelect = document.getElementById(depIndex);
        municipalSelect.disabled = true;
        municipalSelect.innerHTML = '<option>جاري التحميل...</option>';

        const tpl = document.querySelector('meta[name="municipals-url-template"]').content;
        const url = tpl.replace('CITY_ID__PLACEHOLDER', citySelect.value);

        fetch(url)
            .then(r => r.json())
            .then(data => {
                municipalSelect.innerHTML = '<option value="">اختر البلدية</option>';
                data.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.name;
                    municipalSelect.appendChild(opt);
                });
                municipalSelect.disabled = false;
            });
    });
</script>

</html>
