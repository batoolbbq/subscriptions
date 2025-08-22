@extends('layouts.master')
@section('title','تفاصيل الفئة الفرعية')

@section('content')
<div class="container py-4" style="--ink:#111827;--line:#e5e7eb;--blue-50:#f3f6ff;--blue-200:#cfd8ff;--blue-700:#1d4ed8;--amber-50:#fff5e6;--amber-200:#ffd8a8;--amber-800:#92400e;--red-50:#fff1f1;--red-200:#ffc9c9;--red-700:#b42318;--green-50:#e9fbf2;--green-700:#10734a;--gray-50:#eff2f6;--gray-700:#374151;">

  <div style="border:2px solid var(--line);border-radius:14px;box-shadow:0 6px 20px rgba(17,24,39,.05);overflow:hidden;max-width:750px;margin:auto;">
    
    {{-- الهيدر --}}
    <div style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);padding:14px 18px;border-bottom:2px solid var(--line);">
      <h4 style="margin:0;color:var(--gray-700);font-weight:800;">تفاصيل الفئة الفرعية</h4>
    </div>

    {{-- المحتوى --}}
    <div style="padding:20px;">
      <div style="margin-bottom:12px;font-weight:700;color:var(--ink);">الاسم: <span style="font-weight:500;color:var(--gray-700);">{{ $item->name }}</span></div>
      <div style="margin-bottom:12px;font-weight:700;color:var(--ink);">النوع: <span style="font-weight:500;color:var(--gray-700);">{{ $item->type }}</span></div>
      <div style="margin-bottom:12px;font-weight:700;color:var(--ink);">الكود: <span style="font-weight:500;color:var(--gray-700);">{{ $item->code }}</span></div>
      <div style="margin-bottom:12px;font-weight:700;color:var(--ink);">الفئة الرئيسية: <span style="font-weight:500;color:var(--gray-700);">{{ $item->category?->name }}</span></div>
      <div style="margin-bottom:12px;font-weight:700;color:var(--ink);">
        الحالة:
        @if($item->status)
          <span style="display:inline-block;background:var(--green-50);color:var(--green-700);border:2px solid #a7f3d0;border-radius:6px;padding:4px 10px;font-weight:800;">مفعّلة</span>
        @else
          <span style="display:inline-block;background:var(--gray-50);color:var(--gray-700);border:2px solid #d1d5db;border-radius:6px;padding:4px 10px;font-weight:800;">موقوفة</span>
        @endif
      </div>

      <div style="color:var(--gray-700);font-size:14px;margin-top:6px;">
        أُنشئ: {{ $item->created_at?->format('Y-m-d H:i') }} — آخر تحديث: {{ $item->updated_at?->format('Y-m-d H:i') }}
      </div>

      {{-- الأزرار --}}
      <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-top:20px;">
        <a href="{{ route('beneficiaries-sup-categories.edit', $item->id) }}" style="background:var(--amber-50);border:2px solid var(--amber-200);color:var(--amber-800);padding:7px 14px;border-radius:999px;font-weight:800;text-decoration:none;cursor:pointer;">
          تعديل <i class="fa fa-edit"></i>
        </a>
        <form action="{{ route('beneficiaries-sup-categories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('تأكيد الحذف؟');" style="margin:0;">
          @csrf @method('DELETE')
          <button type="submit" style="background:var(--red-50);border:2px solid var(--red-200);color:var(--red-700);padding:7px 14px;border-radius:999px;font-weight:800;cursor:pointer;">
            حذف <i class="fa fa-trash"></i>
          </button>
        </form>
        <a href="{{ route('beneficiaries-sup-categories.index') }}" style="background:var(--blue-50);border:2px solid var(--blue-200);color:var(--blue-700);padding:7px 14px;border-radius:999px;font-weight:800;text-decoration:none;cursor:pointer;">
          رجوع <i class="fa fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
