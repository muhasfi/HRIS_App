@extends('layouts.master')

@section('title', 'Edit Employee')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Employee</h3>
                <p class="text-subtitle text-muted">Manage Employee data</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employee</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            <div class="card-body">

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                   @csrf
                   @method('PUT')
        
                    <div class="mb-3">
                        <label for="" class="form-label">fullname</label>
                        <input type="text" class="form-control @error('fullname') is-invalid @enderror" name="fullname" value="{{ old('fullname', $employee->fullname) }}" required>
                        @error('fullname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="" class="form-label">email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $employee->user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number', $employee->phone_number) }}" required>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                     <div class="mb-3">
                        <label for="" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" name="address">{{ old('address', $employee->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Birth Date</label>
                        <input type="date" class="form-control date @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date', $employee->birth_date) }}" required>
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Hire Date</label>
                        <input type="date" class="form-control date @error('hire_date') is-invalid @enderror" name="hire_date" value="{{ old('hire_date', $employee->hire_date) }}" required>
                        @error('hire_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Departement</label>
                        <select class="form-select @error('department_id') is-invalid @enderror" name="department_id" required>
                            <option value="">Select an Departement</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" 
                                    @if(old('department_id', $employee->department_id) == $department->id) selected @endif>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role</label>
                        <select class="form-select @error('role_id') is-invalid @enderror" name="role_id" required>
                            <option value="">Select an Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" 
                                    @if(old('role_id', $employee->role_id) == $role->id) selected @endif>
                                    {{ $role->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                            <option value="active" {{ $employee->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $employee->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="salary" class="form-label">Salary</label>
                        <input type="number" class="form-control @error('salary') is-invalid @enderror" name="salary" value="{{ old('salary', $employee->salary) }}" required>
                        @error('salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            name="password"
                            placeholder="Kosongkan jika tidak ingin mengubah password"
                            autocomplete="new-password"
                        >
                        
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input 
                            type="password" 
                            class="form-control" 
                            name="password_confirmation"
                            placeholder="Ulangi password baru"
                            autocomplete="new-password"
                        >
                    </div>
        
        
                    <button type="submit" class="btn btn-primary">Update Employee</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to Task List</a>

                </form>
            </div>
        </div>
    </section>
</div>

@endsection