<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveTypes = LeaveType::orderBy('name', 'asc')->get();

        return view('admin.leave-type.index', compact('leaveTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('leave-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'max_days' => 'required|numeric',
            'is_paid' => 'required|string'
        ]);

        LeaveType::create([
            'name' => $request->name,
            'max_days' => $request->max_days,
            'is_paid' => $request->is_paid
        ]);

        return redirect()->route('leave-types.index')->with("success", 'berhasil');
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
    public function edit(LeaveType $leaveType)
    {
        return view('leave-type.edit', compact('leaveType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'name'=> 'required|string',
            'max_days' => 'required',
            'is_paid' => 'required|string'
        ]);

        $leaveType->update([
            'name' => $request->name,
            'max_days' => $request->max_days,
            'is_paid' => $request->is_paid
        ]);

        return redirect()->route('leave-types.index')->with("success", 'berhasil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();

        return redirect()->route('leave-types.index')->with('success', 'berhasil');
    }
}
