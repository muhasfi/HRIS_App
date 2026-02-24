<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (session('role') == 'HR') {
            $payrolls = Payroll::all();
        } else {
            $payrolls = Payroll::where('employee_id', session('employee_id'))->get();
        }

        return view('payroll.index', compact('payrolls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();

        return view('payroll.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'salary' => 'required | numeric',
            'bonuses' => 'required | numeric',
            'deductions' => 'required | numeric',
            'net_salary' => 'nullable | numeric',
            'pay_date' => 'required | date',
        ]);

        $netSalary = $request->input('salary') - $request->input('deductions') + $request->input('bonuses');

        $request->merge(['net_salary' => $netSalary]);

        Payroll::create($request->all());

        return redirect()->route('payrolls.index')->with('Success', 'Payroll created susccessfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payroll $payroll)
    {
        return view('payroll.show', compact('payroll'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payroll $payroll)
    {
        $employees = Employee::all();

        return view('payroll.edit', compact('payroll', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payroll $payroll)
    {
         $request->validate([
            'employee_id' => 'required',
            'salary' => 'required | numeric',
            'bonuses' => 'required | numeric',
            'deductions' => 'required | numeric',
            'net_salary' => 'nullable | numeric',
            'pay_date' => 'required | date',
        ]);

        $netSalary = $request->input('salary') - $request->input('deductions') + $request->input('bonuses');

        $request->merge(['net_salary' => $netSalary]);

        $payroll->update($request->all());

        return redirect()->route('payrolls.index')->with('Success', 'Payroll updated susccessfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()->route('payrolls.index')->with('Success', 'Payroll deleted susccessfully');
    }
}
