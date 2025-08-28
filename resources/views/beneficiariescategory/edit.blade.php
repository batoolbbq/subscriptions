@extends('layouts.master')

@section('title', 'تعديل الفئة')

@section('css')
    {{-- خط + أيقونات --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container py-4"
        style="--brand:#F58220;--brand-600:#ff8f34;--brown:#8C5346;--ink:#1F2328;--muted:#6b7280;--line:#E5E7EB;
            --hdr1:#d95b00;--hdr2:#F58220;--hdr3:#FF8F34;--hdr4:#ffb066;
            --red-50:#fff1f1;--red-200:#ffc9c9;--red-700:#b42318;
            font-family:'Tajawal',system-ui,-apple-system,Segoe UI,Roboto,sans-serif;">

        {{-- العنوان + رجوع --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <h3 style="margin:0;font-weight:800;color:var(--brown);letter-spacing:.2px;">
                    تعديل الفئة: {{ $item->name }}
                </h3>
                {{-- <div style="color:var(--muted);font-size:14px;">عدّل بيانات الفئة ثم احفظ التغييرات.</div> --}}
            </div>
            <a href="{{ route('beneficiariescategory.index') }}"
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
                style="border:1.5px solid var(--red-200);background:var(--red-50);padding:12px;border-radius:14px;margin-bottom:16px;box-shadow:0 10px 28px rgba(0,0,0,.08);color:var(--red-700);">
                <div style="font-weight:800;margin-bottom:6px;">تحقق من الحقول التالية:</div>
                <ul style="margin:0;padding-inline-start:22px;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('beneficiariescategory.update', $item->id) }}">
            @csrf @method('PUT')

            {{-- البطاقة: بيانات الفئة --}}
            <div
                style="background:#fff;border:1.5px solid var(--line);border-radius:24px;box-shadow:0 18px 40px rgba(0,0,0,.12);margin-bottom:16px;overflow:hidden;">
                <div
                    style="background:linear-gradient(135deg,var(--hdr1) 0%,var(--hdr2) 35%,var(--hdr3) 70%,var(--hdr4) 100%);
                  color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                    <span
                        style="background:var(--hdr3);color:#fff;min-width:34px;height:34px;display:grid;place-items:center;border-radius:999px;font-size:.95rem;box-shadow:0 10px 22px rgba(245,130,32,.35);">1</span>
                    <h6 style="margin:0;font-weight:800;color:#fff;">بيانات الفئة</h6>
                </div>

                <div style="padding:22px 20px 26px;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label
                                style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">
                                اسم الفئة <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="text" name="name" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                value="{{ old('name', $item->name) }}" placeholder="اسم الفئة" required>
                        </div>

                        <div class="col-md-6">
                            <label
                                style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">
                                الكود (أرقام فقط حتى 5) <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="text" name="code" class="form-control"
                                style="width:100%;border:1px solid #d7dbe0;background:#fdfdfd;border-radius:999px;padding:12px 14px;font-size:1rem;outline:none;"
                                value="{{ old('code', $item->code) }}" placeholder="الكود" maxlength="5"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                        </div>
                        <div class="col-md-12">
                            <label
                                style="display:block;margin-bottom:6px;color:var(--muted);font-size:.95rem;font-weight:700;">الحالة</label>
                            <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;">
                                <label style="display:inline-flex;gap:6px;align-items:center;">
                                    <input type="radio" name="status" value="1"
                                        {{ old('status', $item->status) == 1 ? 'checked' : '' }}>
                                    <span>مفعّلة</span>
                                </label>
                                <label style="display:inline-flex;gap:6px;align-items:center;">
                                    <input type="radio" name="status" value="0"
                                        {{ old('status', $item->status) == 0 ? 'checked' : '' }}>
                                    <span>موقوفة</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

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

                <a href="{{ route('beneficiariescategory.index') }}"
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
