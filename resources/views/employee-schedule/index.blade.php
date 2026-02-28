@extends('layouts.master')

@section('title', 'Employee Schedule')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Employee Schedule</h3>
                <p class="text-subtitle text-muted">Manage Employee Schedule data</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Employee Schedule</li>
                        <li class="breadcrumb-item active" aria-current="page">Index</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Schedule</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->fullname }}</td>
                            <td>
                                @foreach($employee->schedules as $schedule)
                                    {{ \App\Models\EmployeeSchedule::days()[$schedule->day_of_week] }}:
                                    {{ $schedule->start_time }} - {{ $schedule->end_time }}<br>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('employee-schedules.edit', $employee->id) }}" class="btn btn-sm btn-primary">
                                    Shift Options
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </section>
</div>

@endsection