@extends('layouts.master')
@section('title', 'عرض الاشتراك')

@section('content')
<div class="container py-4">

    {{-- العنوان + أزرار --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <div>
            <h3 style="margin:0;font-weight:800;color:#111827;">{{ $subscription->name }}</h3>
            <div style="color:#6b7280;font-size:0.85rem;">
                تم الإنشاء: {{ $subscription->created_at?->format('Y-m-d H:i') }} • آخر تحديث: {{ $subscription->updated_at?->format('Y-m-d H:i') }}
            </div>
        </div>
        <div style="display:flex;gap:8px;">
            {{-- زر تفعيل/إيقاف --}}
            <form action="{{ route('subscriptions.toggleStatus', $subscription->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit"
                    style="padding:8px 14px;
               border-radius:999px;
               font-weight:800;
               border:2px solid transparent;
               cursor:pointer;
               {{ $subscription->status 
                    ? 'background:#fff1f1;color:#b42318;border-color:#ffc9c9;' 
                    : 'background:#e9fbf2;color:#10734a;border-color:#a7f3d0;' }}">
                    {{ $subscription->status ? 'إلغاء التفعيل' : 'تفعيل الاشتراك' }}
                </button>

            </form>

            <a href="{{ route('subscriptions.index') }}"
                style="background:#f3f4f6;border:2px solid #e5e7eb;color:#374151;padding:8px 14px;border-radius:999px;font-weight:800;text-decoration:none;">
                رجوع
            </a>
        </div>
    </div>

    {{-- الكارت الرئيسي --}}
    <div style="border:2px solid #e5e7eb;border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);overflow:hidden;">
        <div style="padding:20px;">

            {{-- الفئة --}}
            <div style="margin-bottom:14px;">
                <div style="color:#6b7280;font-size:0.85rem;margin-bottom:4px;">الفئة </div>
                <div style="font-weight:600;">{{ $subscription->beneficiariesCategory->name ?? '—' }}</div>
            </div>

            {{-- الحالة --}}
            <div style="margin-bottom:14px;">
                <div style="color:#6b7280;font-size:0.85rem;margin-bottom:4px;">الحالة</div>
                @if($subscription->status)
                <span style="display:inline-block;padding:6px 12px;background:#e9fbf2;color:#10734a;border:2px solid #a7f3d0;border-radius:8px;font-weight:800;">
                    نشط
                </span>
                @else
                <span style="display:inline-block;padding:6px 12px;background:#eff2f6;color:#374151;border:2px solid #d1d5db;border-radius:8px;font-weight:800;">
                    غير نشط
                </span>
                @endif
            </div>

            {{-- تفاصيل القيم --}}
            <div style="margin-top:20px;">
                <h5 style="font-weight:800;color:#111827;margin-bottom:12px;">تفاصيل القيم</h5>
                @if($subscription->values->isNotEmpty())
                <table style="width:100%;border-collapse:collapse;border:1px solid #e5e7eb;">
                    <thead>
                        <tr>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);padding:10px;border-bottom:2px solid #e5e7eb;text-align:right;font-weight:800;">النوع</th>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);padding:10px;border-bottom:2px solid #e5e7eb;text-align:right;font-weight:800;">المدة</th>
                            <th style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);padding:10px;border-bottom:2px solid #e5e7eb;text-align:right;font-weight:800;">القيمة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscription->values as $value)
                        <tr style="border-top:1px solid #e5e7eb;">
                            <td style="padding:10px;">{{ $value->type?->name ?? '—' }}</td>
                            <td style="padding:10px;">{{ $value->duration }} يوم</td>
                            <td style="padding:10px;">{{ $value->is_percentage ? $value->value . '%' : $value->value . ' دينار' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p style="color:#6b7280;">لا توجد قيم مسجلة</p>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection