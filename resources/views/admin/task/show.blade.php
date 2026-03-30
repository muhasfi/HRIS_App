@extends('layouts.master')

@section('title', 'Show Task')

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
                    <label class="form-label">Title</label>
                    <p>{{ $task->title }}</p>
                </div>
        
                <div class="mb-3">
                    <label class="form-label">Assigned To</label>
                    <p>{{ $task->employee->fullname }}</p>
                </div>
        
                <div class="mb-3">
                    <label class="form-label">Due Date</label>
                    <p>{{ \Carbon\Carbon::parse($task->due_date)->format('d F Y') }}</p>
                </div>
        
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <p>
                        @if ($task->status == 'done')
                            <span class="text-success">{{ ucfirst($task->status) }}</span>
                        @else
                            <span class="text-warning">{{ ucfirst($task->status) }}</span>
                        @endif
                    </p>
                </div>
        
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <p>{{ $task->description }}</p>
                </div>

            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to List</a>
                
            </div>
        </div>
    </section>
</div>

@endsection