@extends('layouts.master')

@section('title', 'Detail Payroll')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Payroll</h3>
                <p class="text-subtitle text-muted">Manage Payroll data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Payroll</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Show</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">

        {{-- Top Banner --}}
        <div class="px-4 py-3 d-flex align-items-center justify-content-between"
            style="background: linear-gradient(135deg, #435ebe 0%, #6c7fd8 100%);">
            <div>
                <p class="text-white-50 mb-0" style="font-size: 0.7rem; letter-spacing: 0.12em; text-transform: uppercase;">Payroll Detail</p>
                <h5 class="text-white fw-bold mb-0">{{ $payroll->employee->fullname }}</h5>
            </div>
            <div class="text-end">
                <p class="text-white-50 mb-0" style="font-size: 0.7rem; letter-spacing: 0.12em; text-transform: uppercase;">Period</p>
                <span class="text-white fw-semibold">
                    {{ \Carbon\Carbon::parse($payroll->pay_date)->format('F Y') }}
                </span>
            </div>
        </div>

        <div class="card-body p-4" id="print-area">

            {{-- Summary Cards --}}
            <div class="row g-3 mb-4">

                {{-- Base Salary --}}
                <div class="col-md-4">
                    <div class="p-3 rounded-3 h-100 bg-primary bg-opacity-10 border-start border-primary border-3">
                        <p class="text-muted small mb-1">
                            <i class="bi bi-wallet2 me-1"></i> Base Salary
                        </p>
                        <h6 class="fw-bold mb-0">Rp {{ number_format($payroll->salary) }}</h6>
                    </div>
                </div>

                {{-- Bonuses --}}
                <div class="col-md-4">
                    <div class="p-3 rounded-3 h-100 bg-success bg-opacity-10 border-start border-success border-3">
                        <p class="text-muted small mb-1">
                            <i class="bi bi-plus-circle me-1 text-success"></i> Bonuses
                        </p>
                        <h6 class="fw-bold text-success mb-0">+ Rp {{ number_format($payroll->bonuses) }}</h6>
                    </div>
                </div>

                {{-- Deductions --}}
                <div class="col-md-4">
                    <div class="p-3 rounded-3 h-100 bg-danger bg-opacity-10 border-start border-danger border-3">
                        <p class="text-muted small mb-1">
                            <i class="bi bi-dash-circle me-1 text-danger"></i> Deductions
                        </p>
                        <h6 class="fw-bold text-danger mb-0">- Rp {{ number_format($payroll->deductions) }}</h6>
                    </div>
                </div>

            </div>

            {{-- Net Salary & Pay Date --}}
            <div class="bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3 p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">

                <div>
                    <p class="text-muted small mb-1 text-uppercase" style="letter-spacing: 0.08em;">Net Salary</p>
                    <h2 class="fw-bold text-primary mb-0" style="font-size: 2rem;">
                        Rp {{ number_format($payroll->net_salary) }}
                    </h2>
                </div>

                <div class="vr d-none d-md-block opacity-25"></div>

                <div>
                    <p class="text-muted small mb-1 text-uppercase" style="letter-spacing: 0.08em;">Pay Date</p>
                    <p class="fw-semibold mb-0 fs-6">
                        <i class="bi bi-calendar3 me-2 text-primary"></i>
                        {{ \Carbon\Carbon::parse($payroll->pay_date)->format('d F Y') }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Footer Actions --}}
        <div class="px-4 py-3 d-flex gap-2 border-top">
            <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">Back to Payroll List</a>
            <a href="{{ route('payrolls.slip', $payroll->id) }}" class="btn btn-primary" target="_blank">
            <span class="bi bi-printer"></span> Print Slip</a>
        </div>

    </div>
</section>
</div>

@endsection