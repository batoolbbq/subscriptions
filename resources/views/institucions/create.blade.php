@extends('layouts.master')

@section('title', 'إضافة جهة عمل')

@section('content')
    <div class="container py-4" style="font-family: sans-serif;">

        {{-- العنوان وزر الرجوع --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <h3 style="margin:0;font-weight:800;color:#111827;">إضافة جهة عمل</h3>
                <div style="color:#6b7280;font-size:14px;">اختر نوع جهة العمل أولاً، ثم أكمل البيانات المطلوبة.</div>
            </div>
            <a href="{{ route('institucions.index') }}"
                style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:6px 14px;font-weight:700;text-decoration:none;">
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

        <form action="{{ route('institucions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- البطاقة 1: الأساسيات --}}
            <div
                style="border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);margin-bottom:16px;overflow:hidden;">
                <div
                    style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid #D0D5DD;padding:10px 14px;display:flex;align-items:center;gap:8px;">
                    <span
                        style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid #FFD8A8;color:#92400E;font-weight:800;">1</span>
                    <h6 style="margin:0;font-weight:800;color:#374151;">أساسيات جهة العمل</h6>
                </div>
                <div style="padding:16px;">
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <label style="font-weight:700;color:#374151;">نوع جهة العمل <span
                                    style="color:red;">*</span></label>
                            @php
                                $isWakeel = auth()->user()->hasRole('Wakeel');
                                // لو تحب تمررها من الكنترولر، استخدم المتغير $publicCategoryIds
                                $publicCategoryIds = isset($publicCategoryIds) ? $publicCategoryIds : [19];
                            @endphp

                            <select id="work_categories_id" name="work_categories_id" class="form-control"
                                style="border:1.5px solid #E5E7EB;" required>
                                <option value="" disabled {{ old('work_categories_id') ? '' : 'selected' }}>— اختر
                                    النوع —</option>

                                @foreach ($workCategories as $wc)
                                    @php
                                        // إخفاء الجهات العامة عن الوكيل
                                        $isPublicForWakeel = $isWakeel && in_array($wc->id, $publicCategoryIds);
                                        $requires = in_array($wc->id, $requiresDocsIds ?? []) ? 1 : 0;
                                    @endphp

                                    @continue($isPublicForWakeel)

                                    <option value="{{ $wc->id }}" data-requires="{{ $requires }}"
                                        {{ (string) old('work_categories_id') === (string) $wc->id ? 'selected' : '' }}>
                                        {{ $wc->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div style="color:#6b7280;font-size:13px;">ستظهر حقول السجل والملفات تلقائيًا إذا كان النوع
                                يتطلبها.</div>
                        </div>
                        <div class="col-lg-7">
                            <label style="font-weight:700;color:#374151;">اسم جهة العمل <span
                                    style="color:red;">*</span></label>
                            <input type="text" name="name" class="form-control" style="border:1.5px solid #E5E7EB;"
                                value="{{ old('name') }}" placeholder="أدخل اسم الجهة" required>
                        </div>
                        <div class="col-md-6">
                            <label style="font-weight:700;color:#374151;">الاشتراك <span style="color:red;">*</span></label>
                            <select name="subscriptions_id" class="form-control" style="border:1.5px solid #E5E7EB;"
                                required>
                                <option value="" disabled selected>— اختر الاشتراك —</option>
                                @foreach ($subscriptions as $s)
                                    <option value="{{ $s->id }}"
                                        {{ old('subscriptions_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name ?? 'اشتراك #' . $s->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if ($showAgentSelect)
                            <div class="col-md-6">
                                <label style="font-weight:700;color:#374151;">الوكيل التأميني (اختياري)</label>
                                <select name="insurance_agent_id" class="form-control" style="border:1.5px solid #E5E7EB;">
                                    <option value="">— اختياري —</option>
                                    @foreach ($agents as $a)
                                        <option value="{{ $a->id }}"
                                            {{ (string) old('insurance_agent_id', (string) $preselectedAgentId) === (string) $a->id ? 'selected' : '' }}>
                                            {{ $a->name ?? 'Agent #' . $a->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="insurance_agent_id" value="{{ $preselectedAgentId }}">
                        @endif

                    </div>
                </div>
            </div>

            {{-- البطاقة 2: بيانات السجل --}}
            <div id="docs-card"
                style="display:none;border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);margin-bottom:16px;overflow:hidden;">
                <div
                    style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid #D0D5DD;padding:10px 14px;display:flex;align-items:center;gap:8px;">
                    <span
                        style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid #FFD8A8;color:#92400E;font-weight:800;">2</span>
                    <h6 style="margin:0;font-weight:800;color:#374151;">بيانات السجل التجاري والترخيص</h6>
                </div>
                <div style="padding:16px;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label style="font-weight:700;color:#374151;">الرقم التجاري</label>
                            <input type="text" name="commercial_number" class="form-control"
                                style="border:1.5px solid #E5E7EB;" value="{{ old('commercial_number') }}"
                                placeholder="مثال: 123456789">
                        </div>
                        <div class="col-md-6">
                            <label style="font-weight:700;color:#374151;">ملف الترخيص</label>
                            <input type="file" name="license_number" class="form-control"
                                style="border:1.5px solid #E5E7EB;" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-6">
                            <label style="font-weight:700;color:#374151;">ملف السجل التجاري</label>
                            <input type="file" name="commercial_record" class="form-control"
                                style="border:1.5px solid #E5E7EB;" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
            </div>
             <div style="border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);margin-bottom:16px;overflow:hidden;">
            <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid #D0D5DD;padding:10px 14px;display:flex;align-items:center;gap:8px;">
                <span style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid #FFD8A8;color:#92400E;font-weight:800;">3</span>
                <h6 style="margin:0;font-weight:800;color:#374151;">استيراد بيانات الموظفين / الحسابات</h6>
            </div>
            <div style="padding:16px;">
                <div class="mb-3">
                    <label for="excel_sheet" class="form-label" style="font-weight:700;color:#374151;">شيت الإكسل (اختياري)</label>
                    <input type="file" name="excel_sheet" id="excel_sheet" class="form-control" accept=".xlsx,.xls,.csv">
                   
                </div>
            </div>
        </div>

            {{-- الأزرار --}}
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button type="submit"
                    style="display:inline-flex;align-items:center;gap:6px;background:#FFF7EE;color:#92400E;border:1.5px solid #FFD8A8;border-radius:999px;padding:8px 18px;font-weight:800;">
                    <i class="fa fa-save"></i> حفظ الجهة
                </button>
                <a href="{{ route('institucions.index') }}"
                    style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:8px 18px;font-weight:800;text-decoration:none;">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const select = document.getElementById('work_categories_id');
            const docsCard = document.getElementById('docs-card');

            function toggleDocs() {
                const opt = select.options[select.selectedIndex];
                const requires = opt ? opt.getAttribute('data-requires') === '1' : false;
                docsCard.style.display = requires ? '' : 'none';
            }
            select.addEventListener('change', toggleDocs);
            toggleDocs();
        })();
    </script>
@endpush
