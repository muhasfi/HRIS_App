@extends('layouts.master')

@section('title', 'Show Employee')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Employee</h3>
                <p class="text-subtitle text-muted">Manage employees data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employee</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            <div class="card-body">
                
            <div class="mb-3">
                    <label class="form-label">Fullname</label>
                    <p>{{ $employee->fullname }}</p>
                </div>
        
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <p>{{ $employee->user->email }}</p>
                </div>
        
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <p>{{ $employee->role->title }}</p>
                </div>

                <div class="mb-3">
                    <label class="form-label">Departement</label>
                    <p>{{ $employee->department->name }}</p>
                </div>

                <div class="mb-3">
                    <label class="form-label">Birth Date</label>
                    <p>{{ \Carbon\Carbon::parse($employee->birth_date)->format('d F Y') }}</p>
                </div>
        
                <div class="mb-3">
                    <label class="form-label">Hire Date</label>
                    <p>{{ \Carbon\Carbon::parse($employee->hire_date)->format('d F Y') }}</p>
                </div>
        
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <p>
                        @if ($employee->status == 'active')
                            <span class="text-success">{{ ucfirst($employee->status) }}</span>
                        @else
                            <span class="text-danger">{{ ucfirst($employee->status) }}</span>
                        @endif
                    </p>
                </div>
        
                <div class="mb-3">
                    <label class="form-label">Salary</label>
                    <p>{{ number_format($employee->salary) }}</p>
                </div>

            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to List</a>
                
            </div>
        </div>
    </section>
</div>

@endsection