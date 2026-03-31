@extends('layouts.master')

@section('title', 'Dashboard')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/compiled/css/table-datatable.css') }}">



@endsection

@section('content')

<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
    </div>
</div>

<div class="page-content"> 
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon purple mb-2">
                                        <i class="icon dripicons dripicons-tag"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Departments</h6>
                                    <h6 class="font-extrabold mb-0">{{ $departments }}</h6>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card"> 
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="icon dripicons dripicons-user-group"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Employees</h6>
                                    <h6 class="font-extrabold mb-0">{{ $employees }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="icon dripicons dripicons-alarm"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Presences</h6>
                                    <h6 class="font-extrabold mb-0">{{ $presences }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="icon dripicons dripicons-to-do"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Payrolls</h6>
                                    <h6 class="font-extrabold mb-0">{{ $payrolls }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Presence</h4>
                        </div>
                        <div class="card-body">
                            <div id="presence" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Payroll</h4>
                        </div>
                        <div class="card-body">
                            <div id="payroll" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Latest Task</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-lg">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Detail</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tasks as $task)
                                        <tr>
                                            <td class="col-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-md">
                                                        <img src="https://ui-avatars.com/api/?name={{ $task->employee->fullname }}&background=random">
                                                    </div>
                                                    <p class="font-bold ms-3 mb-0">{{ $task->employee->fullname }}</p>
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <p class=" mb-0">{{ $task->title }}</p>
                                            </td>
                                            <td class="col-auto">
                                                <p class=" mb-0">{{ $task->status }}</p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

{{-- ===================== LEAVE SECTION (HR ONLY) ===================== --}}
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                Pending Leave Requests
                                @if($pendingLeaves->count() > 0)
                                    <span class="badge bg-danger ms-2">
                                        {{ $pendingLeaves->count() }}
                                    </span>
                                @endif
                            </h4>
                        </div>

                        <div class="card-body">
                            @if($pendingLeaves->isEmpty())
                                <p class="text-muted text-center py-3">
                                    Tidak ada pengajuan pending.
                                </p>
                            @else
                            <div class="table-responsive">
                                <table class="table table-hover table-lg">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Leave Type</th>
                                            <th>Date</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                            <th>Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingLeaves as $leave)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-md">
                                                        <img src="https://ui-avatars.com/api/?name={{ $leave->employee->fullname }}&background=random">
                                                    </div>
                                                    <p class="font-bold ms-3 mb-0">
                                                        {{ $leave->employee->fullname }}
                                                    </p>
                                                </div>
                                            </td>

                                            <td>{{ $leave->leaveType->name }}</td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }}
                                                –
                                                {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                            </td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($leave->start_date)
                                                    ->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} hari
                                            </td>

                                            <td>
                                                <span class="badge bg-warning text-dark">
                                                    Pending
                                                </span>
                                            </td>

                                            <td>
                                                <a href="{{ route('leave-requests.show', $leave->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                    Review
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
{{-- ===================== END LEAVE ===================== --}}
        </div>
    </section>
</div>

<!-- Script langsung di sini -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    // ==== PRESENCE CHART ====
    var presenceOptions = {
        chart: {
            type: 'bar'
        },
        series: [{
            name: 'Presences',
            data: @json(array_values($presenceChart->toArray()))
        }],
        xaxis: {
            categories: @json(array_keys($presenceChart->toArray()))
        }
    };

    var presenceChart = new ApexCharts(document.querySelector("#presence"), presenceOptions);
    presenceChart.render();

    // ==== PAYROLL CHART ====
    var payrollOptions = {
        chart: {
            type: 'line'
        },
        series: [{
            name: 'Total Payroll',
            data: @json(array_values($payrollChart->toArray()))
        }],
        xaxis: {
            categories: @json(array_keys($payrollChart->toArray()))
        }
    };

    var payrollChart = new ApexCharts(document.querySelector("#payroll"), payrollOptions);
    payrollChart.render();
</script>


@endsection