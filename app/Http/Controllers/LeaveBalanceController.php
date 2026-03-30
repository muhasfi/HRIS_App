<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeaveBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveBalances = LeaveBalance::with(['employee','leaveType'])
        ->get()
        ->sortBy('employee.fullname');

        return view('admin.leave-balance.index', compact('leaveBalances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();
        $leaveTypes = LeaveType::all();

        return view('admin.leave-balance.create', compact('employees','leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', Rule::exists(Employee::class, 'id'),],
            'leave_type_id' => ['required', Rule::exists(LeaveType::class, 'id'),],
            'year' => 'required|integer',
            'total_days' => 'required|integer|min:0'
        ]);

        LeaveBalance::create([
            'employee_id' => $request->employee_id,
            'leave_type_id' => $request->leave_type_id,
            'year' => $request->year,
            'total_days' => $request->total_days,
            'used_days' => 0,
            'remaining_days' => $request->total_days
        ]);

        return redirect()->route('leave-balances.index')
            ->with('success','Leave balance created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveBalance $leaveBalance)
    {
        $employees = Employee::all();
        $leaveTypes = LeaveType::all();

        return view('admin.leave-balance.edit', compact('leaveBalance','employees','leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveBalance $leaveBalance)
    {
        $request->validate([
            'total_days' => 'required|integer|min:0',
            'used_days'  => [
                'required',
                'integer',
                'min:0',
                'lte:total_days',
            ],
        ]);

        $leaveBalance->total_days = $request->total_days;
        $leaveBalance->used_days  = $request->used_days;

        $leaveBalance->remaining_days = $leaveBalance->total_days - $leaveBalance->used_days;

        $leaveBalance->save();

        return redirect()->route('leave-balances.index')
            ->with('success', 'Leave balance updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        LeaveBalance::destroy($id);

        return redirect()->route('leave-balances.index')
            ->with('success','Leave balance deleted');
    }
}
