@extends('layouts.master')

@section('title', 'Presence')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Presence</h3>
                <p class="text-subtitle text-muted">Manage Presence data</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item">Presence</li>
                        <li class="breadcrumb-item active" aria-current="page">Index</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            <div class="card-body">
                <div class="d-flex">
                    @if (session('role') != 'HR')
                        @if(!$todayPresence)
                        @if($hasSchedule)
                            <a href="{{ route('presences.create') }}" class="btn btn-success mb-3 ms-auto">
                                Check In Today
                            </a>
                        @else
                            <button type="button" class="btn btn-secondary mb-3 ms-auto" disabled>
                                Schedule Belum Dibuat
                            </button>
                        @endif

                        @elseif(!$todayPresence->check_out)
                            <a href="{{ route('presences.create') }}" class="btn btn-warning mb-3 ms-auto">
                                Check Out
                            </a>
                        @else
                        <span class="badge bg-success ms-auto">Absensi Hari Ini Selesai</span>
                        @endif
                    @elseif(session('role') == 'HR')
                        <a href="{{ route('presences.create') }}" class="btn btn-primary mb-3 ms-auto">New Presence</a>
                    @endif
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Date</th>
                            <th>Status</th>
                            @if (session('role') == 'HR')
                                <th>Option</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($presences as $presence)
                            <tr>
                                <td>{{ $presence->employee->fullname }}</td>

                                <td>
                                    {{ $presence->check_in 
                                        ? \Carbon\Carbon::parse($presence->check_in)->format('H:i') 
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $presence->check_out 
                                        ? \Carbon\Carbon::parse($presence->check_out)->format('H:i') 
                                        : '-' }}
                                </td>

                                <td>{{ $presence->date }}</td>

                                <td>
                                   @if($presence->status == 'present')
                                        <span class="badge bg-success">Present</span>
                                    @elseif($presence->status == 'late')
                                        <span class="badge bg-warning">Late</span>
                                    @elseif($presence->status == 'absen')
                                        <span class="badge bg-danger">Absen</span>
                                    @elseif($presence->status == 'ijin')
                                        <span class="badge bg-info">Ijin</span>
                                    @elseif($presence->status == 'cuti')
                                        <span class="badge bg-primary">Cuti</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($presence->status) }}</span>
                                    @endif
                                </td>

                                @if (session('role') == 'HR')
                                <td>
                                    <a href="{{ route('presences.edit', $presence->id) }}" class="btn btn-info btn-sm">Edit</a>

                                    <form action="{{ route('presences.destroy', $presence->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda Yakin?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                                @endif

                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@endsection