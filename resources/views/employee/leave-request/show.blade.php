@extends('employee.layouts.master')

@section('title', 'Detail Leave Request')

@php
    $pageTitle   = 'Leave Request';
    $activePage  = 'leave-requests';
    $breadcrumbs = [
        ['label' => 'Leave Request', 'url' => route('leave-requests.index')],
        ['label' => 'Detail'],
    ];
    $headerCta = null;
@endphp

@section('content')

<div class="card">

    {{-- ===== HEADER ===== --}}
    <div class="panel-head">
        <span class="panel-title">Detail leave request</span>
        <a href="{{ route('leave-requests.index') }}" class="btn btn-sm btn-outline">
            <svg viewBox="0 0 14 14" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round">
                <path d="M9 2L4 7l5 5"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="detail-body">

        {{-- ===== HERO: Avatar + Nama + Status ===== --}}
        <div class="detail-hero">
            <div class="av av-lg" style="background:var(--accent);color:#ede9e3">
                {{ strtoupper(substr($leaveRequest->employee->fullname, 0, 2)) }}
            </div>
            <div style="flex:1;min-width:0">
                <div class="detail-hero-name">{{ $leaveRequest->employee->fullname }}</div>
                <div class="detail-hero-sub">
                    {{ $leaveRequest->employee->role->title ?? '-' }}
                    &nbsp;·&nbsp;
                    {{ $leaveRequest->employee->department->name ?? '-' }}
                </div>
            </div>
            @if($leaveRequest->status === 'approved')
                <span class="badge badge-green">Disetujui</span>
            @elseif($leaveRequest->status === 'pending')
                <span class="badge badge-amber">Menunggu</span>
            @elseif($leaveRequest->status === 'rejected')
                <span class="badge badge-red">Ditolak</span>
            @else
                <span class="badge">{{ ucfirst($leaveRequest->status) }}</span>
            @endif
        </div>

        <div class="detail-divider"></div>

        {{-- ===== INFORMASI KARYAWAN ===== --}}
        <div class="detail-section-label">Informasi karyawan</div>
        <div class="detail-info-grid">

            <div class="detail-info-item">
                <div class="detail-info-key">Nama karyawan</div>
                <div class="detail-info-val">{{ $leaveRequest->employee->fullname }}</div>
            </div>

            <div class="detail-info-item">
                <div class="detail-info-key">Status</div>
                <div class="detail-info-val">
                    @if($leaveRequest->status === 'approved')
                        <span class="badge badge-green">Disetujui</span>
                    @elseif($leaveRequest->status === 'pending')
                        <span class="badge badge-amber">Menunggu</span>
                    @elseif($leaveRequest->status === 'rejected')
                        <span class="badge badge-red">Ditolak</span>
                    @else
                        <span class="badge">{{ ucfirst($leaveRequest->status) }}</span>
                    @endif
                </div>
            </div>

            <div class="detail-info-item">
                <div class="detail-info-key">Jabatan</div>
                <div class="detail-info-val">{{ $leaveRequest->employee->role->title ?? '-' }}</div>
            </div>

            <div class="detail-info-item">
                <div class="detail-info-key">Diajukan pada</div>
                <div class="detail-info-val">{{ $leaveRequest->created_at->format('d F Y, H:i') }}</div>
            </div>

            <div class="detail-info-item">
                <div class="detail-info-key">Departemen</div>
                <div class="detail-info-val">{{ $leaveRequest->employee->department->name ?? '-' }}</div>
            </div>

            <div class="detail-info-item">
                <div class="detail-info-key">Terakhir diperbarui</div>
                <div class="detail-info-val">{{ $leaveRequest->updated_at->format('d F Y, H:i') }}</div>
            </div>

        </div>

        <div class="detail-divider"></div>

        {{-- ===== DETAIL CUTI ===== --}}
        <div class="detail-section-label">Detail cuti</div>
        <div class="detail-info-grid">

            <div class="detail-info-item">
                <div class="detail-info-key">Jenis cuti</div>
                <div class="detail-info-val">{{ $leaveRequest->leaveType->name }}</div>
            </div>

            <div class="detail-info-item">
                <div class="detail-info-key">Total hari</div>
                <div class="detail-info-val">
                    <span class="badge badge-blue">{{ $leaveRequest->total_days }} hari</span>
                </div>
            </div>

            <div class="detail-info-item">
                <div class="detail-info-key">Tanggal mulai</div>
                <div class="detail-info-val">
                    {{ \Carbon\Carbon::parse($leaveRequest->start_date)->translatedFormat('d F Y') }}
                </div>
            </div>

            <div class="detail-info-item">
                <div class="detail-info-key">Tanggal selesai</div>
                <div class="detail-info-val">
                    {{ \Carbon\Carbon::parse($leaveRequest->end_date)->translatedFormat('d F Y') }}
                </div>
            </div>

        </div>

        {{-- Timeline bar --}}
        <div class="leave-timeline">
            <div class="tl-dot" style="background:var(--amber)"></div>
            <div class="tl-line"></div>
            <span class="tl-label">
                {{ \Carbon\Carbon::parse($leaveRequest->start_date)->translatedFormat('d M') }}
            </span>
            <div class="tl-line" style="flex:3"></div>
            <span class="tl-label">
                {{ \Carbon\Carbon::parse($leaveRequest->end_date)->translatedFormat('d M') }}
            </span>
            <div class="tl-line"></div>
            <div class="tl-dot" style="background:var(--amber)"></div>
        </div>

        <div class="detail-divider"></div>

        {{-- ===== ALASAN ===== --}}
        <div class="detail-section-label">Alasan</div>
        <div class="reason-box">{{ $leaveRequest->reason }}</div>

        {{-- ===== LAMPIRAN ===== --}}
        @if($leaveRequest->attachment)
            <div class="detail-divider"></div>
            <div class="detail-section-label">Dokumen pendukung</div>

            @php
                $ext     = strtolower(pathinfo($leaveRequest->attachment, PATHINFO_EXTENSION));
                $fileUrl = asset('storage/' . $leaveRequest->attachment);
                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
            @endphp

            @if($isImage)
                <img src="{{ $fileUrl }}"
                     alt="Lampiran"
                     class="attachment-img">
            @else
                <div class="attachment-box">
                    <div class="att-icon">
                        <svg viewBox="0 0 18 18" style="width:18px;height:18px;stroke:#A32D2D;fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round">
                            <path d="M10 2H4a1 1 0 00-1 1v12a1 1 0 001 1h10a1 1 0 001-1V7l-5-5z"/>
                            <path d="M10 2v5h5"/>
                        </svg>
                    </div>
                    <div>
                        <div class="att-filename">{{ basename($leaveRequest->attachment) }}</div>
                        <div class="att-type">{{ strtoupper($ext) }} Document</div>
                    </div>
                </div>
            @endif

            <div class="att-actions">
                <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline">
                    <svg viewBox="0 0 13 13" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round">
                        <circle cx="6.5" cy="6.5" r="2"/>
                        <path d="M1 6.5C2.5 3.5 9.5 3.5 12 6.5c-2.5 3-9.5 3-11 0z"/>
                    </svg>
                    Lihat file
                </a>
                <a href="{{ $fileUrl }}" download class="btn btn-sm btn-outline">
                    <svg viewBox="0 0 13 13" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round">
                        <path d="M1 8v3h11V8M6.5 1v7M4 6l2.5 2.5L9 6"/>
                    </svg>
                    Unduh
                </a>
            </div>
        @endif

    </div>{{-- end detail-body --}}
</div>{{-- end card --}}

@endsection