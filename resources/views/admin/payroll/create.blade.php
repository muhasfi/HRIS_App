@extends('layouts.master')

@section('title', 'Create Payroll')

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
                
                <form action="{{ route('payrolls.store') }}" method="POST">
                   @csrf
        
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control">
                            <option value="" selected disabled>-- Select Employee --</option>
                            @foreach($employees as $employee)
                                <option 
                                    value="{{ $employee->id }}"
                                    data-salary="{{ $employee->salary }}"
                                >
                                    {{ $employee->fullname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
        
                    <div class="mb-3">
                        <label for="salary" class="form-label">Salary</label>
                        <input type="number" class="form-control @error('salary') is-invalid @enderror" id="salary" readonly>
                        @error('salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="bonuses" class="form-label">Bonuses</label>
                        <input type="number" class="form-control @error('bonuses') is-invalid @enderror" name="bonuses" value="{{ old('bonuses') }}" required>
                        @error('bonuses')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label class="form-label">Estimated Net Salary</label>
                        <input type="number" id="net_salary_preview" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="pay_date" class="form-label">Pay Date</label>
                        <input type="text" class="form-control date @error('pay_date') is-invalid @enderror" name="pay_date" value="{{ old('pay_date') }}" required>
                        @error('pay_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <button type="submit" class="btn btn-primary">Create Payroll</button>
                    <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">Back to Payroll List</a>

                </form>
            </div>
        </div>
    </section>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    const employeeSelect = document.getElementById('employee_id');
    const salaryInput    = document.getElementById('salary');
    const bonusInput     = document.querySelector('input[name="bonuses"]');
    const netPreview     = document.getElementById('net_salary_preview');

    function calculatePreview() {
        let salary  = parseFloat(salaryInput.value) || 0;
        let bonuses = parseFloat(bonusInput.value)  || 0;

        netPreview.value = salary + bonuses;
    }

    employeeSelect.addEventListener('change', function () {
        let selectedOption = this.options[this.selectedIndex];
        let salary = selectedOption.getAttribute('data-salary') || 0;

        salaryInput.value = salary;
        calculatePreview();
    });

    bonusInput.addEventListener('input', calculatePreview);

});
</script>

@endsection