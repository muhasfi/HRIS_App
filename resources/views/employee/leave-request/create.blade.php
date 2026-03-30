@extends('employee.layouts.master')

@section('title', ($isEdit ?? false) ? 'Edit Pengajuan Cuti' : 'Ajukan Cuti — Karyawan HR')

@php
    $isEdit      = $isEdit ?? false;
    $pageTitle   = $isEdit ? 'Edit Pengajuan Cuti' : 'Ajukan Cuti Baru';
    $activePage  = 'leave-list';
    $breadcrumbs = [
        ['label' => 'Leave Request', 'url' => route('leave-requests.index')],
        ['label' => $isEdit ? 'Edit Pengajuan' : 'Ajukan Cuti Baru'],
    ];
    $headerCta   = null;
@endphp

@section('content')

<div class="leave-form-page">
    <div class="card">

        <div class="panel-head" style="margin-bottom:20px">
            <span class="panel-title">
                {{ $isEdit ? 'Edit Pengajuan Cuti' : 'Ajukan Cuti Baru' }}
            </span>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('leave-requests.store') }}"
              enctype="multipart/form-data"
              id="leave-form">
            @csrf

            {{-- EMPLOYEE --}}
            @if(session('role') === 'HR')
            <div class="form-group">
                <label class="form-label">Employee</label>
                <select name="employee_id" class="form-control">
                    <option value="">-- Select Employee --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">
                            {{ $employee->fullname }}
                        </option>
                    @endforeach
                </select>
            </div>
            @else
            <div class="form-group">
                <label class="form-label">Employee</label>
                <input type="text" class="form-control"
                    value="{{ auth()->user()->employee->fullname }}" readonly>
                <input type="hidden" name="employee_id"
                    value="{{ auth()->user()->employee->id }}">
            </div>
            @endif

            {{-- ── SECTION 1: Jenis & Durasi ── --}}
            <div class="form-section">
                <div class="form-section-title">Jenis &amp; Durasi</div>

                <div class="form-group">
                    <label class="form-label">Jenis Cuti</label>
                    <select name="leave_type_id" class="form-control">
                        <option value="">-- Select Leave Type --</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}">
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label" for="lf-start">
                            Tanggal Mulai <span class="req">*</span>
                        </label>
                        <input type="date"
                               class="form-control @error('start_date') is-invalid @enderror"
                               id="lf-start" name="start_date"
                               value="{{ old('start_date') }}"
                               onchange="calcDays()" />
                        @error('start_date')
                            <div class="form-hint" style="color:var(--red)">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="lf-end">
                            Tanggal Selesai <span class="req">*</span>
                        </label>
                        <input type="date"
                               class="form-control @error('end_date') is-invalid @enderror"
                               id="lf-end" name="end_date"
                               value="{{ old('end_date') }}"
                               onchange="calcDays()" />
                        @error('end')
                            <div class="form-hint" style="color:var(--red)">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div id="day-counter-wrap" style="display:none">
                    <div class="day-counter">
                        <div class="day-counter-num" id="day-count">0</div>
                        <div>
                            <div class="day-counter-label">hari kerja</div>
                            <div style="font-size:11px;color:var(--accent-text);opacity:0.8">
                                Tidak termasuk hari libur nasional
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 2: Alasan & Keterangan ── --}}
            <div class="form-section">
                <div class="form-section-title">Alasan &amp; Keterangan</div>

                <div class="form-group">
                    <label class="form-label" for="lf-reason">
                        Alasan Pengajuan <span class="req">*</span>
                    </label>
                    <textarea class="form-control @error('reason') is-invalid @enderror"
                              id="lf-reason" name="reason"
                              placeholder="Jelaskan alasan pengajuan cuti Anda...">{{ old('reason', $leave->reason ?? '') }}</textarea>
                    @error('reason')
                        <div class="form-hint" style="color:var(--red)">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ── SECTION 3: Lampiran ── --}}
            <div class="form-section">
                <div class="form-section-title">Lampiran (opsional)</div>

                <div class="attach-area" id="attach-area">
                    <input type="file" name="attachment"
                           accept="image/*,.pdf"
                           onchange="handleAttach(event)" />
                    <div class="attach-icon">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                             stroke="var(--text-secondary)" stroke-width="1.5" stroke-linecap="round">
                            <path d="M3 11.5V14a1.5 1.5 0 001.5 1.5h9A1.5 1.5 0 0015 14v-2.5M9 2.5v9M6 8.5l3 3 3-3"/>
                        </svg>
                    </div>
                    <div class="attach-text">Klik atau seret file ke sini</div>
                    <div class="attach-hint">
                        Surat dokter, surat keterangan, dll (PDF / JPG, maks 5MB)
                    </div>
                </div>

                <div id="attached-file" class="attached-file" style="display:none">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                         stroke="var(--green-text)" stroke-width="1.5" stroke-linecap="round">
                        <path d="M2 7l4 4 6-7"/>
                    </svg>
                    <span class="attached-file-name" id="attached-name">—</span>
                    <button type="button" class="btn btn-sm"
                            style="padding:3px 8px;font-size:11px"
                            onclick="removeAttach()">Hapus</button>
                </div>
            </div>

            {{-- ── FORM ACTIONS ── --}}
            <div style="display:flex;gap:8px;justify-content:flex-end;
                        padding-top:14px;border-top:1px solid var(--border)">
                <a href="{{ route('leave-requests.index') }}" class="btn">Batal</a>
                <button type="submit" class="btn btn-primary" id="lf-submit">
                    <svg viewBox="0 0 14 14"><path d="M2 7l4 4 6-7"/></svg>
                    <span id="lf-submit-txt">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Kirim Pengajuan' }}
                    </span>
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if($isEdit && isset($leave))
            calcDays();
        @endif
    });
</script>
@endpush
