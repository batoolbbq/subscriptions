@extends('layouts.master')

@section('title', ' تعديل مشترك')

@section('content')
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

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--bg-1);
        }

        .page {
            min-height: 100dvh;
            display: grid;
            place-items: center;
            padding: 24px
        }

        .wrap {
            width: 100%;
            max-width: 800px;
            margin-inline: auto
        }

        .title-area {
            text-align: center;
            margin-bottom: 18px
        }

        .title-area h3 {
            margin: 0;
            font-weight: 800;
            color: var(--brand)
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
            transition: border-color .2s, box-shadow .2s
        }

        input:focus,
        select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .18)
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

        .btn:hover {
            transform: translateY(-1px);
            background: var(--brand-600)
        }
    </style>

    <div class="page">
        <div class="wrap">
            <div class="title-area">
                <h3>تعديل بيانات مشترك / منتفع</h3>
            </div>

            <div class="card">
                <div class="card-head">
                    <span class="icon"><i class="fa-solid fa-user-pen"></i></span>
                    <span> بيانات مشترك / منتفع</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6 form-group">
                                <label>الاسم</label>
                                <input type="text" value="{{ $customer->fullnamea }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>الاسم (بالإنجليزية)</label>
                                <input type="text" value="{{ $customer->fullnamee }}" readonly>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6 form-group">
                                <label>الرقم الوطني</label>
                                <input type="text" value="{{ $customer->nationalID }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>رقم القيد</label>
                                <input type="text" value="{{ $customer->registrationnumbers }}" readonly>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6 form-group">
                                <label>البريد الإلكتروني</label>
                                <input type="email" name="email" value="{{ old('email', $customer->email) }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>رقم الهاتف</label>
                                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6 form-group">
                                <label>الجنس</label>
                                <select name="gender"disabled >
                                    <option value="">اختر</option>
                                    <option value="1" @selected(old('gender', $customer->gender) == '1')>ذكر</option>
                                    <option value="2" @selected(old('gender', $customer->gender) == '2')>أنثى</option>
                                </select>
                            </div>
                            <input type="hidden" name="gender" value="{{ $customer->gender }}">
                            <div class="col-md-6 form-group">
                                <label>تاريخ الميلاد</label>
                                <input type="date" readonly name="birthDate"
                                    value="{{ old('birthDate', $customer->yearbitrh) }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6 form-group">
                                <label>فصيلة الدم</label>
                                <select name="bloodtypes_id">
                                    <option value="">اختر الفصيلة</option>
                                    @foreach ($bloodtype as $bl)
                                        <option value="{{ $bl->id }}" @selected(old('bloodtypes_id', $customer->bloodtypes_id) == $bl->id)>
                                            {{ $bl->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>الحالة الاجتماعية</label>
                                <select name="socialstatuses_id">
                                    <option value="">اختر الحالة</option>
                                    @foreach ($socialstatuses as $s)
                                        <option value="{{ $s->id }}" @selected(old('socialstatuses_id', $customer->socialstatuses_id) == $s->id)>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row g-3 mt-1">
                            {{-- المنطقة الصحية --}}
                            <div class="col-md-6 form-group">
                                <label for="city">المنطقة الصحية</label>
                                <select id="city" class="form-select city @error('cities_id') is-invalid @enderror"
                                    name="cities_id" required>
                                    <option value="" selected disabled>اختر المنطقة الصحية</option>
                                    @foreach ($cities as $item)
                                        <option value="{{ $item->id }}" @selected(old('cities_id', $customer->cities_id) == $item->id)>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cities_id')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- البلدية --}}
                            <div class="col-md-6 form-group">
                                <label for="Municipal">البلدية</label>
                                <select id="Municipal"
                                    class="form-select Municipal @error('municipals_id') is-invalid @enderror"
                                    name="municipals_id" disabled required>
                                    <option value="" selected>اختر البلدية</option>
                                </select>
                                <div id="municipalSkeleton" class="skeleton" style="display:none">جاري التحميل…</div>
                                @error('municipals_id')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>




                        <div class="row g-3 mt-1">
                            {{-- المصرف --}}
                            <div class="col-md-6 form-group">
                                <label for="bank_id">المصرف</label>
                                <select id="bank_id" name="bank_id" class="form-select" required>
                                    <option value="">اختر المصرف</option>
                                    @foreach (\App\Models\Bank::orderBy('name')->get() as $bank)
                                        <option value="{{ $bank->id }}" @selected(old('bank_id', $customer->bank_id) == $bank->id)>
                                            {{ $bank->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- فرع المصرف --}}
                            <div class="col-md-6 form-group">
                                <label for="bank_branch_id">فرع المصرف</label>
                                <select id="bank_branch_id" name="bank_branch_id" class="form-select" required disabled>
                                    <option value="">اختر الفرع</option>
                                </select>
                                @error('bank_branch_id')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            {{-- IBAN --}}
                            <div class="col-md-6 form-group" id="ibanRow" style="display:none;">
                                <label for="iban">رقم الحساب الدولي (IBAN)</label>
                                <input type="text" id="iban" name="iban"
                                    value="{{ old('iban', $customer->iban) }}" placeholder="LY3802100100000001234567890">
                                @error('iban')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- أقرب نقطة --}}
                            <div class="col-md-6 form-group">
                                <label for="nearestpoint">أقرب نقطة</label>
                                <input type="text" id="nearestpoint" name="nearestpoint"
                                    value="{{ old('nearestpoint', $customer->nearestpoint) }}"
                                    placeholder="أدخل أقرب نقطة">
                                @error('nearestpoint')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- الحقول المالية --}}
                        @if ($customer->total_pension || $customer->pension_no || $customer->account_no || $customer->insured_no)
                            <div class="row g-3 mt-1">
                                @if ($customer->total_pension)
                                    <div class="col-md-6 form-group">
                                        <label for="total_pension">إجمالي المرتب</label>
                                        <input type="number" step="0.01" id="total_pension" name="total_pension"
                                            value="{{ old('total_pension', $customer->total_pension) }}"
                                            placeholder="أدخل إجمالي المرتب">
                                        @error('total_pension')
                                            <div class="error-text">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                @if ($customer->pension_no)
                                    <div class="col-md-6 form-group">
                                        <label for="pension_no">رقم المضمون</label>
                                        <input type="text" id="pension_no" name="pension_no"
                                            value="{{ old('pension_no', $customer->pension_no) }}"
                                            placeholder="أدخل رقم المضمون">
                                        @error('pension_no')
                                            <div class="error-text">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                            </div>

                            <div class="row g-3 mt-1">
                                @if ($customer->account_no)
                                    <div class="col-md-6 form-group">
                                        <label for="account_no">رقم الحساب</label>
                                        <input type="text" id="account_no" name="account_no"
                                            value="{{ old('account_no', $customer->account_no) }}"
                                            placeholder="أدخل رقم الحساب">
                                        @error('account_no')
                                            <div class="error-text">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                @if ($customer->insured_no)
                                    <div class="col-md-6 form-group">
                                        <label for="insured_no">رقم المعاش</label>
                                        <input type="text" id="insured_no" name="insured_no"
                                            value="{{ old('insured_no', $customer->insured_no) }}"
                                            placeholder="أدخل رقم المعاش">
                                        @error('insured_no')
                                            <div class="error-text">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        @endif








                        {{-- نحط meta فيها الـ route --}}
                        <meta name="municipals-url-template"
                            content="{{ route('municipals.byCity', 'CITY_ID__PLACEHOLDER') }}">




                        <div class="actions" style="margin-top:16px;">
                            <button type="submit" class="btn btn-b">حفظ التعديلات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>





    <script>
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

                            // ✅ لو في فرع قديم مخزّن عند العميل → حطه
                            const oldBranch = "{{ old('bank_branch_id', $customer->bank_branch_id) }}";
                            if (oldBranch) {
                                branchSelect.value = oldBranch;
                                // نعرض حقل IBAN مباشرة لو الفرع موجود
                                ibanRow.style.display = 'grid';
                            }
                        });
                });

                // ✅ عند تحميل الصفحة: لو عنده مصرف مسجل → نختاره ونطلق change
                const oldBank = "{{ old('bank_id', $customer->bank_id) }}";
                if (oldBank) {
                    bankSelect.value = oldBank;
                    bankSelect.dispatchEvent(new Event('change'));
                }
            }

            if (branchSelect) {
                branchSelect.addEventListener('change', function() {
                    ibanRow.style.display = this.value ? 'grid' : 'none';
                });
            }
        });

        document.addEventListener('change', function(e) {
            if (!e.target.classList.contains('city')) return;

            const citySelect = e.target;
            const municipalSelect = document.getElementById('Municipal');
            const skeleton = document.getElementById('municipalSkeleton');

            municipalSelect.disabled = true;
            municipalSelect.innerHTML = '<option value="" selected>جاري التحميل...</option>';
            if (skeleton) skeleton.style.display = 'block';

            const tpl = document.querySelector('meta[name="municipals-url-template"]').content;
            const url = tpl.replace('CITY_ID__PLACEHOLDER', encodeURIComponent(citySelect.value));

            fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    },
                    cache: 'no-store'
                })
                .then(r => {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(data => {
                    municipalSelect.innerHTML = '<option value="" selected>اختر البلدية</option>';
                    if (Array.isArray(data) && data.length) {
                        data.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.id;
                            opt.textContent = item.name;
                            municipalSelect.appendChild(opt);
                        });
                        municipalSelect.disabled = false;

                        @if (old('municipals_id', $customer->municipals_id))
                            municipalSelect.value = "{{ old('municipals_id', $customer->municipals_id) }}";
                        @endif
                    } else {
                        municipalSelect.innerHTML = '<option value="" selected>لا توجد بلديات متاحة</option>';
                    }
                })
                .catch(err => {
                    console.error('[Municipals] error:', err);
                    municipalSelect.innerHTML = '<option value="" selected>تعذر التحميل، حاول مجدداً</option>';
                })
                .finally(() => {
                    if (skeleton) skeleton.style.display = 'none';
                });
        });

        // عند التحميل: استرجاع المدينة القديمة
        document.addEventListener('DOMContentLoaded', () => {
            const city = document.getElementById('city');
            const oldCity = "{{ old('cities_id', $customer->cities_id) }}";
            if (oldCity) {
                city.value = oldCity;
                city.dispatchEvent(new Event('change'));
            }
        });
    </script>

@endsection
