@extends('employee.layouts.master')

@section('title', 'Edit Pengajuan Cuti')

@php
    $isEdit      = true;
    $pageTitle   = 'Edit Pengajuan Cuti';
@endphp

@section('content')

<div class="leave-form-page">
    <div class="card">

        <div class="panel-head" style="margin-bottom:20px">
            <span class="panel-title">Edit Pengajuan Cuti</span>
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

        <form method="POST" 
              action="{{ route('leave-requests.update', $leaveRequest->id) }}"
              enctype="multipart/form-data"
              id="leave-form">
            @csrf
            @method('PUT')

            {{-- EMPLOYEE --}}
            <div class="form-group">
                <label class="form-label">Employee</label>

                {{-- tampilkan nama --}}
                <input type="text" class="form-control"
                    value="{{ $leaveRequest->employee->fullname }}" readonly>

                {{-- kirim id --}}
                <input type="hidden" name="employee_id"
                    value="{{ $leaveRequest->employee->id }}">
            </div>

            {{-- SECTION 1 --}}
            <div class="form-section">
                <div class="form-section-title">Jenis & Durasi</div>

                <div class="form-group">
                    <label class="form-label">Jenis Cuti</label>
                    <select name="leave_type_id" class="form-control">
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ $leaveRequest->leave_type_id == $type->id ? 'selected' : '' }}>
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
                               value="{{ old('start_date', $leaveRequest->start_date) }}"
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
                               value="{{ old('end_date', $leaveRequest->end_date) }}"
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

            {{-- SECTION 2 --}}
            <div class="form-section">
                <div class="form-section-title">Alasan</div>

                <textarea name="reason" class="form-control">{{ $leaveRequest->reason }}</textarea>
            </div>

            {{-- SECTION 3: ATTACHMENT --}}
            <div class="form-section">
                <div class="form-section-title">Lampiran</div>

                {{-- FILE LAMA --}}
                @if ($leaveRequest->attachment)
                    <div class="attached-file" style="margin-bottom:10px">
                        @php
                            $ext = pathinfo($leaveRequest->attachment, PATHINFO_EXTENSION);
                        @endphp

                        @if (in_array($ext, ['jpg','jpeg','png']))
                            <img 
                                src="{{ asset('storage/' . $leaveRequest->attachment) }}"
                                style="max-height:100px">
                        @else
                            <span>📄 PDF File</span>
                        @endif

                        <a href="{{ asset('storage/' . $leaveRequest->attachment) }}"
                           target="_blank"
                           class="btn btn-sm">
                           Lihat File
                        </a>
                    </div>
                @endif

                {{-- UI BARU --}}
                <div class="attach-area" id="attach-area">
                    <input type="file" name="attachment"
                           accept="image/*,.pdf"
                           onchange="handleAttach(event)" />

                    <div class="attach-text">
                        {{ $leaveRequest->attachment ? 'Ganti file (opsional)' : 'Upload file' }}
                    </div>
                </div>

                <small class="text-muted">
                    Kosongkan jika tidak ingin mengganti file
                </small>

                <div id="attached-file" class="attached-file" style="display:none">
                    <span id="attached-name"></span>
                    <button type="button" onclick="removeAttach()">Hapus</button>
                </div>
            </div>

            {{-- ACTION --}}
            <div style="display:flex;justify-content:flex-end">
                <button type="submit" class="btn btn-primary">
                    Update
                </button>
            </div>

        </form>
    </div>
</div>

@endsection