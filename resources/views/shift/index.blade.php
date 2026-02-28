@extends('layouts.master')

@section('title', 'Shift')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Shift</h3>
                <p class="text-subtitle text-muted">Manage shift data</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item">shift</li>
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
                    @if (session('role') == 'HR')
                        <a href="{{ route('shifts.create') }}" class="btn btn-primary mb-3 ms-auto">New Shift</a>
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
                            <th>Name</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Late Tolerance (minutes)</th>
                            @if (session('role') == 'HR')
                                <th>Option</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shifts as $shift)
                            <tr>
                                <td>{{ $shift->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</td>
                                <td>{{ $shift->late_tolerance }}</td>
                                @if (session('role') == 'HR')
                                <td>
                                    <a href="{{ route('shifts.edit', $shift->id) }}" class="btn btn-info btn-sm">Edit</a>
                                    <form action="{{ route('shifts.destroy', $shift->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin?')">
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