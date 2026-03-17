@extends('layouts.master')

@section('title', 'Edit Leave Request')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Leave Request</h3>
                <p class="text-subtitle text-muted">Manage Leave Request data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('leave-requests.index') }}">Leave Request</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $erorr)
                                <li>{{ $erorr }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('leave-requests.update', $leaveRequest->id) }}" method="POST" enctype="multipart/form-data">
                   @csrf
                   @method('PUT')
        
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control">
                            <option value="" selected disabled>-- Select Employee --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" @if(old('employee_id', $leaveRequest->employee_id) == $employee->id) selected @endif>{{ $employee->fullname }}</option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="leave_type_id" class="form-label">Leave Type</label>

                        <select name="leave_type_id" id="leave_type_id" class="form-control">
                            <option value="" disabled>-- Select Leave Type --</option>

                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('leave_type_id', $leaveRequest->leave_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('leave_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="text" class="form-control date @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date', $leaveRequest->start_date) }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="text" class="form-control date @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date',  $leaveRequest->end_date) }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea 
                            class="form-control @error('reason') is-invalid @enderror" 
                            name="reason" 
                            id="reason"
                            rows="3"
                            required>{{ old('reason', $leaveRequest->reason) }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="attachment" class="form-label">Supporting Document (optional)</label>

                        {{-- Preview file lama jika ada --}}
                        @if ($leaveRequest->attachment)
                            <div class="mb-2 p-2 border rounded d-flex align-items-center gap-2">
                                @php
                                    $ext = pathinfo($leaveRequest->attachment, PATHINFO_EXTENSION);
                                @endphp

                                @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                    {{-- Preview gambar --}}
                                    <img 
                                        src="{{ asset('storage/' . $leaveRequest->attachment) }}" 
                                        alt="Attachment Preview"
                                        style="max-height: 100px; max-width: 200px; object-fit: contain;">
                                @else
                                    {{-- Preview PDF --}}
                                    <i class="bi bi-file-earmark-pdf text-danger fs-3"></i>
                                @endif

                                <a href="{{ asset('storage/' . $leaveRequest->attachment) }}" 
                                target="_blank" 
                                class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View Current File
                                </a>
                            </div>
                        @endif

                        <input 
                            type="file" 
                            name="attachment" 
                            id="attachment" 
                            class="form-control @error('attachment') is-invalid @enderror" 
                            accept=".pdf,.jpg,.jpeg,.png">

                        @if ($leaveRequest->attachment)
                            <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti file.</div>
                        @endif

                        @error('attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <button type="submit" class="btn btn-primary">Update Leave Request</button>
                    <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">Back to Leave Request List</a>

                </form>
            </div>
        </div>
    </section>
</div>

@endsection