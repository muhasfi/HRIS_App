@extends('layouts.master')

@section('title', 'Payroll')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Payroll</h3>
                <p class="text-subtitle text-muted">Manage Payroll data</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item">Payroll</li>
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
                        <a href="{{ route('payrolls.create') }}" class="btn btn-primary mb-3 ms-auto">New Payroll</a>
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
                            <th>Salary</th>
                            <th>Deductions</th>
                            <th>Bonuses</th>
                            <th>Net Salary</th>
                            <th>Pay Date</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payrolls as $payroll)
                            <tr>
                                <td>{{ $payroll->employee->fullname }}</td>
                                <td>{{ number_format($payroll->salary) }}</td>
                                <td>{{ number_format($payroll->deductions) }}</td>
                                <td>{{ number_format($payroll->bonuses) }}</td>
                                <td>{{ number_format($payroll->net_salary) }}</td>
                                <td>{{ $payroll->pay_date }}</td>
                                <td>
                                    <a href="{{ route('payrolls.show', $payroll->id) }}"class="btn btn-warning btn-sm">Salary Slip</a>
                                    @if (session('role') == 'HR')
                                        <a href="{{ route('payrolls.edit', $payroll->id) }}"class="btn btn-info btn-sm">Edit</a>

                                        <form action="{{ route('payrolls.destroy', $payroll->id) }}" method="POST" style="display: inline">
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