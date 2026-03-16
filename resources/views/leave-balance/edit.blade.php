@extends('layouts.master')

@section('title', 'Edit Leave Balance')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Leave Balance</h3>
                <p class="text-subtitle text-muted">Manage Leave Balance data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('leave-requests.index') }}">Leave Balance</a></li>
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
                
                <form action="{{ route('leave-requests.update', $leaveRequest->id) }}" method="POST">
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
                        <label for="leave_type" class="form-label">Leave Type</label>
                        <select name="leave_type" id="leave_type" class="form-control">
                            <option value="" selected disabled>-- Select Leave Type --</option>
                            <option value="Sick Leave" {{ $leaveRequest->leave_type == 'Sick Leave' ? 'selected' : '' }}>Sick Leave</option>
                            <option value="Vacation" {{ $leaveRequest->leave_type == 'Vacation' ? 'selected' : '' }}>Vacation</option>
                            <option value="Birth Leave" {{ $leaveRequest->leave_type == 'Birth Leave' ? 'selected' : '' }}>Birth Leave</option>
                        </select>
                        @error('leave_type')
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
        
                    <button type="submit" class="btn btn-primary">Update Leave Balance</button>
                    <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">Back to Leave Balance List</a>

                </form>
            </div>
        </div>
    </section>
</div>

@endsection