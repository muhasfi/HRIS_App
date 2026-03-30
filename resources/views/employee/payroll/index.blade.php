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
<div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-bottom:18px">

    <div class="m-card">
        <div class="m-label">Total Penggajian</div>
        <div class="m-val">{{ $payrollStats['total'] ?? 'Rp 3,2M' }}</div>
        <div class="m-note">{{ $currentMonth ?? 'Maret 2026' }}</div>
        <div class="m-bar">
            <div class="m-fill" style="width:72%;background:var(--accent)"></div>
        </div>
    </div>

    <div class="m-card">
        <div class="m-label">Sudah Dibayar</div>
        <div class="m-val">{{ $payrollStats['paid'] ?? 241 }}</div>
        <div class="m-note" style="color:var(--green-text)">
            dari {{ $payrollStats['total_karyawan'] ?? 248 }} karyawan
        </div>
        <div class="m-bar">
            <div class="m-fill" style="width:97%;background:var(--green)"></div>
        </div>
    </div>

    <div class="m-card">
        <div class="m-label">Menunggu</div>
        <div class="m-val">{{ $payrollStats['pending'] ?? 7 }}</div>
        <div class="m-note" style="color:var(--amber-text)">perlu diproses</div>
        <div class="m-bar">
            <div class="m-fill" style="width:3%;background:var(--amber)"></div>
        </div>
    </div>

</div>

{{-- ===== CONTENT ===== --}}
<div style="display:grid;grid-template-columns:1.6fr 1fr;gap:16px">

    {{-- ===== SLIP GAJI ===== --}}
    <div class="card">
        <div class="panel-head">
            <span class="panel-title">Slip Gaji</span>
        </div>

        <div id="payslip-list">
            @forelse($payrolls as $index => $payroll)
                <div class="payslip-row {{ $index === 0 ? 'active' : '' }}"
                     data-salary="{{ $payroll->salary }}"
                     data-bonuses="{{ $payroll->bonuses }}"
                     data-deductions="{{ $payroll->deductions }}"
                     data-total="{{ $payroll->net_salary }}">

                    {{-- Avatar --}}
                    <div class="av av-sm"
                         style="background:var(--accent);color:#ede9e3">
                        {{ strtoupper(substr($payroll->employee->fullname, 0, 2)) }}
                    </div>

                    {{-- Nama --}}
                    <div style="flex:1;min-width:0">
                        <div class="payslip-name">
                            {{ $payroll->employee->fullname }}
                        </div>
                        <div class="payslip-div">
                            Pay Date: {{ $payroll->pay_date }}
                        </div>
                    </div>

                    {{-- Nominal --}}
                    <div style="text-align:right;flex-shrink:0">
                        <div class="payslip-amount">
                            Rp {{ number_format($payroll->net_salary) }}
                        </div>
                        <div style="margin-top:3px">
                            <span class="badge badge-green">Dibayar</span>
                        </div>
                    </div>
                    
                    {{-- Action --}}
                    <a href="{{ route('payrolls.slip', $payroll->id) }}"
                       class="btn btn-sm btn-icon"
                       onclick="event.stopPropagation()"
                       target="_blank"
                       title="Detail">
                        <svg viewBox="0 0 14 14">
                            <path d="M2 10v2h10v-2M7 2v7M4 7l3 3 3-3"/>
                        </svg>
                    </a>

                </div>
            @empty
                <p class="text-muted">Belum ada data payroll</p>
            @endforelse
        </div>
    </div>

    {{-- ===== BREAKDOWN ===== --}}
    <div class="card">
        <div class="panel-head">
            <span class="panel-title">Breakdown Gaji</span>
        </div>

        <div id="breakdown-list">
            <div class="breakdown-row">
                <span class="breakdown-label">Gaji Pokok</span>
                <span id="bd-salary" class="breakdown-amount">
                    Rp {{ number_format($payrolls[0]->salary ?? 0) }}
                </span>
            </div>

            <div class="breakdown-row">
                <span class="breakdown-label">Bonus</span>
                <span id="bd-bonus" class="breakdown-amount">
                    Rp {{ number_format($payrolls[0]->bonuses ?? 0) }}
                </span>
            </div>

            <div class="breakdown-row">
                <span class="breakdown-label">Potongan</span>
                <span id="bd-deduction" class="breakdown-amount breakdown-deduct">
                    - Rp {{ number_format($payrolls[0]->deductions ?? 0) }}
                </span>
            </div>

            <div class="breakdown-row breakdown-total">
                <span class="breakdown-label">Total</span>
                <span id="bd-total" class="breakdown-amount">
                    Rp {{ number_format($payrolls[0]->net_salary ?? 0) }}
                </span>
            </div>
        </div>
    </div>

</div>



@endsection