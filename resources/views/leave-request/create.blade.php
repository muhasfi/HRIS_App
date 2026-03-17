@extends('layouts.master')

@section('title', 'Create Leave Request')

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
                        <li class="breadcrumb-item active" aria-current="page">New</li>
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
                
                <form action="{{ route('leave-requests.store') }}" method="POST" enctype="multipart/form-data">
                   @csrf
        
                    @if(session('role') === 'HR')
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>

                        <select name="employee_id" id="employee_id"
                            class="form-control @error('employee_id') is-invalid @enderror">

                            <option value="" selected disabled>-- Select Employee --</option>

                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->fullname }}
                                </option>
                            @endforeach

                        </select>

                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @else
                    <div class="mb-3">
                        <label class="form-label">Employee</label>

                        <input type="text"
                            class="form-control"
                            value="{{ auth()->user()->employee->fullname }}"
                            readonly>

                        <input type="hidden"
                            name="employee_id"
                            value="{{ auth()->user()->employee->id }}">
                    </div>
                    @endif
        
                    <div class="mb-3">
                        <label for="leave_type_id" class="form-label">Leave Type</label>
                        <select name="leave_type_id" id="leave_type_id" class="form-control">
                            <option value="" selected disabled>-- Select Leave Type --</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}">
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
                        <input type="text" class="form-control date @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="text" class="form-control date @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date') }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea  type="text" class="form-control @error('reason') is-invalid @enderror" name="reason" value="{{ old('reason') }}" required> </textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="attachment" class="form-label">Supporting Document (optional)</label>
                        <input type="file" name="attachment" id="attachment" class="form-control @error('attachment') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                        @error('attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <button type="submit" class="btn btn-primary">Create Leave Request</button>
                    <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">Back to Leave Request List</a>

                </form>
            </div>
        </div>
    </section>
</div>

@endsection