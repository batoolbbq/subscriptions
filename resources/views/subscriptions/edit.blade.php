@extends('layouts.master')

@section('title', 'تعديل الاشتراك')

@section('css')
    {{-- خط + أيقونات --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container py-4"
        style="--brand:#F58220;--brand-600:#ff8f34;--brown:#8C5346;--ink:#1F2328;
               --muted:#6b7280;--line:#E5E7EB;
               --hdr1:#d95b00;--hdr2:#F58220;--hdr3:#FF8F34;--hdr4:#ffb066;
               --red-50:#fff1f1;--red-200:#ffc9c9;--red-700:#b42318;
               font-family:'Tajawal',system-ui,-apple-system,Segoe UI,Roboto,sans-serif;">

        {{-- العنوان + رجوع --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <h3 style="margin:0;font-weight:800;color:var(--brown);letter-spacing:.2px;">
                    تعديل الاشتراك: {{ $subscription->name }}
                </h3>
            </div>
            <a href="{{ route('subscriptions.index') }}"
                style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;
                  background:#fff;color:var(--ink);border:1.5px solid var(--line);
                  border-radius:999px;padding:10px 16px;font-weight:900;text-decoration:none;
                  box-shadow:0 8px 18px rgba(0,0,0,.06);">
                <i class="fa-solid fa-arrow-right"></i> رجوع للقائمة
            </a>
        </div>

        {{-- الأخطاء --}}
        @if ($errors->any())
            <div
                style="border:1.5px solid var(--red-200);background:var(--red-50);
                       padding:12px;border-radius:14px;margin-bottom:16px;
                       box-shadow:0 10px 28px rgba(0,0,0,.08);color:var(--red-700);">
                <div style="font-weight:800;margin-bottom:6px;">تحقق من الحقول التالية:</div>
                <ul style="margin:0;padding-inline-start:22px;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('subscriptions.update', $subscription->id) }}">
            @csrf @method('PUT')

            {{-- البطاقة: بيانات الاشتراك --}}
            <div
                style="background:#fff;border:1.5px solid var(--line);
                       border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);
                       margin-bottom:16px;overflow:hidden;">
                <div
                    style="background:linear-gradient(135deg,var(--hdr1) 0%,var(--hdr2) 35%,var(--hdr3) 70%,var(--hdr4) 100%);
                          color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                    <span
                        style="background:var(--hdr3);color:#fff;min-width:34px;height:34px;
                               display:grid;place-items:center;border-radius:999px;font-size:.95rem;
                               box-shadow:0 10px 22px rgba(245,130,32,.35);">1</span>
                    <h6 style="margin:0;font-weight:800;color:#fff;">بيانات الاشتراك</h6>
                </div>

                <div style="padding:22px 20px 26px;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label
                                style="display:block;margin-bottom:6px;color:var(--muted);
                                          font-size:.95rem;font-weight:700;">
                                اسم الاشتراك <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="text" name="name" class="form-control"
                                style="width:100%;border:1.5px solid #d7dbe0;background:#fdfdfd;
                                       border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                value="{{ old('name', $subscription->name) }}" placeholder="اسم الاشتراك" required>
                        </div>

                        <div class="col-md-6">
                            <label
                                style="display:block;margin-bottom:6px;color:var(--muted);
                                          font-size:.95rem;font-weight:700;">
                                فئة المستفيدين <span style="color:#ef4444;">*</span>
                            </label>
                            <select name="beneficiaries_categories_id" class="form-control"
                                style="width:100%;border:1.5px solid #d7dbe0;background:#fdfdfd;
                                       border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                required>
                                <option value="">اختر الفئة</option>
                                @foreach ($beneficiariesCategories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('beneficiaries_categories_id', $subscription->beneficiaries_categories_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- البطاقة: تفاصيل القيم --}}
            @foreach ($types as $type)
                @php
                    $existingValue = $subscription->values->firstWhere('subscription_type', $type->id);
                @endphp

                <div
                    style="background:#fff;border:1.5px solid var(--line);
                           border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);
                           margin-bottom:16px;overflow:hidden;">
                    <div
                        style="background:linear-gradient(135deg,var(--hdr1) 0%,var(--hdr2) 35%,var(--hdr3) 70%,var(--hdr4) 100%);
                              color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                        <span
                            style="background:var(--hdr3);color:#fff;min-width:34px;height:34px;
                                   display:grid;place-items:center;border-radius:999px;font-size:.95rem;
                                   box-shadow:0 10px 22px rgba(245,130,32,.35);">
                            <i class="fa-solid fa-list"></i>
                        </span>
                        <h6 style="margin:0;font-weight:800;color:#fff;">{{ $type->name }}</h6>
                    </div>

                    <div style="padding:22px 20px 26px;">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label
                                    style="display:block;margin-bottom:6px;color:var(--muted);
                                              font-size:.95rem;font-weight:700;">نوع
                                    القيمة</label>
                                <select name="types[{{ $type->id }}][is_percentage]" class="form-control"
                                    style="width:100%;border:1.5px solid #d7dbe0;background:#fdfdfd;
                                           border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;">
                                    <option value="" disabled {{ is_null($existingValue) ? 'selected' : '' }}>اختر
                                    </option>
                                    <option value="1" {{ $existingValue?->is_percentage == '1' ? 'selected' : '' }}>
                                        نسبة</option>
                                    <option value="0" {{ $existingValue?->is_percentage == '0' ? 'selected' : '' }}>
                                        قيمة ثابتة</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label
                                    style="display:block;margin-bottom:6px;color:var(--muted);
                                              font-size:.95rem;font-weight:700;">القيمة</label>
                                <input type="number" step="any" min="0"
                                    name="types[{{ $type->id }}][value]"
                                    value="{{ old("types.$type->id.value", $existingValue?->value) }}" class="form-control"
                                    style="width:100%;border:1.5px solid #d7dbe0;background:#fdfdfd;
                                           border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;">
                            </div>
                            <div class="col-md-4">
                                <label
                                    style="display:block;margin-bottom:6px;color:var(--muted);
                                              font-size:.95rem;font-weight:700;">المدة
                                    (بالأشهر)</label>
                                <input type="number" min="0" name="types[{{ $type->id }}][duration]"
                                    value="{{ old("types.$type->id.duration", $existingValue?->duration) }}"
                                    class="form-control"
                                    style="width:100%;border:1.5px solid #d7dbe0;background:#fdfdfd;
                                           border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- الأزرار --}}
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button type="submit"
                    style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;
                         background:var(--brand);color:#fff;padding:10px 18px;border-radius:999px;
                         font-weight:900;text-decoration:none;box-shadow:0 12px 26px rgba(245,130,32,.30);"
                    onmouseover="this.style.filter='brightness(1.05)';this.style.transform='translateY(-1px)';"
                    onmouseout="this.style.filter='none';this.style.transform='none';">
                    حفظ التعديلات <i class="fa-solid fa-floppy-disk"></i>
                </button>

                <a href="{{ route('subscriptions.index') }}"
                    style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;
                    background:#fff;color:var(--ink);border:1.5px solid var(--line);
                    border-radius:999px;padding:10px 18px;font-weight:900;text-decoration:none;"
                    onmouseover="this.style.backgroundColor='#f9fafb';this.style.transform='translateY(-1px)';"
                    onmouseout="this.style.backgroundColor='#fff';this.style.transform='none';">
                    إلغاء <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
        </form>
    </div>
@endsection
