@extends('employee.layouts.master')

@section('title', 'Presence')

@php
    $pageTitle   = 'Presence';
    $activePage  = 'presence';

    $total = $presences->count();
    $today = now()->toDateString();

    $todayPresences = $presences->where('date', $today);

    $hadir     = $todayPresences->where('status', 'present')->count();
    $terlambat = $todayPresences->where('status', 'late')->count();
    $hadirPct  = $total > 0 ? round(($hadir / $total) * 100) : 0;
@endphp

@section('content')

<div style="width:100%">

    {{-- ACTION BUTTON --}}
    <div style="display:flex;justify-content:flex-end;margin-bottom:12px">
        @if (session('role') != 'HR')
            @if(!$todayPresence)
                @if($hasSchedule)
                    <a href="{{ route('presences.create') }}" class="btn btn-success">Check In</a>
                @else
                    <button class="btn btn-secondary" disabled>Tidak Ada Jadwal</button>
                @endif
            @elseif(!$todayPresence->check_out)
                <a href="{{ route('presences.create') }}" class="btn btn-warning">Check Out</a>
            @else
                <span class="badge badge-green">Sudah Absen Hari Ini</span>
            @endif
        @else
            <a href="{{ route('presences.create') }}" class="btn btn-primary">New Presence</a>
        @endif
    </div>

    {{-- METRIC CARDS --}}
    <div class="presence-metrics">
        <div class="m-card">
            <div class="m-label">Total Data</div>
            <div class="m-val">{{ $total }}</div>
            <div class="m-note">semua data</div>
            <div class="m-bar"><div class="m-fill" style="width:100%;background:var(--accent)"></div></div>
        </div>
        <div class="m-card">
            <div class="m-label">Hadir Hari Ini</div>
            <div class="m-val">{{ $hadir }}</div>
            <div class="m-note" style="color:var(--green-text)">{{ $hadirPct }}%</div>
            <div class="m-bar"><div class="m-fill" style="width:{{ $hadirPct }}%;background:var(--green)"></div></div>
        </div>
        <div class="m-card">
            <div class="m-label">Terlambat</div>
            <div class="m-val">{{ $terlambat }}</div>
            <div class="m-note" style="color:var(--amber-text)">hari ini</div>
            <div class="m-bar"><div class="m-fill" style="width:30%;background:var(--amber)"></div></div>
        </div>
        <div class="m-card">
            <div class="m-label">Status Saya</div>
            <div class="m-val" style="font-size:16px">
                @if($todayPresence) {{ ucfirst($todayPresence->status) }} @else — @endif
            </div>
            <div class="m-note">
                @if(!$todayPresence) Belum check in
                @elseif(!$todayPresence->check_out) Sudah check in
                @else Selesai @endif
            </div>
            <div class="m-bar"><div class="m-fill" style="width:{{ $todayPresence ? 100 : 0 }}%;background:var(--green)"></div></div>
        </div>
    </div>

    {{-- CONTENT GRID --}}
    <div class="presence-content-grid">

        {{-- TABEL (desktop) --}}
        <div class="card presence-table-wrap">
            <div class="panel-head">
                <span class="panel-title">Riwayat Kehadiran</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presences as $log)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px">
                                        <div class="av av-xs" style="background:var(--accent);color:#fff">
                                            {{ strtoupper(substr($log->employee->fullname,0,2)) }}
                                        </div>
                                        {{ $log->employee->fullname }}
                                    </div>
                                </td>
                                <td>{{ $log->check_in ? \Carbon\Carbon::parse($log->check_in)->format('H:i') : '—' }}</td>
                                <td>{{ $log->check_out ? \Carbon\Carbon::parse($log->check_out)->format('H:i') : '—' }}</td>
                                <td>
                                    @if($log->status == 'present')     <span class="badge badge-green">Hadir</span>
                                    @elseif($log->status == 'late')    <span class="badge badge-amber">Terlambat</span>
                                    @elseif($log->status == 'absen')   <span class="badge badge-red">Absen</span>
                                    @elseif($log->status == 'ijin')    <span class="badge badge-blue">Izin</span>
                                    @elseif($log->status == 'cuti')    <span class="badge badge-gray">Cuti</span>
                                    @else <span class="badge badge-gray">{{ $log->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:24px">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- CHART --}}
        <div class="card">
            <div class="panel-head">
                <span class="panel-title">Kehadiran Minggu Ini</span>
            </div>
            @php
                $weekData = [
                    ['label'=>'Sen','pct'=>80,'color'=>'var(--accent)'],
                    ['label'=>'Sel','pct'=>85,'color'=>'var(--accent)'],
                    ['label'=>'Rab','pct'=>90,'color'=>'var(--green)'],
                    ['label'=>'Kam','pct'=>70,'color'=>'var(--amber)'],
                    ['label'=>'Jum','pct'=>88,'color'=>'var(--green)'],
                ];
            @endphp
            <div class="week-grid">
                @foreach($weekData as $day)
                    <div class="week-day">
                        <div class="day-label">{{ $day['label'] }}</div>
                        <div class="day-bar">
                            <div class="day-fill" style="height:{{ $day['pct'] }}%;background:{{ $day['color'] }}"></div>
                        </div>
                        <div class="day-pct">{{ $day['pct'] }}%</div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- CARD LIST (mobile) — Riwayat Kehadiran --}}
    <div class="presence-card-list" style="margin-top:14px">
        <div style="font-size:14px;font-weight:600;color:var(--text-primary);margin-bottom:10px">
            Riwayat Kehadiran
        </div>

        @forelse($presences as $log)
            <div class="presence-card-item">
                <div class="task-card-header">
                    <div class="av av-sm" style="background:var(--accent);color:#ede9e3;flex-shrink:0">
                        {{ strtoupper(substr($log->employee->fullname,0,2)) }}
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="task-card-title">{{ $log->employee->fullname }}</div>
                    </div>
                    @if($log->status == 'present')     <span class="badge badge-green">Hadir</span>
                    @elseif($log->status == 'late')    <span class="badge badge-amber">Terlambat</span>
                    @elseif($log->status == 'absen')   <span class="badge badge-red">Absen</span>
                    @elseif($log->status == 'ijin')    <span class="badge badge-blue">Izin</span>
                    @elseif($log->status == 'cuti')    <span class="badge badge-gray">Cuti</span>
                    @else <span class="badge badge-gray">{{ $log->status }}</span>
                    @endif
                </div>
                <div class="presence-card-row">
                    <span class="presence-card-label">Check In</span>
                    <span class="presence-card-val">{{ $log->check_in ? \Carbon\Carbon::parse($log->check_in)->format('H:i') : '—' }}</span>
                </div>
                <div class="presence-card-row">
                    <span class="presence-card-label">Check Out</span>
                    <span class="presence-card-val">{{ $log->check_out ? \Carbon\Carbon::parse($log->check_out)->format('H:i') : '—' }}</span>
                </div>
            </div>
        @empty
            <div class="presence-card-item" style="text-align:center;color:var(--text-muted)">
                Tidak ada data
            </div>
        @endforelse
    </div>

</div>

@endsection