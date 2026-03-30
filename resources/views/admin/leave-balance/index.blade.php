@extends('layouts.master')

@section('title', 'Leave Balance')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Leave Balance</h3>
                <p class="text-subtitle text-muted">Manage Leave Balance data</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item">Leave Balance</li>
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
                    @if (session('role') == 'HR')
                        <a href="{{ route('leave-balances.create') }}" class="btn btn-primary mb-3 ms-auto">New Leave Balance</a>
                    @endif
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Year</th>
                            <th>Total Days</th>
                            <th>Used Days</th>
                            <th>Remaining Days</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveBalances as $leaveBalance)
                            <tr>
                                <td>{{ $leaveBalance->employee->fullname }}</td>
                                <td>{{ $leaveBalance->leaveType->name }}</td>
                                <td>{{ $leaveBalance->year }}</td>
                                <td>{{ $leaveBalance->total_days }}</td>
                                <td>{{ $leaveBalance->used_days }}</td>
                                <td>{{ $leaveBalance->remaining_days }}</td>
                                <td>
                                    <a href="{{ route('leave-balances.edit', $leaveBalance->id) }}"class="btn btn-info btn-sm">Edit</a>
                                    <form action="{{ route('leave-balances.destroy', $leaveBalance->id) }}" method="POST" style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda Yakin?')">Delete</button>
                                    </form>
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