@extends('layouts.master')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
@endsection

@section('title', 'قائمة الاشتراكات')

@section('content')
    <div class="container py-4"
        style="font-family: 'Tajawal', system-ui, -apple-system, Segoe UI, Roboto, sans-serif; color:#1F2328;">

        {{-- العنوان + زر إضافة --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h3 style="margin:0;font-weight:800;color:#8C5346;">قائمة الاشتراكات</h3>
            <a href="{{ route('subscriptions.create') }}"
                style="all:unset;display:inline-flex;align-items:center;gap:8px;cursor:pointer;
                       background:#F58220;color:#fff;padding:12px 22px;border-radius:999px;
                       font-weight:900;text-decoration:none;box-shadow:0 12px 26px rgba(245,130,32,.30);">
                <i class="fa-solid fa-plus"></i> إضافة اشتراك جديد
            </a>
        </div>

        {{-- الجدول --}}
        <div class="table-responsive"
            style="border:1.5px solid #E5E7EB;border-radius:14px;overflow:hidden;
                   box-shadow:0 10px 28px rgba(0,0,0,.06);">
            <table style="width:100%;margin:0;color:#111827;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th
                            style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                                   color:#4b5563;font-weight:800;font-size:1.05rem;
                                   border-bottom:1.5px solid #E5E7EB;padding:14px;text-align:right;">
                            #</th>
                        <th
                            style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                                   color:#4b5563;font-weight:800;font-size:1.05rem;
                                   border-bottom:1.5px solid #E5E7EB;padding:14px;text-align:right;">
                            الفئة</th>
                        <th
                            style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                                   color:#4b5563;font-weight:800;font-size:1.05rem;
                                   border-bottom:1.5px solid #E5E7EB;padding:14px;text-align:right;">
                            اسم الاشتراك</th>
                        <th
                            style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                                   color:#4b5563;font-weight:800;font-size:1.05rem;
                                   border-bottom:1.5px solid #E5E7EB;padding:14px;text-align:right;">
                            الحالة</th>
                        <th
                            style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                                   color:#4b5563;font-weight:800;font-size:1.05rem;
                                   border-bottom:1.5px solid #E5E7EB;padding:14px;text-align:right;">
                            تفاصيل القيم</th>
                        <th
                            style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);
                                   color:#4b5563;font-weight:800;font-size:1.05rem;
                                   border-bottom:1.5px solid #E5E7EB;padding:14px;text-align:right;">
                            إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        <tr style="border-top:1px solid #E5E7EB;">
                            <td style="padding:14px;color:#64748b;font-weight:700;">{{ $subscription->id }}</td>
                            <td style="padding:14px;">{{ $subscription->beneficiariesCategory->name ?? '—' }}</td>
                            <td style="padding:14px;font-weight:700;color:#374151;">{{ $subscription->name }}</td>
                            <td style="padding:14px;">
                                @if ($subscription->status)
                                    <span
                                        style="display:inline-block;padding:6px 12px;
                                                 background:#e9fbf2;color:#10734a;
                                                 border:1.5px solid #86efac;border-radius:999px;font-weight:800;">
                                        نشط
                                    </span>
                                @else
                                    <span
                                        style="display:inline-block;padding:6px 12px;
                                                 background:#eff2f6;color:#374151;
                                                 border:1.5px solid #d1d5db;border-radius:999px;font-weight:800;">
                                        غير نشط
                                    </span>
                                @endif
                            </td>
                            <td style="padding:14px;">
                                @if ($subscription->values->isNotEmpty())
                                    <ul style="margin:0;padding-inline-start:18px;">
                                        @foreach ($subscription->values as $value)
                                            <li>
                                                {{ $value->type?->name ?? '—' }} :
                                                {{ $value->is_percentage ? $value->value . '%' : $value->value . ' دينار' }}
                                                ({{ $value->duration }} يوم)
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span style="color:#6b7280;">لا توجد قيم</span>
                                @endif
                            </td>
                            <td style="padding:14px;">
                                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                    <a href="{{ route('subscriptions.show', $subscription->id) }}"
                                        style="display:inline-flex;align-items:center;gap:6px;
                                               background:#FFF7EE;border:1.5px solid #FFD8A8;
                                               color:#92400E;padding:6px 14px;border-radius:999px;
                                               font-weight:800;text-decoration:none;">
                                        <i class="fa-solid fa-eye"></i> عرض
                                    </a>
                                    <a href="{{ route('subscriptions.edit', $subscription->id) }}"
                                        style="display:inline-flex;align-items:center;gap:6px;
                                               background:#fff7ee;color:#f58220;
                                               border:1.5px solid #f58220;padding:6px 14px;
                                               border-radius:999px;font-weight:800;text-decoration:none;">
                                        <i class="fa-solid fa-pen-to-square"></i> تعديل
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:20px;color:#6b7280;">
                                لا توجد اشتراكات
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
