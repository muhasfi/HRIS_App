@extends('layouts.master')

@section('title', 'Edit Leave Type')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Leave Type</h3>
                <p class="text-subtitle text-muted">Manage department data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('leave-types.index') }}">Leave Type</a></li>
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
                
                <form action="{{ route('leave-types.update', $leaveType->id) }}" method="POST">
                   @csrf
                   @method('PUT')
        
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $leaveType->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="max_days" class="form-label">Max day</label>
                        <input type="number" class="form-control @error('max_days') is-invalid @enderror" name="max_days" value="{{ old('max_days', $leaveType->max_days) }}" required>
                        @error('max_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="is_paid" class="form-label">Is Paid</label>
                        <select class="form-select @error('is_paid') is-invalid @enderror" name="is_paid" required>
                            <option value="active" {{ $leaveType->is_paid == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $leaveType->is_paid == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_paid')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <button type="submit" class="btn btn-primary">Create Leave Types</button>
                    <a href="{{ route('leave-types.index') }}" class="btn btn-secondary">Back to Leave Types List</a>

                </form>
            </div>
        </div>
    </section>
</div>

@endsection