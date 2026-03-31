@extends('employee.layouts.master')

@section('title', 'Dashboard')

@php
    $pageTitle   = 'Dashboard';
    $activePage  = 'dashboard';
    $breadcrumbs = [['label' => 'Dashboard']];
    $headerCta   = null;
@endphp

@section('content')

{{-- ===== GREETING ===== --}}
<div style="margin-bottom:20px">
    <div style="font-family:'Sora',sans-serif;font-size:20px;font-weight:600;color:var(--text-primary);letter-spacing:-0.3px">
        Selamat Datang, {{ Auth::user()->name }}! 👋
    </div>
    <div style="font-size:13px;color:var(--text-muted);margin-top:3px">
        {{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

{{-- ===== SALDO CUTI ===== --}}
<div class="card" style="margin-bottom:16px">
    <div class="panel-head">
        <span class="panel-title">Saldo Cuti {{ now()->year }}</span>
    </div>

    @if($leaveBalances->isEmpty())
        <div style="text-align:center;color:var(--text-muted);padding:32px;font-size:13px">
            Belum ada data saldo cuti.
        </div>
    @else
        <div class="dashboard-leave-grid">
            @foreach($leaveBalances as $balance)
                @php
                    $used      = $balance->used_days ?? 0;
                    $total     = $balance->total_days ?? 0;
                    $remaining = $total - $used;
                    $pct       = $total > 0 ? round(($used / $total) * 100) : 0;
                    $barColor  = match(true) {
                        $pct >= 80 => 'var(--red)',
                        $pct >= 50 => 'var(--amber)',
                        default    => 'var(--green)',
                    };
                @endphp
                <div class="m-card">
                    <div class="m-label">{{ $balance->leaveType->name }}</div>
                    <div class="m-val">{{ $remaining }}</div>
                    <div class="m-note">dari {{ $total }} hari · {{ $used }} terpakai</div>
                    <div class="m-bar">
                        <div class="m-fill" style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ===== RIWAYAT PENGAJUAN CUTI ===== --}}
<div class="card">
    <div class="panel-head">
        <span class="panel-title">Riwayat Pengajuan Cuti</span>
        <a href="{{ route('leave-requests.index') }}" class="panel-action">Lihat Semua →</a>
    </div>

    @if($leaveRequests->isEmpty())
        <div style="text-align:center;color:var(--text-muted);padding:32px;font-size:13px">
            Belum ada pengajuan cuti.
        </div>
    @else

        {{-- Tabel desktop --}}
        <div class="table-wrap dashboard-task-table">
            <table>
                <thead>
                    <tr>
                        <th>Jenis Cuti</th>
                        <th>Tanggal</th>
                        <th>Durasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaveRequests as $leave)
                        <tr>
                            <td style="font-weight:500;color:var(--text-primary)">
                                {{ $leave->leaveType->name }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }}
                                – {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1 }} hari
                            </td>
                            <td>
                                @if($leave->status == 'approved')
                                    <span class="badge badge-green">Disetujui</span>
                                @elseif($leave->status == 'pending')
                                    <span class="badge badge-amber">Pending</span>
                                @elseif($leave->status == 'rejected')
                                    <span class="badge badge-red">Ditolak</span>
                                @else
                                    <span class="badge badge-gray">{{ $leave->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Card list mobile --}}
        <div class="dashboard-task-cards">
            @foreach($leaveRequests as $leave)
                <div class="presence-card-item">
                    <div class="task-card-header">
                        <div style="flex:1;min-width:0">
                            <div class="task-card-title">{{ $leave->leaveType->name }}</div>
                            <div class="task-card-sub">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }}
                                – {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                            </div>
                        </div>
                        @if($leave->status == 'approved')
                            <span class="badge badge-green">Disetujui</span>
                        @elseif($leave->status == 'pending')
                            <span class="badge badge-amber">Pending</span>
                        @elseif($leave->status == 'rejected')
                            <span class="badge badge-red">Ditolak</span>
                        @else
                            <span class="badge badge-gray">{{ $leave->status }}</span>
                        @endif
                    </div>
                    <div class="presence-card-row">
                        <span class="presence-card-label">Durasi</span>
                        <span class="presence-card-val">
                            {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1 }} hari
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

    @endif
</div>

@endsection