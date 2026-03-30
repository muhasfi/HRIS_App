@extends('layouts.master')

@section('title', 'Show Presence')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Show Presences</h3>
                <p class="text-subtitle text-muted">Show Presences data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('presences.index') }}">Presences</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- INFO UMUM --}}
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="text-muted small">Employee</label>
                        <p class="fw-bold">{{ $presence->employee->fullname }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Date</label>
                        <p class="fw-bold">{{ \Carbon\Carbon::parse($presence->date)->format('d F Y') }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Status</label>
                        <p>
                            @if ($presence->status == 'present')
                                <span class="badge bg-success">Present</span>
                            @elseif ($presence->status == 'late')
                                <span class="badge bg-warning text-dark">Late ({{ $presence->late_minutes }} menit)</span>
                            @elseif ($presence->status == 'absent')
                                <span class="badge bg-danger">Absent</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($presence->status) }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                {{-- CHECK IN & CHECK OUT --}}
                <div class="row mt-3">

                    {{-- CHECK IN --}}
                    <div class="col-md-6 border-end">
                        <h5 class="text-success mb-3">🟢 Check In</h5>

                        <div class="mb-3">
                            <label class="text-muted small">Waktu Check In</label>
                            <p class="fw-bold">
                                {{ $presence->check_in ? \Carbon\Carbon::parse($presence->check_in)->format('H:i:s') : '-' }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Lokasi Check In</label>
                            <p class="fw-bold">
                                @if ($presence->check_in_lat && $presence->check_in_long)
                                    {{ $presence->check_in_lat }}, {{ $presence->check_in_long }}
                                    <br>
                                    <a href="https://www.google.com/maps?q={{ $presence->check_in_lat }},{{ $presence->check_in_long }}" target="_blank" class="btn btn-sm btn-outline-success mt-1">
                                        📍 Lihat Maps
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Foto Check In</label>
                            <div class="mt-1">
                                @if ($presence->photo_check_in)
                                    <img src="{{ asset('storage/' . $presence->photo_check_in) }}" width="280" style="border-radius:8px; border:2px solid #198754;">
                                @else
                                    <p class="text-muted">Tidak ada foto</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- CHECK OUT --}}
                    <div class="col-md-6 ps-4">
                        <h5 class="text-danger mb-3">🔴 Check Out</h5>

                        <div class="mb-3">
                            <label class="text-muted small">Waktu Check Out</label>
                            <p class="fw-bold">
                                {{ $presence->check_out ? \Carbon\Carbon::parse($presence->check_out)->format('H:i:s') : '-' }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Lokasi Check Out</label>
                            <p class="fw-bold">
                                @if ($presence->check_out_lat && $presence->check_out_long)
                                    {{ $presence->check_out_lat }}, {{ $presence->check_out_long }}
                                    <br>
                                    <a href="https://www.google.com/maps?q={{ $presence->check_out_lat }},{{ $presence->check_out_long }}" target="_blank" class="btn btn-sm btn-outline-danger mt-1">
                                        📍 Lihat Maps
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Foto Check Out</label>
                            <div class="mt-1">
                                @if ($presence->photo_check_out)
                                    <img src="{{ asset('storage/' . $presence->photo_check_out) }}" width="280" style="border-radius:8px; border:2px solid #dc3545;">
                                @else
                                    <p class="text-muted">Tidak ada foto</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <hr>

                <a href="{{ route('presences.index') }}" class="btn btn-secondary">Back to List</a>

            </div>
        </div>
    </section>
</div>

@endsection