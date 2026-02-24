@extends('layouts.master')

@section('title', 'Leave Request')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Leave Request</h3>
                <p class="text-subtitle text-muted">Manage Leave Request data</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item">Leave Request</li>
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
                    {{-- @if (session('role') == 'HR') --}}
                        <a href="{{ route('leave-requests.create') }}" class="btn btn-primary mb-3 ms-auto">New Leave Request</a>
                    {{-- @endif --}}
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
                            <th>Leave Type</th>
                            <th>Start date</th>
                            <th>End date</th>
                            <th>Status</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRequests as $leaveRequest)
                            <tr>
                                <td>{{ $leaveRequest->employee->fullname }}</td>
                                <td>{{ $leaveRequest->leave_type }}</td>
                                <td>{{ $leaveRequest->start_date }}</td>
                                <td>{{ $leaveRequest->end_date }}</td>
                                <td>
                                    @if($leaveRequest->status == 'confirm')
                                        <span class="text-success">{{ ucfirst($leaveRequest->status) }}</span>
                                    @elseif($leaveRequest->status == 'reject')
                                        <span class="text-danger">{{ ucfirst($leaveRequest->status) }}</span>
                                    @else
                                        <span class="text-warning">{{ ucfirst($leaveRequest->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($leaveRequest->status == 'pending')
                                        <a href="{{ route('leave-requests.confirm', $leaveRequest->id) }}" class="btn btn-success btn-sm">Confirm</a>
                                        <a href="{{ route('leave-requests.reject', $leaveRequest->id) }}" class="btn btn-warning btn-sm">Reject</a>

                                    @elseif ($leaveRequest->status == 'reject')
                                        <a href="{{ route('leave-requests.confirm', $leaveRequest->id) }}" class="btn btn-success btn-sm">Confirm</a>

                                    @elseif ($leaveRequest->status == 'confirm')
                                        <a href="{{ route('leave-requests.reject', $leaveRequest->id) }}" class="btn btn-warning btn-sm">Reject</a>

                                    @endif


                                    <a href="{{ route('leave-requests.edit', $leaveRequest->id) }}"class="btn btn-info btn-sm">Edit</a>

                                    <form action="{{ route('leave-requests.destroy', $leaveRequest->id) }}" method="POST" style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda Yakin?')">Delete</button>
                                    </form>
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