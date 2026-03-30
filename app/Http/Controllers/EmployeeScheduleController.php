<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSchedule;
use Illuminate\Http\Request;

class EmployeeScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with('schedules')->get();
        return view('admin.employee-schedule.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeSchedule $employeeSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $employee->load('schedules');
        return view('admin.employee-schedule.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        foreach($request->schedules as $day => $times) {
            EmployeeSchedule::updateOrCreate(
                ['employee_id' => $employee->id, 'day_of_week' => $day],
                ['start_time' => $times['start_time'], 'end_time' => $times['end_time']]
            );
        }

        return redirect()->route('employee-schedules.index')
            ->with('success', 'Schedule updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeSchedule $employeeSchedule)
    {
        //
    }
}
