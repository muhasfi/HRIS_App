@extends('layouts.master')

@section('title', 'Detail Leave Request')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Leave Request</h3>
                <p class="text-subtitle text-muted">Detail Leave Request.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('leave-requests.index') }}">Leave Request</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Show</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Leave Request</h5>
                <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>

            <div class="card-body">

                {{-- Info Karyawan --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted text-uppercase fw-bold mb-3">Employee Information</h6>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="text-muted" width="40%">Employee Name</td>
                                <td width="5%">:</td>
                                <td><strong>{{ $leaveRequest->employee->fullname }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Position</td>
                                <td>:</td>
                                <td>{{ $leaveRequest->employee->role->title ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Department</td>
                                <td>:</td>
                                <td>{{ $leaveRequest->employee->department->name ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="text-muted" width="40%">Status</td>
                                <td width="5%">:</td>
                                <td>
                                    @if ($leaveRequest->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif ($leaveRequest->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif ($leaveRequest->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($leaveRequest->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Submitted At</td>
                                <td>:</td>
                                <td>{{ $leaveRequest->created_at->format('d F Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Last Updated</td>
                                <td>:</td>
                                <td>{{ $leaveRequest->updated_at->format('d F Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                {{-- Detail Cuti --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted text-uppercase fw-bold mb-3">Leave Detail</h6>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="text-muted" width="40%">Leave Type</td>
                                <td width="5%">:</td>
                                <td><strong>{{ $leaveRequest->leaveType->name }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Start Date</td>
                                <td>:</td>
                                <td>{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">End Date</td>
                                <td>:</td>
                                <td>{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Days</td>
                                <td>:</td>
                                <td><span class="badge bg-primary">{{ $leaveRequest->total_days }} Days</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                {{-- Reason --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted text-uppercase fw-bold mb-2">Reason</h6>
                        <p class="border rounded p-3 bg-light">{{ $leaveRequest->reason }}</p>
                    </div>
                </div>

                {{-- Attachment --}}
                @if ($leaveRequest->attachment)
                    <hr>
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted text-uppercase fw-bold mb-3">Supporting Document</h6>
                            @php
                                $ext = pathinfo($leaveRequest->attachment, PATHINFO_EXTENSION);
                            @endphp

                            @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                <img 
                                    src="{{ asset('storage/' . $leaveRequest->attachment) }}" 
                                    alt="Attachment"
                                    class="img-fluid rounded border"
                                    style="max-height: 300px; object-fit: contain;">
                            @else
                                <div class="d-flex align-items-center gap-2 p-3 border rounded bg-light">
                                    <i class="bi bi-file-earmark-pdf text-danger fs-2"></i>
                                    <span class="text-muted">PDF Document</span>
                                </div>
                            @endif

                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $leaveRequest->attachment) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View File
                                </a>
                                <a href="{{ asset('storage/' . $leaveRequest->attachment) }}" 
                                   download
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Action buttons (HR only) --}}
                @if (session('role') == 'HR' && $leaveRequest->status == 'pending')
                    <hr>
                    <div class="row">
                        <div class="col-12 d-flex gap-2">
                            <a href="{{ route('leave-requests.confirm', $leaveRequest->id) }}" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Approve
                            </a>
                            <a href="{{ route('leave-requests.reject', $leaveRequest->id) }}" class="btn btn-danger">
                                <i class="bi bi-x-circle"></i> Reject
                            </a>
                        </div>
                    </div>
                @endif

            </div>{{-- end card-body --}}
        </div>{{-- end card --}}
    </section>
</div>

@endsection