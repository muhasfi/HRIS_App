@extends('layouts.master')

@section('title', 'Edit Employee Schedule')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Employee Schedule</h3>
                <p class="text-subtitle text-muted">Manage Employee Schedule data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employee-schedules.index') }}">Employee Schedule</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-calendar-week me-2"></i>
                            Edit Schedule — {{ $employee->name }}
                        </h4>
                    </div>

                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @elseif (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-x-circle me-1"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('employee-schedules.update', $employee->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">Day</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(\App\Models\EmployeeSchedule::days() as $dayNumber => $dayName)
                                            @php
                                                $schedule = $employee->schedules->where('day_of_week', $dayNumber)->first();
                                            @endphp
                                            <tr>
                                                <td class="text-muted fw-bold">{{ $dayNumber }}</td>
                                                <td>
                                                    <span class="fw-semibold">
                                                        <i class="bi bi-calendar-day me-1 text-primary"></i>{{ $dayName }}
                                                    </span>
                                                </td>
                                                <td style="cursor:pointer;" onclick="this.querySelector('input').showPicker()">
                                                    <div class="d-flex align-items-center gap-2 pe-none">
                                                        <i class="bi bi-clock text-primary"></i>
                                                        <input type="time"
                                                            name="schedules[{{ $dayNumber }}][start_time]"
                                                            value="{{ old("schedules.$dayNumber.start_time", $schedule->start_time ?? '') }}"
                                                            class="form-control @error("schedules.$dayNumber.start_time") is-invalid @enderror"
                                                            onclick="event.stopPropagation()"
                                                            required>
                                                    </div>
                                                    @error("schedules.$dayNumber.start_time")
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td style="cursor:pointer;" onclick="this.querySelector('input').showPicker()">
                                                    <div class="d-flex align-items-center gap-2 pe-none">
                                                        <i class="bi bi-clock text-primary"></i>
                                                        <input type="time"
                                                            name="schedules[{{ $dayNumber }}][end_time]"
                                                            value="{{ old("schedules.$dayNumber.end_time", $schedule->end_time ?? '') }}"
                                                            class="form-control @error("schedules.$dayNumber.end_time") is-invalid @enderror"
                                                            onclick="event.stopPropagation()"
                                                            required>
                                                    </div>
                                                    @error("schedules.$dayNumber.end_time")
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('employee-schedules.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-lg me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i> Update Schedule
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection