@extends('layouts.master')

@section('title', 'Create Leave Balance')

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
                
                <form action="{{ route('leave-balances.store') }}" method="POST">
                   @csrf
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
                        <label for="year" class="form-label">Year</label>
                        <input type="text" 
                            class="form-control @error('year') is-invalid @enderror" 
                            name="year" 
                            id="year"
                            value="{{ old('year', date('Y')) }}" 
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
                            value="{{ old('total_days') }}" 
                            min="0"
                            required>
                        @error('total_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <button type="submit" class="btn btn-primary">Create Leave Balance</button>
                    <a href="{{ route('leave-balances.index') }}" class="btn btn-secondary">Back to Leave Balance List</a>

                </form>
            </div>
        </div>
    </section>
</div>

@endsection