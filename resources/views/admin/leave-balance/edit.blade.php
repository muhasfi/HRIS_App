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
                
                <form action="{{ route('leave-balances.update', $leaveBalance->id) }}" method="POST">
                   @csrf
                   @method('PUT')
        
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select name="employee_id" id="employee_id"
                            class="form-control @error('employee_id') is-invalid @enderror">
                            <option value="" disabled>-- Select Employee --</option>

                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ old('employee_id', $leaveBalance->employee_id) == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->fullname }}
                                </option>
                            @endforeach

                        </select>

                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <label for="leave_type_id" class="form-label">Leave Type</label>
                        <select name="leave_type_id" id="leave_type_id"
                            class="form-control @error('leave_type_id') is-invalid @enderror">

                            <option value="" disabled>-- Select Leave Type --</option>

                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('leave_type_id', $leaveBalance->leave_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('leave_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <label for="year" class="form-label">Year</label>
                        <input type="text"
                            class="form-control @error('year') is-invalid @enderror"
                            name="year"
                            id="year"
                            value="{{ old('year', $leaveBalance->year) }}"
                            required>

                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <label for="total_days" class="form-label">Total Days</label>
                        <input type="number"
                            class="form-control @error('total_days') is-invalid @enderror"
                            name="total_days"
                            id="total_days"
                            value="{{ old('total_days', $leaveBalance->total_days) }}"
                            min="0"
                            required>

                        @error('total_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="used_days" class="form-label">Used Days</label>
                        <input type="number"
                            class="form-control @error('used_days') is-invalid @enderror"
                            name="used_days"
                            id="used_days"
                            value="{{ old('used_days', $leaveBalance->used_days) }}"
                            min="0"
                            required>

                        @error('used_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="remaining_days" class="form-label">Remaining Days</label>
                        <input type="number"
                            class="form-control @error('remaining_days') is-invalid @enderror"
                            name="remaining_days"
                            id="remaining_days"
                            value="{{ old('remaining_days', $leaveBalance->remaining_days) }}"
                            min="0"
                            disabled>
                    </div>
        
                    <button type="submit" class="btn btn-primary">Update Leave Balance</button>
                    <a href="{{ route('leave-balances.index') }}" class="btn btn-secondary">Back to Leave Balance List</a>

                </form>
            </div>
        </div>
    </section>
</div>

@endsection