@extends('layouts.master')

@section('title', 'Edit Task')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tasks</h3>
                <p class="text-subtitle text-muted">Manage tasks data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tasks</a></li>
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
                
                <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                   @csrf
                   @method('PUT')
        
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $task->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Employee</label>
                        <select class="form-select @error('assigned_to') is-invalid @enderror" name="assigned_to" required>
                            <option value="">Select an Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" 
                                    @if(old('assigned_to', $task->assigned_to) == $employee->id) selected @endif>
                                    {{ $employee->fullname }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control date @error('due_date') is-invalid @enderror" name="due_date" value="{{ old('due_date', $task->due_date) }}" required>
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                            <option value="done" @if(old('status', $task->status) == 'done') selected @endif>Done</option>
                            <option value="pending" @if(old('status', $task->status) == 'pending') selected @endif>Pending</option>
                            <option value="on progress" @if(old('status', $task->status) == 'ono progress') selected @endif>On Progress</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <button type="submit" class="btn btn-primary">Update Task</button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to Task List</a>

                </form>
            </div>
        </div>
    </section>
</div>

@endsection