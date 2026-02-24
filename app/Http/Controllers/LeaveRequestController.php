<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveRequests = LeaveRequest::all();

        return view('leave-request.index', compact('leaveRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();

        return view('leave-request.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'leave_type' => 'required | string',
            'start_date' => 'required | date',
            'end_date' => 'required | date'
        ]);

        $request->merge([
            'status' => 'pending'
        ]);

        LeaveRequest::create($request->all());

        return redirect()->route('leave-requests.index')->with('Success', 'Leave Request Create Successfully');
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
    public function edit(LeaveRequest $leaveRequest)
    {
        $employees = Employee::all();

        return view('leave-request.edit', compact('leaveRequest', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'employee_id' => 'required',
            'leave_type' => 'required | string',
            'start_date' => 'required | date',
            'end_date' => 'required | date'
        ]);

        $leaveRequest->update($request->all());

        return redirect()->route('leave-requests.index')->with('Success', 'Leave Request Update Successfully');
    
    }

    public function confirm(int $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        $leaveRequest->update([
            'status' => 'confirm'
        ]);

        return redirect()->route('leave-requests.index')->with('Success', 'Leave Request confirmed Successfully');
    }
    public function reject(int $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        $leaveRequest->update([
            'status' => 'reject'
        ]);

        return redirect()->route('leave-requests.index')->with('Success', 'Leave Request rejected Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')->with('Success', 'Leave Request Deleted Successfully');
    }
}
