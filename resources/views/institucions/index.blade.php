@extends('layouts.master')

@section('title', 'جهات العمل')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0" style="color:#111827;font-weight:700;letter-spacing:.2px;">جهات العمل</h3>
            <a href="{{ route('institucions.create') }}"
                style="display:inline-flex;align-items:center;gap:.4rem;background:#EFF3FF;color:#0F172A;
                  border:1.5px solid #C7D2FE;border-radius:999px;padding:.55rem 1rem;font-weight:800;
                  text-decoration:none;"
                onmouseover="this.style.background='#E9EEFF';this.style.borderColor='#BED0FF';this.style.color='#1D4ED8';"
                onmouseout="this.style.background='#EFF3FF';this.style.borderColor='#C7D2FE';this.style.color='#0F172A';">
                إضافة جهة عمل <i class="fa fa-plus" style="font-size:.9rem;"></i>
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div
            style="border:1.5px solid #D0D5DD;border-radius:14px;box-shadow:0 8px 20px rgba(17,24,39,.04);background:#fff;padding:1rem;">
            <div style="border:1.5px solid #D0D5DD;border-radius:12px;overflow:hidden;background:#fff;">
                <div class="table-responsive">
                    <div class="mb-3" style="display:flex; gap:10px;">
                        
                        <a href="{{ route('institucions.index') }}"
                            style="padding:8px 16px; border-radius:8px; font-weight:700; 
              background:{{ request('status') == null ? '#e0f2fe' : '#f3f4f6' }};
              color:{{ request('status') == null ? '#1d4ed8' : '#374151' }};
              border:1.5px solid {{ request('status') == null ? '#bfdbfe' : '#d1d5db' }};
              text-decoration:none;">
                            الكل
                        </a>
                        <a href="{{ route('institucions.index', ['status' => 'active']) }}"
                            style="padding:8px 16px; border-radius:8px; font-weight:700; 
              background:{{ request('status') == 'active' ? '#e9fbf2' : '#f3f4f6' }};
              color:{{ request('status') == 'active' ? '#10734a' : '#374151' }};
              border:1.5px solid {{ request('status') == 'active' ? '#a7f3d0' : '#d1d5db' }};
              text-decoration:none;">
                            مفعلة
                        </a>

                        <a href="{{ route('institucions.index', ['status' => 'inactive']) }}"
                            style="padding:8px 16px; border-radius:8px; font-weight:700; 
              background:{{ request('status') == 'inactive' ? '#fff5f5' : '#f3f4f6' }};
              color:{{ request('status') == 'inactive' ? '#b91c1c' : '#374151' }};
              border:1.5px solid {{ request('status') == 'inactive' ? '#fecaca' : '#d1d5db' }};
              text-decoration:none;">
                            غير مفعلة
                        </a>

                    </div>

                    <table class="table align-middle"
                        style="margin-bottom:0;color:#111827;border-collapse:separate;border-spacing:0;width:100%;">
                        <thead>
                            <tr>
                                <th
                                    style="width:56px;background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4B5563;font-weight:800;font-size:1.05rem;border-bottom:1.5px solid #D0D5DD;padding:16px 14px;vertical-align:middle;">
                                    #</th>
                                <th
                                    style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4B5563;font-weight:800;font-size:1.05rem;border-bottom:1.5px solid #D0D5DD;padding:16px 14px;vertical-align:middle;">
                                    الاسم</th>
                                <th
                                    style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4B5563;font-weight:800;font-size:1.05rem;border-bottom:1.5px solid #D0D5DD;padding:16px 14px;vertical-align:middle;">
                                    نوع جهة العمل</th>
                                <th
                                    style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4B5563;font-weight:800;font-size:1.05rem;border-bottom:1.5px solid #D0D5DD;padding:16px 14px;vertical-align:middle;">
                                    الاشتراك</th>
                                <th
                                    style="background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4B5563;font-weight:800;font-size:1.05rem;border-bottom:1.5px solid #D0D5DD;padding:16px 14px;vertical-align:middle;">
                                    الرقم التجاري</th>
                                <th
                                    style="width:120px;background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4B5563;font-weight:800;font-size:1.05rem;border-bottom:1.5px solid #D0D5DD;padding:16px 14px;vertical-align:middle;">
                                    الحالة</th>
                                <th
                                    style="width:260px;background:linear-gradient(180deg,#FFF7EE,#FCE8D6);color:#4B5563;font-weight:800;font-size:1.05rem;border-bottom:1.5px solid #D0D5DD;padding:16px 14px;vertical-align:middle;">
                                    إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $i => $row)
                                <tr onmouseover="this.style.background='#FFF9F2';" onmouseout="this.style.background='';">
                                    <td
                                        style="color:#8A94A6;font-weight:700;border-top:1px solid #EEF2F7;padding:18px 14px;">
                                        {{ $row->id }}</td>
                                    <td style="font-weight:600;border-top:1px solid #EEF2F7;padding:18px 14px;">
                                        {{ $row->name }}</td>
                                    <td style="border-top:1px solid #EEF2F7;padding:18px 14px;">
                                        {{ optional($row->workCategory)->name }}</td>
                                    <td style="border-top:1px solid #EEF2F7;padding:18px 14px;">
                                        {{ optional($row->subscription)->name ?: '—' }}</td>
                                    <td style="border-top:1px solid #EEF2F7;padding:18px 14px;">
                                        {{ $row->commercial_number ?: '—' }}</td>
                                    <td style="padding:8px;">
                                        @if ($row->status)
                                            <span
                                                style="display:inline-block; background:#e9fbf2; color:#10734a; border:1px solid #a7f3d0; 
                 border-radius:6px; padding:4px 12px; font-weight:700; font-size:0.85rem;">
                                                نشطة
                                            </span>
                                        @else
                                            <span
                                                style="display:inline-block; background:#eff2f6; color:#374151; border:1px solid #d1d5db; 
                 border-radius:6px; padding:4px 12px; font-weight:700; font-size:0.85rem;">
                                                موقوفة
                                            </span>
                                        @endif
                                    </td>

                                    <td style="border-top:1px solid #EEF2F7;padding:18px 14px;">
                                        <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                                            <a href="{{ route('institucions.show', $row) }}"
                                                style="border-radius:999px;padding:7px 16px;font-weight:800;border:1.5px solid #C7D2FE;background:#EFF3FF;color:#0F172A;text-decoration:none;"
                                                onmouseover="this.style.background='#E9EEFF';this.style.borderColor='#BED0FF';this.style.color='#1D4ED8';"
                                                onmouseout="this.style.background='#EFF3FF';this.style.borderColor='#C7D2FE';this.style.color='#0F172A';">
                                                عرض <i class="fa fa-eye"
                                                    style="font-size:.9rem;margin-inline-start:.4rem;"></i>
                                            </a>
                                            <a href="{{ route('institucions.edit', $row) }}"
                                                style="border-radius:999px;padding:7px 16px;font-weight:800;border:1.5px solid #FFD8A8;background:#FFF5E6;color:#92400E;text-decoration:none;"
                                                onmouseover="this.style.background='#FFEFDB';this.style.borderColor='#FFCE8B';"
                                                onmouseout="this.style.background='#FFF5E6';this.style.borderColor='#FFD8A8';">
                                                تعديل <i class="fa fa-edit"
                                                    style="font-size:.9rem;margin-inline-start:.4rem;"></i>
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align:center;padding:2rem;color:#6b7280;">لا توجد بيانات
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-0 pt-3">
                {{-- {{ $items->links() }} --}}
            </div>
        </div>
    </div>
@endsection
