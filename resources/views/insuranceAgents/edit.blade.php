@extends('layouts.master')

@section('title', 'تعديل بيانات وكيل التأمين')

@section('css')
    {{-- خط + أيقونات --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container py-4"
        style="--brand:#F58220;--brand-600:#ff8f34;--brown:#8C5346;--ink:#1F2328;--muted:#6b7280;--line:#E5E7EB;
            --hdr1:#d95b00;--hdr2:#F58220;--hdr3:#FF8F34;--hdr4:#ffb066;
            --green-50:#e9fbf2;--green-700:#10734a;--gray-50:#eff2f6;--gray-700:#374151;
            --red-50:#fff1f1;--red-200:#ffc9c9;--red-700:#b42318;
            font-family:'Tajawal',system-ui,-apple-system,Segoe UI,Roboto,sans-serif;">

        {{-- العنوان + رجوع --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <h3 style="margin:0;font-weight:800;color:var(--brown);letter-spacing:.2px;">تعديل بيانات وكيل التأمين</h3>
                <div style="color:var(--muted);font-size:14px;">قم بتحديث بيانات الوكيل ثم احفظ التغييرات.</div>
            </div>
            <a href="{{ route('insuranceAgents.index') }}"
                style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;
                  background:#fff;color:var(--ink);border:1.5px solid var(--line);
                  border-radius:999px;padding:10px 16px;font-weight:900;text-decoration:none;
                  box-shadow:0 8px 18px rgba(0,0,0,.06);">
                <i class="fa-solid fa-arrow-right"></i> رجوع للقائمة
            </a>
        </div>

        {{-- رسائل الأخطاء/النجاح --}}
        @if ($errors->any())
            <div
                style="border:1.5px solid var(--red-200);background:var(--red-50);padding:12px;border-radius:14px;
                    margin-bottom:16px;box-shadow:0 10px 28px rgba(0,0,0,.08);color:var(--red-700);">
                <div style="font-weight:800;margin-bottom:6px;">تحقق من الحقول التالية:</div>
                <ul style="margin:0;padding-inline-start:22px;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (Session::has('success'))
            <div
                style="border:1.5px solid #86efac;background:#f0fdf4;padding:12px;border-radius:14px;
                    margin-bottom:16px;box-shadow:0 10px 28px rgba(0,0,0,.08);color:#166534;font-weight:800;">
                {{ Session::get('success') }}
            </div>
        @endif

        <form method="POST" enctype="multipart/form-data" action="{{ route('insuranceAgents.update', $agent->id) }}">
            @csrf
            @method('PUT')

            {{-- البطاقة 1: بيانات الوكيل --}}
            <div
                style="background:#fff;border:1.5px solid var(--line);border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);margin-bottom:16px;overflow:hidden;">
                <div
                    style="background:linear-gradient(135deg,var(--hdr1) 0%,var(--hdr2) 35%,var(--hdr3) 70%,var(--hdr4) 100%);
                        color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                    <span
                        style="background:var(--hdr3);color:#fff;min-width:34px;height:34px;display:grid;place-items:center;border-radius:999px;font-size:.95rem;box-shadow:0 10px 22px rgba(245,130,32,.35);">1</span>
                    <h6 style="margin:0;font-weight:800;color:#fff;">بيانات الوكيل</h6>
                </div>

                <div style="padding:22px 20px 26px;">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label
                                style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">الاسم
                                رباعي <span style="color:#ef4444;">*</span></label>
                            <input type="text" id="name" name="name" maxlength="50"
                                class="form-control @error('name') is-invalid @enderror"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                value="{{ old('name', $agent->name) }}" required>
                            @error('name')
                                <div style="color:var(--red-700);font-size:13px;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label
                                style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">رقم
                                الهاتف <span style="color:#ef4444;">*</span></label>
                            <input type="text" id="phone_number" name="phone_number" maxlength="9"
                                class="form-control @error('phone_number') is-invalid @enderror"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                value="{{ old('phone_number', $agent->phone_number) }}" required
                                onkeypress="return onlyNumberKey(event)">
                            <div style="color:#6b7280;font-size:13px;margin-top:6px;">اكتب 9 أرقام بدون صفر البداية (ينبغي
                                أن يبدأ بـ 91/92/94/21)</div>
                            @error('phone_number')
                                <div style="color:var(--red-700);font-size:13px;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label
                                style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">العنوان
                                <span style="color:#ef4444;">*</span></label>
                            <input type="text" id="address" name="address" maxlength="150"
                                class="form-control @error('address') is-invalid @enderror"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                value="{{ old('address', $agent->address) }}" required>
                            @error('address')
                                <div style="color:var(--red-700);font-size:13px;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label
                                style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">البريد
                                الإلكتروني <span style="color:#ef4444;">*</span></label>
                            <input type="email" id="email" name="email" maxlength="50"
                                class="form-control @error('email') is-invalid @enderror"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                value="{{ old('email', $agent->email) }}" required>
                            @error('email')
                                <div style="color:var(--red-700);font-size:13px;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label
                                style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">المنطقة
                                الصحية <span style="color:#ef4444;">*</span></label>
                            <select id="cities_id" name="cities_id"
                                class="form-control city @error('cities_id') is-invalid @enderror"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                required>
                                <option value="" disabled>اختر المنطقة الصحية</option>
                                @foreach ($cities as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ (string) old('cities_id', $agent->cities_id) === (string) $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cities_id')
                                <div style="color:var(--red-700);font-size:13px;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label
                                style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">البلدية
                                <span style="color:#ef4444;">*</span></label>
                            <select id="municipals_id" name="municipals_id"
                                class="form-control Municipal @error('municipals_id') is-invalid @enderror"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                required>
                                {{-- سيتم ملؤه عبر الـ AJAX أدناه --}}
                            </select>
                            @error('municipals_id')
                                <div style="color:var(--red-700);font-size:13px;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">

                            <label
                                style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">وصف
                                للمكان <span style="color:#ef4444;">*</span></label>
                            <textarea id="description" name="description" rows="4"
                                class="form-control @error('description') is-invalid @enderror"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:16px;padding:12px 14px;font-size:1rem;outline:none;"
                                required>{{ old('description', $agent->description) }}</textarea>
                            @error('description')
                                <div style="color:var(--red-700);font-size:13px;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- البطاقة 2: المستندات والملفات --}}
            <div
                style="background:#fff;border:1.5px solid var(--line);border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);margin-bottom:16px;overflow:hidden;">
                <div
                    style="background:linear-gradient(135deg,var(--hdr1) 0%,var(--hdr2) 35%,var(--hdr3) 70%,var(--hdr4) 100%);
                        color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                    <span
                        style="background:var(--hdr3);color:#fff;min-width:34px;height:34px;display:grid;place-items:center;border-radius:999px;font-size:.95rem;box-shadow:0 10px 22px rgba(245,130,32,.35);">2</span>
                    <h6 style="margin:0;font-weight:800;color:#fff;">المستندات والملفات</h6>
                </div>

                <div
                    style="padding:22px 20px 26px;display:grid;
            grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;">

                    {{-- شهادة الميلاد --}}
                    <div>
                        <label style="display:block;margin-bottom:6px;color:var(--muted);font-weight:700;">شهادة
                            الميلاد</label>

                        @if ($agent->birth_certificate_path)
                            <a href="{{ asset('insurancagents_files/' . $agent->birth_certificate_path) }}"
                                target="_blank"
                                style="display:flex;align-items:center;gap:10px;border:1.5px solid var(--line);
                       border-radius:16px;padding:12px 14px;text-decoration:none;color:var(--ink);margin-bottom:8px;">
                                <i class="fa-solid fa-id-card" style="font-size:20px;color:var(--brand);"></i>
                                <span style="font-weight:800;">عرض الملف الحالي</span>
                            </a>
                        @endif

                        <input type="file" name="birth_certificate"
                            class="form-control @error('birth_certificate') is-invalid @enderror"
                            style="border:1px solid #d7dbe0;background:#fdfdfd;border-radius:12px;padding:8px 14px;font-size:1rem;">
                        @error('birth_certificate')
                            <div style="color:var(--red-700);font-size:13px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- شهادة التخرج --}}
                    <div>
                        <label style="display:block;margin-bottom:6px;color:var(--muted);font-weight:700;">شهادة
                            التخرج</label>

                        @if ($agent->qualification_path)
                            <a href="{{ asset('insurancagents_files/' . $agent->qualification_path) }}" target="_blank"
                                style="display:flex;align-items:center;gap:10px;border:1.5px solid var(--line);
                       border-radius:16px;padding:12px 14px;text-decoration:none;color:var(--ink);margin-bottom:8px;">
                                <i class="fa-solid fa-graduation-cap" style="font-size:20px;color:var(--brand);"></i>
                                <span style="font-weight:800;">عرض الملف الحالي</span>
                            </a>
                        @endif

                        <input type="file" name="qualification"
                            class="form-control @error('qualification') is-invalid @enderror"
                            style="border:1px solid #d7dbe0;background:#fdfdfd;border-radius:12px;padding:8px 14px;font-size:1rem;">
                        @error('qualification')
                            <div style="color:var(--red-700);font-size:13px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- صورة للمكان --}}
                    <div>
                        <label style="display:block;margin-bottom:6px;color:var(--muted);font-weight:700;">صورة
                            للمكان</label>

                        @if ($agent->location_image_path)
                            <a href="{{ asset('insurancagents_files/' . $agent->location_image_path) }}" target="_blank"
                                style="display:flex;align-items:center;gap:10px;border:1.5px solid var(--line);
                       border-radius:16px;padding:12px 14px;text-decoration:none;color:var(--ink);margin-bottom:8px;">
                                <i class="fa-regular fa-image" style="font-size:20px;color:var(--brand);"></i>
                                <span style="font-weight:800;">عرض الملف الحالي</span>
                            </a>
                        @endif

                        <input type="file" name="location_image"
                            class="form-control @error('location_image') is-invalid @enderror"
                            style="border:1px solid #d7dbe0;background:#fdfdfd;border-radius:12px;padding:8px 14px;font-size:1rem;">
                        @error('location_image')
                            <div style="color:var(--red-700);font-size:13px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

            </div>

            {{-- الأزرار --}}
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button type="submit"
                    style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;
                           background:var(--brand);color:#fff;padding:10px 18px;border-radius:999px;
                           font-weight:900;text-decoration:none;box-shadow:0 12px 26px rgba(245,130,32,.30);">
                    <i class="fa-solid fa-floppy-disk"></i> حفظ التغييرات
                </button>

                <a href="{{ route('insuranceAgents.index') }}"
                    style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;
                      background:#fff;color:var(--ink);border:1.5px solid var(--line);
                      border-radius:999px;padding:10px 18px;font-weight:900;text-decoration:none;">
                    <i class="fa-solid fa-xmark"></i> إلغاء
                </a>
            </div>

        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function populateMunicipals(cityId, selectedId = null) {
            const $municipal = $('#municipals_id');
            $municipal.prop('disabled', true).empty()
                .append('<option value="" disabled>جاري التحميل...</option>');

            if (!cityId) {
                return;
            }

            $.ajax({
                url: '/get-Municipal/' + cityId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $municipal.empty().append('<option value="" disabled selected>اختر البلدية</option>');
                    data.forEach(function(item) {
                        const opt = $('<option/>', {
                            value: item.id,
                            text: item.name
                        });
                        if (selectedId && String(selectedId) === String(item.id)) {
                            opt.attr('selected', 'selected');
                        }
                        $municipal.append(opt);
                    });
                    $municipal.prop('disabled', false);
                },
                error: function() {
                    $municipal.empty().append(
                        '<option value="" disabled selected>حدث خطأ أثناء التحميل</option>');
                }
            });
        }

        $(document).ready(function() {
            const currentCityId = '{{ old('cities_id', $agent->cities_id) }}';
            const currentMunicipalId = '{{ old('municipals_id', $agent->municipals_id) }}';
            if (currentCityId) {
                populateMunicipals(currentCityId, currentMunicipalId);
            }
            $('#cities_id').on('change', function() {
                populateMunicipals(this.value, null);
            });
        });

        function onlyNumberKey(evt) {
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode;
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) return false;
            return true;
        }
    </script>
@endpush
