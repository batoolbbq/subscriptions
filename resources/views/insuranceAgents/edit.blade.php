@extends('layouts.master')
@section('title', 'تعديل بيانات وكيل التأمين')

@section('content')
<div class="container py-4" style="font-family: sans-serif;">

    {{-- العنوان وزر الرجوع --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <div>
            <h3 style="margin:0;font-weight:800;color:#111827;">تعديل بيانات وكيل التأمين</h3>
            <div style="color:#6b7280;font-size:14px;">قم بتحديث بيانات الوكيل ثم احفظ التغييرات.</div>
        </div>
        <a href="{{ route('insuranceAgents.index') }}" style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:6px 14px;font-weight:700;text-decoration:none;">
            <i class="fa fa-arrow-right"></i> رجوع للقائمة
        </a>
    </div>

    {{-- رسائل الأخطاء --}}
    @if ($errors->any())
        <div style="border:1.5px solid #fecaca;background:#fff5f5;padding:12px;border-radius:8px;margin-bottom:16px;">
            <div style="font-weight:700;margin-bottom:6px;">تحقق من الحقول التالية:</div>
            <ul style="margin:0;padding-left:20px;">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (Session::has('success'))
      <p class="alert alert-success">{{ Session::get('success') }}</p>
    @endif

    <form method="POST" enctype="multipart/form-data" action="{{ route('insuranceAgents.update', $agent->id) }}">
        @csrf
        @method('PUT')

        {{-- البطاقة 1: بيانات الوكيل --}}
        <div style="border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);margin-bottom:16px;overflow:hidden;">
            <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid #D0D5DD;padding:10px 14px;display:flex;align-items:center;gap:8px;">
                <span style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid #FFD8A8;color:#92400E;font-weight:800;">1</span>
                <h6 style="margin:0;font-weight:800;color:#374151;">بيانات الوكيل</h6>
            </div>
            <div style="padding:16px;">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <label style="font-weight:700;color:#374151;">الاسم رباعي <span style="color:red;">*</span></label>
                        <input type="text" id="name" name="name" maxlength="50"
                               class="form-control @error('name') is-invalid @enderror"
                               style="border:1.5px solid #E5E7EB;"
                               value="{{ old('name', $agent->name) }}" required>
                        @error('name') <div style="color:#b91c1c;font-size:13px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-lg-6">
                        <label style="font-weight:700;color:#374151;">رقم الهاتف <span style="color:red;">*</span></label>
                        <input type="text" id="phone_number" name="phone_number" maxlength="9"
                               class="form-control @error('phone_number') is-invalid @enderror"
                               style="border:1.5px solid #E5E7EB;"
                               value="{{ old('phone_number', $agent->phone_number) }}" required
                               onkeypress="return onlyNumberKey(event)">
                        <div style="color:#6b7280;font-size:13px;">اكتب 9 أرقام بدون صفر البداية (ينبغي أن يبدأ بـ 91/92/94/21)</div>
                        @error('phone_number') <div style="color:#b91c1c;font-size:13px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-lg-6">
                        <label style="font-weight:700;color:#374151;">العنوان <span style="color:red;">*</span></label>
                        <input type="text" id="address" name="address" maxlength="150"
                               class="form-control @error('address') is-invalid @enderror"
                               style="border:1.5px solid #E5E7EB;"
                               value="{{ old('address', $agent->address) }}" required>
                        @error('address') <div style="color:#b91c1c;font-size:13px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-lg-6">
                        <label style="font-weight:700;color:#374151;">البريد الإلكتروني <span style="color:red;">*</span></label>
                        <input type="email" id="email" name="email" maxlength="50"
                               class="form-control @error('email') is-invalid @enderror"
                               style="border:1.5px solid #E5E7EB;"
                               value="{{ old('email', $agent->email) }}" required>
                        @error('email') <div style="color:#b91c1c;font-size:13px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label style="font-weight:700;color:#374151;">المنطقة الصحية <span style="color:red;">*</span></label>
                        <select id="cities_id" name="cities_id"
                                class="form-control city @error('cities_id') is-invalid @enderror"
                                style="border:1.5px solid #E5E7EB;" required>
                            <option value="" disabled>اختر المنطقة الصحية</option>
                            @foreach ($cities as $id => $name)
                                <option value="{{ $id }}" {{ (string)old('cities_id', $agent->cities_id) === (string)$id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('cities_id') <div style="color:#b91c1c;font-size:13px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label style="font-weight:700;color:#374151;">البلدية <span style="color:red;">*</span></label>
                        <select id="municipals_id" name="municipals_id"
                                class="form-control Municipal @error('municipals_id') is-invalid @enderror"
                                style="border:1.5px solid #E5E7EB;" required>
                            {{-- سيتم ملؤه عبر الـ AJAX أدناه --}}
                        </select>
                        @error('municipals_id') <div style="color:#b91c1c;font-size:13px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label style="font-weight:700;color:#374151;">وصف للمكان <span style="color:red;">*</span></label>
                        <textarea id="description" name="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  style="border:1.5px solid #E5E7EB;" required>{{ old('description', $agent->description) }}</textarea>
                        @error('description') <div style="color:#b91c1c;font-size:13px;">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- البطاقة 2: المستندات والملفات --}}
        <div style="border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);margin-bottom:16px;overflow:hidden;">
            <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid #D0D5DD;padding:10px 14px;display:flex;align-items:center;gap:8px;">
                <span style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid #FFD8A8;color:#92400E;font-weight:800;">2</span>
                <h6 style="margin:0;font-weight:800;color:#374151;">المستندات والملفات</h6>
            </div>
            <div style="padding:16px;">
                <div class="row g-3">

                    <div class="col-md-12">
                        <label style="font-weight:700;color:#374151;">شهادة الميلاد</label>
                        <input type="file" id="Birth_creature" name="Birth_creature"
                               class="form-control @error('Birth_creature') is-invalid @enderror"
                               style="border:1.5px solid #E5E7EB;" accept="image/png,image/gif,image/jpeg,application/pdf">
                        <div style="color:#6b7280;font-size:13px;">اتركه فارغًا إن لم ترغب بالتغيير.</div>
                        @if($agent->birth_certificate_path)
                            <div style="margin-top:6px;">
                                <a href="{{ Storage::url($agent->birth_certificate_path) }}" target="_blank">عرض الملف الحالي</a>
                            </div>
                        @endif
                        @error('Birth_creature') <div style="color:#b91c1c;font-size:13px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-12">
                        <label style="font-weight:700;color:#374151;">المؤهل العلمي</label>
                        <input type="file" id="qualification" name="qualification"
                               class="form-control @error('qualification') is-invalid @enderror"
                               style="border:1.5px solid #E5E7EB;" accept="image/png,image/gif,image/jpeg,application/pdf">
                        <div style="color:#6b7280;font-size:13px;">اتركه فارغًا إن لم ترغب بالتغيير.</div>
                        @if($agent->qualification_path)
                            <div style="margin-top:6px;">
                                <a href="{{ Storage::url($agent->qualification_path) }}" target="_blank">عرض الملف الحالي</a>
                            </div>
                        @endif
                        @error('qualification') <div style="color:#b91c1c;font-size:13px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-12">
                        <label style="font-weight:700;color:#374151;">صورة للمكان</label>
                        <input type="file" id="image" name="image"
                               class="form-control @error('image') is-invalid @enderror"
                               style="border:1.5px solid #E5E7EB;" accept="image/png,image/gif,image/jpeg">
                        <div style="color:#6b7280;font-size:13px;">اتركه فارغًا إن لم ترغب بالتغيير.</div>
                        @if($agent->location_image_path)
                            <div style="margin-top:6px;">
                                <a href="{{ Storage::url($agent->location_image_path) }}" target="_blank">عرض الملف الحالي</a>
                            </div>
                        @endif
                        @error('image') <div style="color:#b91c1c;font-size:13px;">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- الأزرار --}}
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <button type="submit" style="display:inline-flex;align-items:center;gap:6px;background:#FFF7EE;color:#92400E;border:1.5px solid #FFD8A8;border-radius:999px;padding:8px 18px;font-weight:800;">
                <i class="fa fa-save"></i> حفظ التغييرات
            </button>
            <a href="{{ route('insuranceAgents.index') }}" style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:8px 18px;font-weight:800;text-decoration:none;">
                إلغاء
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

    if(!cityId){ return; }

    $.ajax({
      url: '/get-Municipal/' + cityId,
      type: 'GET',
      dataType: 'json',
      success: function(data) {
        $municipal.empty().append('<option value="" disabled selected>اختر البلدية</option>');
        data.forEach(function(item){
          const opt = $('<option/>', { value: item.id, text: item.name });
          if (selectedId && String(selectedId) === String(item.id)) {
            opt.attr('selected', 'selected');
          }
          $municipal.append(opt);
        });
        $municipal.prop('disabled', false);
      },
      error: function() {
        $municipal.empty().append('<option value="" disabled selected>حدث خطأ أثناء التحميل</option>');
      }
    });
  }

  $(document).ready(function () {
    const currentCityId = '{{ old('cities_id', $agent->cities_id) }}';
    const currentMunicipalId = '{{ old('municipals_id', $agent->municipals_id) }}';
    if (currentCityId) {
      populateMunicipals(currentCityId, currentMunicipalId);
    }
    $('#cities_id').on('change', function () {
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
