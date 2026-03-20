@extends('layouts.master')

@section('title', 'Employee')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>employee</h3>
                <p class="text-subtitle text-muted">Manage employee data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item">Employee</li>
                        <li class="breadcrumb-item active" aria-current="page">Index</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            <div class="card-body">
                <div class="d-flex">
                    {{-- @if (session('role') == 'HR') --}}
                        <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3 ms-auto">New Empployee</a>
                    {{-- @endif --}}
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Fullname</th>
                            <th>Email</th>
                            <th>Departement</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Salary</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee->fullname }}</td>
                                <td>{{ $employee->user->email }}</td>
                                <td>{{ $employee->department->name }}</td>
                                <td>{{ $employee->role->title }}</td>
                                <td>
                                    @if($employee->status == 'active')
                                        <span class="text-success">{{ ucfirst($employee->status) }}</span>
                                    @else
                                        <span class="text-warning">{{ ucfirst($employee->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ number_format($employee->salary) }}</td>
                                <td>
                                    <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('employees.edit', $employee->id) }}"class="btn btn-warning btn-sm">Edit</a>
                                    @if(Auth::user()->id !== $employee->user_id)
                                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda Yakin?')">Delete</button>
                                    </form>
                                    @endif
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@endsection