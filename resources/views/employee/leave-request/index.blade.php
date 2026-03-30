@extends('employee.layouts.master')

@section('title', 'Leave Request — Karyawan')

@php
    $pageTitle   = 'Leave Request';
    $activePage  = 'leave-list';
    $breadcrumbs = [['label' => 'Leave Request']];
    // $headerCta   = ['label' => 'Ajukan Cuti', 'url' => route('leave-requests.create')];
@endphp

@section('content')

{{-- ===== SALDO CUTI ===== --}}
<div class="leave-bal-grid">

    @php
        $defaultBalances = [
            ['type'=>'Cuti Tahunan','used'=>8,'max'=>12,'pct'=>67,'color'=>'var(--accent)'],
            ['type'=>'Cuti Sakit','used'=>2,'max'=>10,'pct'=>20,'color'=>'var(--red)'],
            ['type'=>'Cuti Penting','used'=>1,'max'=>3,'pct'=>33,'color'=>'var(--amber)'],
            ['type'=>'Sisa Total','used'=>14,'max'=>null,'pct'=>56,'color'=>'var(--green)','suffix'=>'hari tersisa'],
        ];
        $balanceData = $balances ?? $defaultBalances;
    @endphp

    @foreach($balanceData as $bal)
        <div class="leave-bal-card">
            <div class="leave-bal-type">{{ $bal['type'] }}</div>
            <div class="leave-bal-num">
                <span class="leave-bal-used"
                      @if(isset($bal['suffix'])) style="color:var(--green-text)" @endif>
                    {{ $bal['used'] }}
                </span>
                <span class="leave-bal-of">
                    @if(isset($bal['suffix']))
                        {{ $bal['suffix'] }}
                    @else
                        / {{ $bal['max'] }} hari
                    @endif
                </span>
            </div>
            <div class="progress">
                <div class="progress-bar"
                     style="width:{{ $bal['pct'] }}%;background:{{ $bal['color'] }}">
                </div>
            </div>
        </div>
    @endforeach

</div>

{{-- ===== RIWAYAT CUTI ===== --}}
<div class="card">
    <div class="panel-head">
        <span class="panel-title">Riwayat Pengajuan Cuti</span>
        <a href="{{ route('leave-requests.create') }}"
           class="btn btn-primary btn-sm" style="margin-left:auto">
            + Ajukan Cuti
        </a>
    </div>

    <div class="leave-list">

        @forelse($leaveRequests as $leave)

            @php
                $start = \Carbon\Carbon::parse($leave->start_date);
                $end   = \Carbon\Carbon::parse($leave->end_date);
                $days  = $start->diffInDays($end) + 1;
            @endphp

            <div class="leave-item">

                {{-- ICON --}}
                <div class="leave-type-icon" style="background:var(--accent-soft)">
                    📋
                </div>

                {{-- INFO --}}
                <div style="flex:1;min-width:0">
                    <div class="leave-item-name">
                        {{ $leave->leaveType->name }}
                    </div>

                    <div class="leave-item-dates">
                        {{ $start->format('d M Y') }} –
                        {{ $end->format('d M Y') }}
                        · {{ $days }} hari
                    </div>

                    @if($leave->reason)
                        <div style="font-size:11px;color:var(--text-muted);margin-top:2px">
                            {{ $leave->reason }}
                        </div>
                    @endif
                </div>

                {{-- RIGHT SIDE --}}
                <div class="leave-item-right">

                    {{-- STATUS --}}
                    <div style="margin-bottom:5px">
                        @if($leave->status == 'confirm')
                            <span class="badge badge-green">Disetujui</span>
                        @elseif($leave->status == 'reject')
                            <span class="badge badge-red">Ditolak</span>
                        @else
                            <span class="badge badge-amber">Menunggu</span>
                        @endif
                    </div>

                    {{-- ACTION --}}
                    <div class="leave-action-btns">

                        {{-- EMPLOYEE ACTION --}}
                        @if($leave->status == 'pending')
                            <a href="{{ route('leave-requests.edit', $leave->id) }}"
                               class="btn btn-sm">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('leave-requests.destroy', $leave->id) }}"
                                  style="display:inline"
                                  onsubmit="return confirm('Batalkan pengajuan cuti ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Batalkan
                                </button>
                            </form>
                        @endif

                        {{-- DETAIL --}}
                        <a href="{{ route('leave-requests.show', $leave->id) }}"
                           class="btn btn-info btn-sm">
                            Detail
                        </a>

                    </div>
                </div>

            </div>

        @empty

            {{-- EMPTY STATE --}}
            <div style="text-align:center;padding:32px 0;color:var(--text-muted)">
                <div style="font-size:28px;margin-bottom:8px">🏖️</div>
                <div style="font-size:13px">Belum ada pengajuan cuti</div>
            </div>

        @endforelse

    </div>
</div>

@endsection