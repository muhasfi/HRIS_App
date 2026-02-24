<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();

        return view('employee.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        $roles = Role::all();

        return view('employee.create', compact('departments', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required | string | max:255',
            'email' => [
                'required',
                'email',
                Rule::unique(User::class, 'email'), // <-- di sini
            ],
            'phone_number' => 'required | string | max:15',
            'address' => 'nullable',
            'birth_date' => 'required | date',
            'hire_date' => 'required | date',
            'department_id' => 'required',
            'role_id' => 'required',
            'status' => 'required | string',
            'salary' => 'required | numeric | max:99999999.99',
            'password' => 'required|min:6|confirmed',
        ]);

        DB::transaction(function () use ($request) {

            // ✅ 1. Buat User dulu
            $user = User::create([
                'name' => $request->fullname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // ✅ 2. Buat Employee dan hubungkan ke user
            Employee::create([
                'user_id' => $user->id,
                'fullname' => $request->fullname,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'hire_date' => $request->hire_date,
                'department_id' => $request->department_id,
                'role_id' => $request->role_id,
                'status' => $request->status,
                'salary' => $request->salary,
            ]);
        });

        return redirect()->route('employees.index')->with('Success', 'Employee Created Successfully');
    }


    public function show($id)
    {
        $employee = Employee::findOrFail($id);

        return view('employee.show', compact('employee'));
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $departments = Department::all();
        $roles = Role::all();

        return view('employee.edit', compact('employee', 'departments', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required | string | max:255',
            'email' => 'required | email',
            'phone_number' => 'required | string | max:15',
            'address' => 'nullable',
            'birth_date' => 'required | date',
            'hire_date' => 'required | date',
            'department_id' => 'required',
            'role_id' => 'required',
            'status' => 'required | string',
            'salary' => 'required | numeric | max:99999999.99',
            'password' => 'nullable|min:6|confirmed',
        ]);

        DB::transaction(function () use ($request, $id) {

            $employee = Employee::with('user')->findOrFail($id);

            // ✅ Update employee
            $employee->update([
                'fullname' => $request->fullname,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'hire_date' => $request->hire_date,
                'department_id' => $request->department_id,
                'role_id' => $request->role_id,
                'status' => $request->status,
                'salary' => $request->salary,
            ]);

            // ✅ Update user
            $userData = [
                'name' => $request->fullname,
                'email' => $request->email,
            ];

            // Kalau password diisi, baru update
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $employee->user->update($userData);
        });

        return redirect()->route('employees.index')->with('Success', 'Employee Updated Successfully');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

         return redirect()->route('employees.index')->with('Success', 'Employee Deleted Successfully');
    }
}
