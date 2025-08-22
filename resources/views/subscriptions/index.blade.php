@extends('layouts.master')

@section('title', 'قائمة الاشتراكات')

@section('content')
<div class="container py-4">

    {{-- العنوان + زر إضافة --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h3 style="margin:0;font-weight:800;color:#111827;">قائمة الاشتراكات</h3>
        <a href="{{ route('subscriptions.create') }}" 
           style="background:#1d4ed8;color:#fff;border:none;padding:10px 18px;border-radius:12px;font-weight:700;text-decoration:none;">
            إضافة اشتراك جديد <i class="fa fa-plus"></i>
        </a>
    </div>

    {{-- الكارت --}}
    <div style="border:2px solid #e5e7eb;border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;color:#111827;border:2px solid #e5e7eb;">
                <thead>
                    <tr>
                        <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;font-size:1.05rem;border-bottom:2px solid #e5e7eb;padding:14px;text-align:right;">#</th>
                        <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;font-size:1.05rem;border-bottom:2px solid #e5e7eb;padding:14px;text-align:right;">الفئة</th>
                        <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;font-size:1.05rem;border-bottom:2px solid #e5e7eb;padding:14px;text-align:right;">اسم الاشتراك</th>
                        <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;font-size:1.05rem;border-bottom:2px solid #e5e7eb;padding:14px;text-align:right;">الحالة</th>
                        <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;font-size:1.05rem;border-bottom:2px solid #e5e7eb;padding:14px;text-align:right;">تفاصيل القيم</th>
                        <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4b5563;font-weight:800;font-size:1.05rem;border-bottom:2px solid #e5e7eb;padding:14px;text-align:right;">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        <tr style="border-top:1px solid #e5e7eb;">
                            <td style="padding:14px;color:#64748b;font-weight:700;">{{ $subscription->id }}</td>
                            <td style="padding:14px;">{{ $subscription->beneficiariesCategory->name ?? '—' }}</td>
                            <td style="padding:14px;font-weight:600;">{{ $subscription->name }}</td>
                            <td style="padding:14px;">
                                @if ($subscription->status)
                                    <span style="display:inline-block;padding:6px 12px;background:#e9fbf2;color:#10734a;border:2px solid #a7f3d0;border-radius:8px;font-weight:800;">
                                        نشط
                                    </span>
                                @else
                                    <span style="display:inline-block;padding:6px 12px;background:#eff2f6;color:#374151;border:2px solid #d1d5db;border-radius:8px;font-weight:800;">
                                        غير نشط
                                    </span>
                                @endif
                            </td>
                            <td style="padding:14px;">
                                @if ($subscription->values->isNotEmpty())
                                    <ul style="margin:0;padding-left:18px;">
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
                                       style="background:#f3f6ff;border:2px solid #cfd8ff;color:#0f172a;padding:6px 12px;border-radius:999px;font-weight:800;text-decoration:none;">
                                        عرض <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('subscriptions.edit', $subscription->id) }}" 
                                       style="background:#fff5e6;border:2px solid #ffd8a8;color:#92400e;padding:6px 12px;border-radius:999px;font-weight:800;text-decoration:none;">
                                        تعديل <i class="fa fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:20px;color:#6b7280;">لا توجد اشتراكات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
