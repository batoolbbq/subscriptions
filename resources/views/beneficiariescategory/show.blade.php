@extends('layouts.master')

@section('title', 'تفاصيل الفئة')

@section('content')
<div class="container py-4" style="font-family: sans-serif;">

  {{-- العنوان + زر الرجوع --}}
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
    <div>
      <h3 style="margin:0;font-weight:800;color:#111827;">تفاصيل الفئة</h3>
      <div style="color:#6b7280;font-size:14px;">عرض بيانات الفئة والمعلومات المرتبطة.</div>
    </div>
    <a href="{{ route('beneficiariescategory.index') }}" 
       style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:6px 14px;font-weight:700;text-decoration:none;">
      <i class="fa fa-arrow-right"></i> رجوع للقائمة
    </a>
  </div>

  <div style="border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);margin-bottom:16px;overflow:hidden;">
    <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);border-bottom:1.5px solid #D0D5DD;padding:10px 14px;display:flex;align-items:center;gap:8px;">
      <span style="min-width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#FFF7EE;border:1.5px solid #FFD8A8;color:#92400E;font-weight:800;">i</span>
      <h6 style="margin:0;font-weight:800;color:#374151;">بيانات الفئة</h6>
    </div>

    <div style="padding:16px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <div style="color:#6b7280;font-size:13px;margin-bottom:4px;">الاسم</div>
          <div style="font-weight:700;color:#111827;">{{ $item->name }}</div>
        </div>

        <div>
          <div style="color:#6b7280;font-size:13px;margin-bottom:4px;">الكود</div>
          <div style="font-weight:700;color:#111827;">{{ $item->code }}</div>
        </div>

        <div>
          <div style="color:#6b7280;font-size:13px;margin-bottom:4px;">الحالة</div>
          <div>
            @if($item->status)
              <span style="display:inline-block;background:#e9fbf2;color:#10734a;border:1.5px solid #a7f3d0;border-radius:6px;padding:4px 12px;font-weight:800;">مفعّلة</span>
            @else
              <span style="display:inline-block;background:#eff2f6;color:#374151;border:1.5px solid #d1d5db;border-radius:6px;padding:4px 12px;font-weight:800;">موقوفة</span>
            @endif
          </div>
        </div>

        <div>
          <div style="color:#6b7280;font-size:13px;margin-bottom:4px;">تاريخ الإنشاء</div>
          <div style="font-weight:700;color:#111827;">{{ $item->created_at?->format('Y-m-d H:i') }}</div>
        </div>

        <div>
          <div style="color:#6b7280;font-size:13px;margin-bottom:4px;">آخر تحديث</div>
          <div style="font-weight:700;color:#111827;">{{ $item->updated_at?->format('Y-m-d H:i') }}</div>
        </div>
      </div>

      {{-- الأزرار --}}
      <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:16px;">
        <a href="{{ route('beneficiariescategory.edit', $item->id) }}"
           style="display:inline-flex;align-items:center;gap:6px;background:#fff5e6;color:#92400E;border:1.5px solid #FFD8A8;border-radius:999px;padding:8px 18px;font-weight:800;text-decoration:none;cursor:pointer;">
          <i class="fa fa-edit"></i> تعديل
        </a>
        <form action="{{ route('beneficiariescategory.destroy', $item->id) }}" method="POST" onsubmit="return confirm('تأكيد الحذف؟');" style="margin:0;">
          @csrf @method('DELETE')
          <button type="submit"
                  style="display:inline-flex;align-items:center;gap:6px;background:#FFF1F1;color:#B42318;border:1.5px solid #FFC9C9;border-radius:999px;padding:8px 18px;font-weight:800;cursor:pointer;">
            <i class="fa fa-trash"></i> حذف
          </button>
        </form>
        <a href="{{ route('beneficiariescategory.index') }}"
           style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#111827;border:1.5px solid #D0D5DD;border-radius:999px;padding:8px 18px;font-weight:800;text-decoration:none;">
          <i class="fa fa-arrow-right"></i> رجوع
        </a>
      </div>
    </div>
  </div>

</div>
@endsection
