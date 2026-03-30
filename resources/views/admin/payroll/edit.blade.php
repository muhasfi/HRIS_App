@extends('layouts.master')

@section('title', 'Edit Payroll')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Payroll</h3>
                <p class="text-subtitle text-muted">Manage Payroll data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Payroll</a></li>
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
                
                <form action="{{ route('payrolls.update', $payroll->id) }}" method="POST">
                   @csrf
                   @method('PUT')
        
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control">
                            <option value="" selected disabled>-- Select Employee --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" @if(old('employee_id', $payroll->employee_id) == $employee->id) selected @endif>{{ $employee->fullname }}</option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="salary" class="form-label">Salary</label>
                        <input type="number" class="form-control @error('salary') is-invalid @enderror" name="salary" value="{{ old('salary', $payroll->salary) }}" required>
                        @error('salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="deductions" class="form-label">Deduction</label>
                        <input type="number" class="form-control @error('deductions') is-invalid @enderror" name="deductions" value="{{ old('deductions', $payroll->deductions) }}" required>
                        @error('deductions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="bonuses" class="form-label">Bonuses</label>
                        <input type="number" class="form-control @error('bonuses') is-invalid @enderror" name="bonuses" value="{{ old('bonuses', $payroll->bonuses) }}" required>
                        @error('bonuses')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="net_salary" class="form-label">Net Salary</label>
                        <input type="number" class="form-control @error('net_salary') is-invalid @enderror" name="net_salary" value="{{ old('net_salary', $payroll->net_salary) }}" disabled>
                        @error('net_salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="pay_date" class="form-label">Pay Date</label>
                        <input type="text" class="form-control date @error('pay_date') is-invalid @enderror" name="pay_date" value="{{ old('pay_date', $payroll->pay_date) }}" required>
                        @error('pay_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <button type="submit" class="btn btn-primary">Update Payroll</button>
                    <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">Back to Payroll List</a>

                </form>
            </div>
        </div>
    </section>
</div>


<script>
    function calculateNetSalary() {
        let salary     = parseFloat(document.querySelector('input[name="salary"]').value)     || 0;
        let deductions = parseFloat(document.querySelector('input[name="deductions"]').value) || 0;
        let bonuses    = parseFloat(document.querySelector('input[name="bonuses"]').value)    || 0;

        let netSalary = salary - deductions + bonuses;

        document.querySelector('input[name="net_salary"]').value = netSalary;
    }

    // Jalankan setiap kali input berubah
    document.querySelector('input[name="salary"]').addEventListener('input', calculateNetSalary);
    document.querySelector('input[name="deductions"]').addEventListener('input', calculateNetSalary);
    document.querySelector('input[name="bonuses"]').addEventListener('input', calculateNetSalary);
</script>

@endsection