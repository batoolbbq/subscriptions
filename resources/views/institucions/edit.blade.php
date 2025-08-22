{{-- resources/views/institucions/edit.blade.php --}}
@extends('layouts.master')

@section('title', 'تعديل جهة عمل')

@section('content')
<div class="container py-4">

    {{-- العنوان --}}
    <div class="row mb-3" style="align-items:center;">
        <div class="col">
            <h3 style="font-weight:800;color:#111827;margin-bottom:4px;">تعديل جهة عمل</h3>
            <div style="color:#6b7280;font-size:0.875rem;">
                عدّل بيانات جهة العمل حسب الحاجة.
            </div>
        </div>
        <div class="col-auto">
            <a href="{{ route('institucions.show', $institucion) }}" 
               style="border:2px solid #d1d5db;padding:6px 14px;border-radius:12px;text-decoration:none;color:#374151;font-weight:600;background:#fff;">
                رجوع للتفاصيل
            </a>
        </div>
    </div>

    {{-- الأخطاء --}}
    @if ($errors->any())
        <div style="background:#fff1f1;border:1px solid #ffc9c9;border-radius:10px;padding:14px;color:#b42318;margin-bottom:20px;">
            <div style="font-weight:700;margin-bottom:6px;">تحقق من الحقول التالية:</div>
            <ul style="margin:0;padding-left:18px;">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('institucions.update', $institucion) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- البطاقة 1 --}}
        <div style="border:2px solid #e5e7eb;border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);margin-bottom:24px;">
            <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);padding:10px 16px;border-bottom:2px solid #e5e7eb;border-radius:14px 14px 0 0;display:flex;align-items:center;gap:8px;">
                <span style="background:#1d4ed8;color:#fff;padding:4px 10px;border-radius:999px;font-weight:800;">1</span>
                <h6 style="margin:0;font-weight:700;color:#374151;">أساسيات جهة العمل</h6>
            </div>
            <div style="padding:18px;">

                <div class="row g-3">
                    {{-- نوع جهة العمل --}}
                    <div class="col-md-6">
                        <label style="font-weight:600;">نوع جهة العمل <span style="color:red;">*</span></label>
                        <select id="work_categories_id" name="work_categories_id" class="form-select" required>
                            <option value="" disabled>— اختر النوع —</option>
                            @foreach ($workCategories as $wc)
                                @php $requires = in_array($wc->id, $requiresDocsIds ?? []) ? 1 : 0; @endphp
                                <option value="{{ $wc->id }}" data-requires="{{ $requires }}"
                                    {{ old('work_categories_id', $institucion->work_categories_id) == $wc->id ? 'selected' : '' }}>
                                    {{ $wc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- اسم جهة العمل --}}
                    <div class="col-md-6">
                        <label style="font-weight:600;">اسم جهة العمل <span style="color:red;">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $institucion->name) }}" required>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    {{-- الاشتراك --}}
                    <div class="col-md-6">
                        <label style="font-weight:600;">الاشتراك <span style="color:red;">*</span></label>
                        <select name="subscriptions_id" class="form-select" required>
                            <option value="" disabled>— اختر الاشتراك —</option>
                            @foreach ($subscriptions as $s)
                                <option value="{{ $s->id }}"
                                    {{ old('subscriptions_id', $institucion->subscriptions_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->name ?? 'اشتراك #' . $s->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- الوكيل --}}
                    <div class="col-md-6">
                        <label style="font-weight:600;">الوكيل التأميني (اختياري)</label>
                        <select name="insurance_agent_id" class="form-select">
                            <option value="">— اختياري —</option>
                            @foreach ($agents as $a)
                                <option value="{{ $a->id }}"
                                    {{ old('insurance_agent_id', $institucion->insurance_agent_id) == $a->id ? 'selected' : '' }}>
                                    {{ $a->name ?? 'Agent #' . $a->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>

        {{-- البطاقة 2 --}}
        <div id="docs-card" style="display:none;border:2px solid #e5e7eb;border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);margin-bottom:24px;">
            <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);padding:10px 16px;border-bottom:2px solid #e5e7eb;border-radius:14px 14px 0 0;display:flex;align-items:center;gap:8px;">
                <span style="background:#1d4ed8;color:#fff;padding:4px 10px;border-radius:999px;font-weight:800;">2</span>
                <h6 style="margin:0;font-weight:700;color:#374151;">بيانات السجل التجاري والترخيص</h6>
            </div>
            <div style="padding:18px;">
                <div class="row g-3">
                    {{-- الرقم التجاري --}}
                    <div class="col-md-6">
                        <label style="font-weight:600;">الرقم التجاري</label>
                        <input type="text" name="commercial_number" class="form-control" value="{{ old('commercial_number', $institucion->commercial_number) }}">
                    </div>

                    {{-- ملف الترخيص --}}
                    <div class="col-md-6">
                        <label style="font-weight:600;">ملف الترخيص</label>
                        @if ($institucion->license_number)
                            <div class="mb-1"><a href="{{ Storage::url($institucion->license_number) }}" target="_blank" style="color:#1d4ed8;">عرض الملف الحالي</a></div>
                        @endif
                        <input type="file" name="license_number" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    {{-- ملف السجل --}}
                    <div class="col-md-6">
                        <label style="font-weight:600;">ملف السجل التجاري</label>
                        @if ($institucion->commercial_record)
                            <div class="mb-1"><a href="{{ Storage::url($institucion->commercial_record) }}" target="_blank" style="color:#1d4ed8;">عرض الملف الحالي</a></div>
                        @endif
                        <input type="file" name="commercial_record" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
            </div>
        </div>

        {{-- أزرار --}}
        <div style="display:flex;gap:10px;">
            <button type="submit" style="background:#1d4ed8;color:#fff;border:none;padding:10px 20px;border-radius:12px;font-weight:700;">حفظ التعديلات</button>
            <a href="{{ route('institucions.show', $institucion) }}" style="border:2px solid #d1d5db;padding:8px 18px;border-radius:12px;text-decoration:none;color:#374151;font-weight:600;background:#fff;">
                إلغاء
            </a>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const select=document.getElementById('work_categories_id');
    const docsCard=document.getElementById('docs-card');
    function toggleDocs(){
        const opt=select.options[select.selectedIndex];
        const requires=opt?opt.getAttribute('data-requires')==='1':false;
        docsCard.style.display=requires?'':'none';
        const commercial=document.querySelector('[name="commercial_number"]');
        const licFile=document.querySelector('[name="license_number"]');
        const crFile=document.querySelector('[name="commercial_record"]');
        [commercial,licFile,crFile].forEach(el=>{
            if(!el)return;
            if(requires)el.setAttribute('required','required');
            else el.removeAttribute('required');
        });
    }
    select.addEventListener('change',toggleDocs);
    toggleDocs();
})();
</script>
@endpush
