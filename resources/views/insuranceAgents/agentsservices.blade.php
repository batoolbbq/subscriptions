@extends('layouts.master')
@section('title', $mode === 'customers' ? 'خدمات المشتركين' : 'خدمات جهات العمل')

@section('content')
    <div class="container py-4"
        style="
  direction:rtl;
  --ink:#1F2328; --muted:#6b7280; --line:#E5E7EB;
  --brand:#F58220; --brand-600:#ff8f34; --brand-700:#d95b00;
  --brown:#8C5346; --blue-50:#F0F7FF; --amber-50:#FFF7EE; --amber-200:#FFD8A8;
">
        {{-- العنوان --}}
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:14px;">
            <h4 style="margin:0;color:var(--brown);font-weight:800;">
                {{ $mode === 'customers' ? ' خدمات المشتركين' : ' خدمات جهات العمل' }}
            </h4>
            <a href="{{ route('agents.performance.index', ['phone' => $agent->phone_number ?? null]) }}"
                style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:var(--ink);
              border:1.5px solid var(--line);border-radius:999px;padding:8px 14px;font-weight:800;text-decoration:none;">
                <i class="fa-solid fa-arrow-right"></i> العودة لصفحة الوكيل
            </a>
        </div>

        {{-- معلومات الوكيل --}}
        <div
            style="border:1.5px solid var(--line);border-radius:18px;overflow:hidden;box-shadow:0 12px 28px rgba(0,0,0,.06);background:#fff;margin-bottom:14px;">
            <div
                style="background:linear-gradient(135deg,var(--brand-700),var(--brand),var(--brand-600));color:#fff;
                padding:12px 16px;display:flex;align-items:center;gap:10px;font-weight:800;">
                <span
                    style="background:#ffffff22;width:32px;height:32px;display:grid;place-items:center;border-radius:999px">
                    <i class="fa-solid fa-id-badge"></i>
                </span>
                <span>بيانات الوكيل</span>
            </div>
            <div class="card-body"
                style="padding:14px 16px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
                <div style="display:flex;gap:18px;flex-wrap:wrap;">
                    <div>
                        <div style="color:var(--muted);font-size:.85rem;">الوكيل</div>
                        <div style="font-weight:800;color:var(--ink);">{{ $agent->name ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="color:var(--muted);font-size:.85rem;">الهاتف</div>
                        <div style="font-weight:800;color:var(--ink);">📞 {{ $agent->phone_number ?? '—' }}</div>
                    </div>
                </div>
                {{-- <a href="{{ route('agents.performance.index', ['phone' => $agent->phone_number]) }}"
                    style="display:inline-flex;align-items:center;gap:6px;background:#EEF2FF;color:#1D4ED8;
                border:1.5px solid #C7D2FE;border-radius:999px;padding:8px 14px;font-weight:800;text-decoration:none;">
                    ⬅️ رجوع لصفحة الوكيل
                </a> --}}
            </div>
        </div>

        {{-- فلترة التاريخ --}}
        <form method="GET" class="row g-3 mb-3"
            style="margin:0;border:1.5px solid var(--line);border-radius:18px;padding:12px;background:#fff">
            <div class="col-auto">
                <label class="form-label" style="color:var(--muted);font-weight:700">من تاريخ</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control"
                    style="border-radius:999px;border:1.5px solid #d7dbe0;padding:10px 14px;min-width:220px;">
            </div>
            <div class="col-auto">
                <label class="form-label" style="color:var(--muted);font-weight:700">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control"
                    style="border-radius:999px;border:1.5px solid #d7dbe0;padding:10px 14px;min-width:220px;">
            </div>
            <div class="col-auto align-self-end" style="display:flex;gap:8px;flex-wrap:wrap;">
                <button class="btn"
                    style="background:var(--brand);color:#fff;border:none;border-radius:999px;
                     padding:10px 18px;font-weight:800;box-shadow:0 10px 22px rgba(245,130,32,.25);">
                    تصفية
                </button>
                <a href="{{ request()->url() }}"
                    style="display:inline-flex;align-items:center;gap:6px;background:#fff;border:1.5px solid var(--line);
                color:var(--ink);padding:10px 18px;border-radius:999px;font-weight:800;text-decoration:none;">
                    إعادة الضبط
                </a>
            </div>
        </form>

        {{-- جدول الخدمات --}}
        <div class="table-responsive"
            style="border:1.5px solid var(--line);border-radius:18px;overflow:hidden;background:#fff;box-shadow:0 10px 24px rgba(0,0,0,.05);">
            <table class="table align-middle" style="margin:0;">
                <thead style="background:linear-gradient(180deg,var(--amber-50),#FCE8D6);">
                    <tr>
                        <th style="padding:14px;color:#4b5563;font-weight:800;border-bottom:1.5px solid var(--line);">الخدمة
                        </th>
                        <th style="padding:14px;color:#4b5563;font-weight:800;border-bottom:1.5px solid var(--line);">
                            {{ $mode === 'customers' ? 'المشترك' : 'جهة العمل' }}
                        </th>
                        {{-- <th style="padding:14px;color:#4b5563;font-weight:800;border-bottom:1.5px solid var(--line);">منفّذ الخدمة</th> --}}
                        <th style="padding:14px;color:#4b5563;font-weight:800;border-bottom:1.5px solid var(--line);">
                            التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr style="border-top:1px solid var(--line);" onmouseover="this.style.backgroundColor='#FFF7EE22'"
                            onmouseout="this.style.backgroundColor=''">
                            <td style="padding:12px 14px;font-weight:700;color:var(--ink);">
                                {{ $log->service->name ?? '—' }}
                            </td>
                            <td style="padding:12px 14px;">
                                @if ($mode === 'customers')
                                    <span
                                        style="display:inline-flex;align-items:center;gap:6px;background:#F0F7FF;color:#1D4ED8;
                             border:1.5px solid #C7D2FE;border-radius:999px;padding:6px 12px;font-weight:800;">
                                        👤 {{ $log->customer->regnumber ?? '—' }}
                                    </span>
                                @else
                                    <span
                                        style="display:inline-flex;align-items:center;gap:6px;background:#FFF5E6;color:#92400E;
                             border:1.5px solid var(--amber-200);border-radius:999px;padding:6px 12px;font-weight:800;">
                                        🏢 {{ $log->institution->name ?? '—' }}
                                    </span>
                                @endif
                            </td>
                            {{-- <td style="padding:12px 14px;color:#374151;">{{ $log->performedBy->name ?? '—' }}</td> --}}
                            <td style="padding:12px 14px;color:#374151;">
                                {{ optional($log->created_at)->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center" style="padding:18px;color:#6b7280;">لا توجد بيانات ضمن
                                الفلتر الحالي.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ترقيم الصفحات --}}
        <div class="mt-3">
            {{ $logs->links() }}
        </div>
    </div>
@endsection
