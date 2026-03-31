{{--
    ┌──────────────────────────────────────────────────┐
    │  PAGE: employee/payroll/index.blade.php          │
    │  Daftar Payroll milik employee                   │
    │  Filter bulan + pagination 5 per halaman         │
    │                                                  │
    │  Variables dari PayrollController@index:         │
    │  - $payrolls       → paginator (perPage: 5)      │
    │  - $payrollStats   → array:                      │
    │      total, total_count, avg_net                 │
    │  - $currentMonth   → string, e.g. 'Maret 2026'  │
    │  - $months         → array dropdown bulan        │
    │                                                  │
    │  Kolom tabel transactions.payrolls:              │
    │  employee_id, salary, bonuses, deductions,       │
    │  net_salary, pay_date, total_alpha,              │
    │  total_late_minutes, deduction_alpha,            │
    │  deduction_late                                  │
    └──────────────────────────────────────────────────┘
--}}

@extends('employee.layouts.master')

@section('title', 'Payrolls — Karyawan')

@php
    $pageTitle   = 'Payrolls';
    $activePage  = 'payrolls';
    $breadcrumbs = [['label' => 'Payrolls']];
    $headerCta   = null;
@endphp

@section('content')

{{-- ===== METRIC ===== --}}
<div class="payroll-metrics">

    <div class="m-card">
        <div class="m-label">Total Penggajian</div>
        <div class="m-val">{{ $payrollStats['total'] }}</div>
        <div class="m-note">{{ $currentMonth }}</div>
        <div class="m-bar">
            <div class="m-fill" style="width:72%;background:var(--accent)"></div>
        </div>
    </div>

    <div class="m-card">
        <div class="m-label">Jumlah Slip</div>
        <div class="m-val">{{ $payrollStats['total_count'] }}</div>
        <div class="m-note" style="color:var(--text-muted)">total slip gaji</div>
        <div class="m-bar">
            <div class="m-fill" style="width:100%;background:var(--green)"></div>
        </div>
    </div>

    <div class="m-card">
        <div class="m-label">Rata-rata Gaji Bersih</div>
        <div class="m-val" style="font-size:clamp(16px,1.4vw,20px)">
            Rp {{ number_format($payrollStats['avg_net'] ?? 0, 0, ',', '.') }}
        </div>
        <div class="m-note" style="color:var(--text-muted)">per periode</div>
        <div class="m-bar">
            <div class="m-fill" style="width:60%;background:var(--amber)"></div>
        </div>
    </div>

</div>

{{-- ===== CONTENT GRID ===== --}}
<div class="payroll-content-grid">

    {{-- ===== SLIP GAJI ===== --}}
    <div class="card">

        <div class="panel-head" style="flex-wrap:wrap;gap:8px">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none"
                 stroke="var(--accent-text)" stroke-width="1.5" stroke-linecap="round">
                <rect x="1.5" y="3.5" width="13" height="9" rx="1.5"/>
                <path d="M5.5 3.5v1M10.5 3.5v1M4 8h2M10 8h2M6 10.5h4"/>
            </svg>
            <span class="panel-title">Slip Gaji</span>
            <span class="badge badge-blue" style="margin-left:0">
                {{ $payrolls->total() }} slip
            </span>
            @if(request()->filled('month'))
                <a href="{{ route('payrolls.index') }}"
                   class="btn btn-sm" style="margin-left:auto;color:var(--red-text)">
                    <svg viewBox="0 0 14 14" width="12" height="12">
                        <path d="M2 2l10 10M12 2L2 12" stroke="currentColor"
                              stroke-width="1.5" fill="none" stroke-linecap="round"/>
                    </svg>
                    Reset
                </a>
            @endif
        </div>

        {{-- ── FILTER BULAN ── --}}
        <form method="GET" action="{{ route('payrolls.index') }}"
              style="padding:0 20px 14px;display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap">

            <div style="min-width:160px;flex:1">
                <label style="font-size:11px;color:var(--text-muted);
                              display:block;margin-bottom:4px;font-weight:500">
                    Filter Bulan
                </label>
                <select name="month" class="form-control">
                    <option value="">Semua Bulan</option>
                    @foreach($months as $m)
                        <option value="{{ $m['value'] }}"
                            {{ request('month') === $m['value'] ? 'selected' : '' }}>
                            {{ $m['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="align-self:flex-end">
                <svg viewBox="0 0 14 14" width="13" height="13">
                    <path d="M1 3h12M3 7h8M5 11h4" stroke="currentColor"
                          stroke-width="1.5" fill="none" stroke-linecap="round"/>
                </svg>
                <span>Tampilkan</span>
            </button>

        </form>

        {{-- ── LIST PAYSLIP ── --}}
        <div id="payslip-list" style="padding:0 8px 8px">
            @forelse($payrolls as $index => $payroll)
                <div class="payslip-row {{ $index === 0 && $payrolls->currentPage() === 1 ? 'active' : '' }}"
                     onclick="showBreakdown(this)"
                     data-id="{{ $payroll->id }}"
                     data-paydate="{{ \Carbon\Carbon::parse($payroll->pay_date)->format('d M Y') }}"
                     data-salary="{{ $payroll->salary }}"
                     data-bonuses="{{ $payroll->bonuses }}"
                     data-deductions="{{ $payroll->deductions }}"
                     data-net="{{ $payroll->net_salary }}"
                     data-alpha="{{ $payroll->total_alpha }}"
                     data-late="{{ $payroll->total_late_minutes }}"
                     data-deduct-alpha="{{ $payroll->deduction_alpha }}"
                     data-deduct-late="{{ $payroll->deduction_late }}">

                    {{-- Avatar (inisial bulan) --}}
                    <div class="av av-sm" style="background:var(--accent);color:#ede9e3;flex-shrink:0">
                        {{ \Carbon\Carbon::parse($payroll->pay_date)->format('M') }}
                    </div>

                    {{-- Periode --}}
                    <div style="flex:1;min-width:0">
                        <div class="payslip-name">
                            {{ \Carbon\Carbon::parse($payroll->pay_date)->translatedFormat('F Y') }}
                        </div>
                        <div class="payslip-div">
                            Pay Date: {{ \Carbon\Carbon::parse($payroll->pay_date)->format('d M Y') }}
                        </div>
                    </div>

                    {{-- Net Salary --}}
                    <div style="text-align:right;flex-shrink:0">
                        <div class="payslip-amount">
                            Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                        </div>
                        <div style="margin-top:3px">
                            @if($payroll->total_alpha > 0 || $payroll->total_late_minutes > 0)
                                <span class="badge badge-amber" style="font-size:10px">Ada potongan</span>
                            @else
                                <span class="badge badge-green" style="font-size:10px">Full hadir</span>
                            @endif
                        </div>
                    </div>

                    {{-- Download --}}
                    <a href="{{ route('payrolls.slip', $payroll->id) }}"
                       class="btn btn-sm btn-icon"
                       onclick="event.stopPropagation()"
                       target="_blank"
                       title="Unduh Slip">
                        <svg viewBox="0 0 14 14" width="14" height="14">
                            <path d="M2 10v2h10v-2M7 2v7M4 7l3 3 3-3"
                                  stroke="currentColor" stroke-width="1.5"
                                  fill="none" stroke-linecap="round"/>
                        </svg>
                    </a>

                </div>
            @empty
                <div style="padding:40px 12px;text-align:center;color:var(--text-muted)">
                    <svg width="36" height="36" viewBox="0 0 16 16" fill="none"
                         stroke="currentColor" stroke-width="1" stroke-linecap="round"
                         style="opacity:0.3;display:block;margin:0 auto 10px">
                        <rect x="1.5" y="3.5" width="13" height="9" rx="1.5"/>
                        <path d="M5.5 3.5v1M10.5 3.5v1M4 8h2M10 8h2M6 10.5h4"/>
                    </svg>
                    Tidak ada slip gaji ditemukan.
                </div>
            @endforelse
        </div>

        {{-- ── PAGINATION ── --}}
        @if($payrolls->hasPages())
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:12px 20px 16px;border-top:1px solid var(--border);
                    flex-wrap:wrap;gap:8px">

            <div style="font-size:12px;color:var(--text-muted)">
                Menampilkan {{ $payrolls->firstItem() }}–{{ $payrolls->lastItem() }}
                dari {{ $payrolls->total() }} slip
            </div>

            <div style="display:flex;gap:4px;align-items:center">

                {{-- Prev --}}
                @if($payrolls->onFirstPage())
                    <button class="pg-btn" disabled>
                        <svg viewBox="0 0 14 14" width="12" height="12">
                            <path d="M9 2L4 7l5 5" stroke="currentColor" stroke-width="1.5"
                                  fill="none" stroke-linecap="round"/>
                        </svg>
                    </button>
                @else
                    <a href="{{ $payrolls->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                       class="pg-btn">
                        <svg viewBox="0 0 14 14" width="12" height="12">
                            <path d="M9 2L4 7l5 5" stroke="currentColor" stroke-width="1.5"
                                  fill="none" stroke-linecap="round"/>
                        </svg>
                    </a>
                @endif

                {{-- Nomor halaman (window 5) --}}
                @php
                    $cur   = $payrolls->currentPage();
                    $last  = $payrolls->lastPage();
                    $start = max(1, min($cur - 2, $last - 4));
                    $end   = min($last, $start + 4);
                @endphp

                @if($start > 1)
                    <a href="{{ $payrolls->url(1) }}&{{ http_build_query(request()->except('page')) }}"
                       class="pg-btn">1</a>
                    @if($start > 2)<span class="pg-dots">…</span>@endif
                @endif

                @for($p = $start; $p <= $end; $p++)
                    <a href="{{ $payrolls->url($p) }}&{{ http_build_query(request()->except('page')) }}"
                       class="pg-btn {{ $p === $cur ? 'pg-active' : '' }}">{{ $p }}</a>
                @endfor

                @if($end < $last)
                    @if($end < $last - 1)<span class="pg-dots">…</span>@endif
                    <a href="{{ $payrolls->url($last) }}&{{ http_build_query(request()->except('page')) }}"
                       class="pg-btn">{{ $last }}</a>
                @endif

                {{-- Next --}}
                @if($payrolls->hasMorePages())
                    <a href="{{ $payrolls->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                       class="pg-btn">
                        <svg viewBox="0 0 14 14" width="12" height="12">
                            <path d="M5 2l5 5-5 5" stroke="currentColor" stroke-width="1.5"
                                  fill="none" stroke-linecap="round"/>
                        </svg>
                    </a>
                @else
                    <button class="pg-btn" disabled>
                        <svg viewBox="0 0 14 14" width="12" height="12">
                            <path d="M5 2l5 5-5 5" stroke="currentColor" stroke-width="1.5"
                                  fill="none" stroke-linecap="round"/>
                        </svg>
                    </button>
                @endif

            </div>
        </div>
        @endif

    </div>{{-- /.card slip gaji --}}

    {{-- ===== BREAKDOWN ===== --}}
    <div class="card" style="align-self:start;position:sticky;top:80px">

        <div class="panel-head">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none"
                 stroke="var(--accent-text)" stroke-width="1.5" stroke-linecap="round">
                <rect x="2" y="3" width="12" height="1.4" rx=".7"/>
                <rect x="2" y="7.3" width="12" height="1.4" rx=".7"/>
                <rect x="2" y="11.6" width="7" height="1.4" rx=".7"/>
            </svg>
            <span class="panel-title">Breakdown Gaji</span>
        </div>

        {{-- Periode yang dipilih --}}
        <div style="padding:0 20px 12px;border-bottom:1px solid var(--border)">
            <div id="bd-paydate"
                 style="font-size:14px;font-weight:600;color:var(--text-primary)">
                {{ $payrolls->first()?->pay_date
                    ? \Carbon\Carbon::parse($payrolls->first()->pay_date)->translatedFormat('F Y')
                    : '—' }}
            </div>
            <div id="bd-paydate-sub"
                 style="font-size:12px;color:var(--text-muted);margin-top:2px">
                Pay Date:
                {{ $payrolls->first()?->pay_date
                    ? \Carbon\Carbon::parse($payrolls->first()->pay_date)->format('d M Y')
                    : '—' }}
            </div>
        </div>

        <div style="padding:4px 20px 0">

            {{-- Pendapatan --}}
            <div style="font-size:10.5px;font-weight:500;color:var(--text-muted);
                        text-transform:uppercase;letter-spacing:0.6px;padding:10px 0 6px">
                Pendapatan
            </div>

            <div class="breakdown-row">
                <span class="breakdown-label">Gaji Pokok</span>
                <span id="bd-salary" class="breakdown-amount">
                    Rp {{ number_format($payrolls->first()?->salary ?? 0, 0, ',', '.') }}
                </span>
            </div>
            <div class="breakdown-row">
                <span class="breakdown-label">Bonus</span>
                <span id="bd-bonus" class="breakdown-amount">
                    Rp {{ number_format($payrolls->first()?->bonuses ?? 0, 0, ',', '.') }}
                </span>
            </div>

            {{-- Potongan --}}
            <div style="font-size:10.5px;font-weight:500;color:var(--text-muted);
                        text-transform:uppercase;letter-spacing:0.6px;padding:10px 0 6px">
                Potongan
            </div>

            <div class="breakdown-row">
                <span class="breakdown-label">Potongan Umum</span>
                <span id="bd-deduction" class="breakdown-amount breakdown-deduct">
                    − Rp {{ number_format($payrolls->first()?->deductions ?? 0, 0, ',', '.') }}
                </span>
            </div>
            <div class="breakdown-row">
                <span class="breakdown-label">
                    Potongan Alpha
                    <span id="bd-alpha-count"
                          style="font-size:10px;color:var(--text-muted);margin-left:4px">
                        ({{ $payrolls->first()?->total_alpha ?? 0 }} hari)
                    </span>
                </span>
                <span id="bd-deduct-alpha" class="breakdown-amount breakdown-deduct">
                    − Rp {{ number_format($payrolls->first()?->deduction_alpha ?? 0, 0, ',', '.') }}
                </span>
            </div>
            <div class="breakdown-row">
                <span class="breakdown-label">
                    Potongan Terlambat
                    <span id="bd-late-count"
                          style="font-size:10px;color:var(--text-muted);margin-left:4px">
                        ({{ $payrolls->first()?->total_late_minutes ?? 0 }} mnt)
                    </span>
                </span>
                <span id="bd-deduct-late" class="breakdown-amount breakdown-deduct">
                    − Rp {{ number_format($payrolls->first()?->deduction_late ?? 0, 0, ',', '.') }}
                </span>
            </div>

            {{-- Total --}}
            <div class="breakdown-row breakdown-total" style="margin-top:4px">
                <span class="breakdown-label">Gaji Bersih</span>
                <span id="bd-net" class="breakdown-amount">
                    Rp {{ number_format($payrolls->first()?->net_salary ?? 0, 0, ',', '.') }}
                </span>
            </div>

        </div>

        {{-- Download --}}
        <div style="padding:12px 20px 16px">
            <a id="bd-slip-link"
               href="{{ $payrolls->first() ? route('payrolls.slip', $payrolls->first()->id) : '#' }}"
               target="_blank"
               class="btn btn-primary"
               style="width:100%;justify-content:center">
                <svg viewBox="0 0 14 14" width="13" height="13">
                    <path d="M2 10v2h10v-2M7 2v7M4 7l3 3 3-3"
                          stroke="currentColor" stroke-width="1.5"
                          fill="none" stroke-linecap="round"/>
                </svg>
                <span>Unduh Slip</span>
            </a>
        </div>

    </div>{{-- /.card breakdown --}}

</div>{{-- /.payroll-content-grid --}}

@endsection

@push('styles')
<style>
.pg-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 30px;
    height: 30px;
    padding: 0 6px;
    border-radius: 7px;
    font-size: 12.5px;
    font-weight: 500;
    color: var(--text-secondary);
    background: var(--bg-muted);
    border: 1px solid var(--border);
    cursor: pointer;
    text-decoration: none;
    transition: all 0.14s;
    font-family: 'DM Sans', sans-serif;
}
.pg-btn:hover:not(:disabled) {
    background: var(--bg-hover);
    color: var(--text-primary);
}
.pg-btn:disabled {
    opacity: 0.35;
    cursor: default;
}
.pg-btn.pg-active {
    background: var(--accent);
    color: #ede9e3;
    border-color: var(--accent);
}
.pg-dots {
    font-size: 13px;
    color: var(--text-muted);
    padding: 0 3px;
    line-height: 30px;
}
</style>
@endpush

@push('scripts')
<script>
function fmt(n) {
    return 'Rp ' + Number(n || 0).toLocaleString('id-ID');
}

function showBreakdown(el) {
    // Hapus active semua row
    document.querySelectorAll('.payslip-row').forEach(r => r.classList.remove('active'));
    el.classList.add('active');

    const d = el.dataset;

    // Header periode
    const date = new Date(d.paydate);
    document.getElementById('bd-paydate').textContent     = d.paydate || '—';
    document.getElementById('bd-paydate-sub').textContent = 'Pay Date: ' + (d.paydate || '—');

    // Pendapatan
    document.getElementById('bd-salary').textContent = fmt(d.salary);
    document.getElementById('bd-bonus').textContent  = fmt(d.bonuses);

    // Potongan
    document.getElementById('bd-deduction').textContent    = '− ' + fmt(d.deductions);
    document.getElementById('bd-deduct-alpha').textContent = '− ' + fmt(d.deductAlpha);
    document.getElementById('bd-deduct-late').textContent  = '− ' + fmt(d.deductLate);

    // Info hari & menit
    document.getElementById('bd-alpha-count').textContent = '(' + (d.alpha || 0) + ' hari)';
    document.getElementById('bd-late-count').textContent  = '(' + (d.late  || 0) + ' mnt)';

    // Gaji bersih
    document.getElementById('bd-net').textContent = fmt(d.net);

    // Link unduh slip
    const base = "{{ url('payrolls') }}";
    document.getElementById('bd-slip-link').href = base + '/' + d.id + '/slip';
}
</script>
@endpush