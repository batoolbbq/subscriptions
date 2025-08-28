@extends('layouts.master')

@section('title', 'عرض الاشتراك')

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

        {{-- العنوان + زر الرجوع --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <div>
                <h3 style="margin:0;font-weight:800;color:var(--brown);letter-spacing:.2px;">تفاصيل الاشتراك</h3>
            </div>
            <a href="{{ route('subscriptions.index') }}"
                style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;
              background:#fff;color:var(--ink);border:1.5px solid var(--line);
              border-radius:999px;padding:10px 16px;font-weight:900;text-decoration:none;
              box-shadow:0 8px 18px rgba(0,0,0,.06);">
                <i class="fa-solid fa-arrow-right"></i> رجوع للقائمة
            </a>
        </div>

        {{-- بطاقة بيانات الاشتراك --}}
        <div
            style="background:#fff;border:1.5px solid var(--line);border-radius:24px;
              box-shadow:0 18px 40px rgba(0,0,0,.12);overflow:hidden;">

            {{-- الترويسة --}}
            <div
                style="background:linear-gradient(135deg,var(--hdr1) 0%,var(--hdr2) 35%,var(--hdr3) 70%,var(--hdr4) 100%);
                color:#fff;padding:14px 18px;display:flex;align-items:center;gap:10px;font-weight:800;">
                <span
                    style="background:var(--hdr3);color:#fff;min-width:34px;height:34px;
                   display:grid;place-items:center;border-radius:999px;font-size:.95rem;
                   box-shadow:0 10px 22px rgba(245,130,32,.35);">
                    <i class="fa-solid fa-file-contract"></i>
                </span>
                <h6 style="margin:0;font-weight:800;color:#fff;">بيانات الاشتراك</h6>
            </div>

            {{-- المحتوى --}}
            <div
                style="padding:22px 20px 26px;display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;">

                <div>
                    <label style="color:var(--muted);font-size:.95rem;font-weight:700;display:block;margin-bottom:6px;">
                        الاسم
                    </label>
                    <div style="font-weight:800;color:var(--ink);">{{ $subscription->name }}</div>
                </div>

                <div>
                    <label style="color:var(--muted);font-size:.95rem;font-weight:700;display:block;margin-bottom:6px;">
                        الفئة
                    </label>
                    <div style="font-weight:800;color:var(--ink);">
                        {{ $subscription->beneficiariesCategory->name ?? '—' }}
                    </div>
                </div>

                <div>
                    <label style="color:var(--muted);font-size:.95rem;font-weight:700;display:block;margin-bottom:6px;">
                        الحالة
                    </label>
                    @if ($subscription->status)
                        <span
                            style="display:inline-flex;align-items:center;gap:6px;
                           background:var(--green-50);color:var(--green-700);
                           border:1.5px solid #86efac;border-radius:999px;
                           padding:6px 14px;font-weight:800;">
                            <i class="fa-solid fa-circle-check"></i> نشط
                        </span>
                    @else
                        <span
                            style="display:inline-flex;align-items:center;gap:6px;
                           background:var(--gray-50);color:var(--gray-700);
                           border:1.5px solid #d1d5db;border-radius:999px;
                           padding:6px 14px;font-weight:800;">
                            <i class="fa-solid fa-circle-xmark"></i> غير نشط
                        </span>
                    @endif
                </div>

                <div>
                    <label style="color:var(--muted);font-size:.95rem;font-weight:700;display:block;margin-bottom:6px;">
                        تاريخ الإنشاء
                    </label>
                    <div style="font-weight:800;color:var(--ink);">
                        {{ $subscription->created_at?->format('Y-m-d H:i') }}
                    </div>
                </div>

                <div>
                    <label style="color:var(--muted);font-size:.95rem;font-weight:700;display:block;margin-bottom:6px;">
                        آخر تحديث
                    </label>
                    <div style="font-weight:800;color:var(--ink);">
                        {{ $subscription->updated_at?->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>

            {{-- تفاصيل القيم --}}
            <div style="padding:22px 20px 26px;border-top:1.5px solid var(--line);">
                <h5 style="font-weight:800;color:var(--ink);margin-bottom:12px;">تفاصيل القيم</h5>
                @if ($subscription->values->isNotEmpty())
                    <table style="width:100%;border-collapse:collapse;border:1.5px solid var(--line);">
                        <thead>
                            <tr>
                                <th
                                    style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                                           padding:10px;border-bottom:1.5px solid var(--line);
                                           text-align:right;font-weight:800;">
                                    النوع</th>
                                <th
                                    style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                                           padding:10px;border-bottom:1.5px solid var(--line);
                                           text-align:right;font-weight:800;">
                                    المدة</th>
                                <th
                                    style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                                           padding:10px;border-bottom:1.5px solid var(--line);
                                           text-align:right;font-weight:800;">
                                    القيمة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscription->values as $value)
                                <tr style="border-top:1px solid var(--line);">
                                    <td style="padding:10px;">{{ $value->type?->name ?? '—' }}</td>
                                    <td style="padding:10px;">{{ $value->duration }} يوم</td>
                                    <td style="padding:10px;">
                                        {{ $value->is_percentage ? $value->value . '%' : $value->value . ' دينار' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="color:var(--muted);">لا توجد قيم مسجلة</p>
                @endif
            </div>

            {{-- الأزرار --}}
            <div style="padding:16px;border-top:1.5px solid var(--line);display:flex;gap:8px;flex-wrap:wrap;">
                <form action="{{ route('subscriptions.toggleStatus', $subscription->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;
                               {{ $subscription->status
                                   ? 'background:var(--red-50);color:var(--red-700);border:1.5px solid var(--red-200);'
                                   : 'background:var(--green-50);color:var(--green-700);border:1.5px solid #86efac;' }}
                               border-radius:999px;padding:10px 18px;font-weight:900;">
                        <i class="fa-solid {{ $subscription->status ? 'fa-ban' : 'fa-check' }}"></i>
                        {{ $subscription->status ? 'إلغاء التفعيل' : 'تفعيل الاشتراك' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
