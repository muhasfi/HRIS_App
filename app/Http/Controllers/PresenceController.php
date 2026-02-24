<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (session('role') == 'HR') {

            $presences = Presence::all();
            return view('presence.index', compact('presences'));

        } else {

            $employeeId = session('employee_id');
            $today = Carbon::today()->toDateString();
            // $today = Carbon::today()->addDay()->toDateString();

            $presences = Presence::where('employee_id', $employeeId)->get();

            $todayPresence = Presence::where('employee_id', $employeeId)
                ->where('date', $today)
                ->first();

            return view('presence.index', compact('presences', 'todayPresence'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();
        return view('presence.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (session('role') == 'HR') {

            $request->validate([
                'employee_id' => 'required',
                'check_in' => 'required',
                'check_out' => 'required',
                'date' => 'required|date',
                'status' => 'required|string',
            ]);

            Presence::create($request->all());

            return redirect()->route('presences.index')
                ->with('success', 'Presence Created');

        } else {

            $employeeId = session('employee_id');
            $today = Carbon::today();
            // $today = Carbon::today()->addDay();

            $presence = Presence::where('employee_id', $employeeId)
                ->whereDate('date', $today)
                ->first();
            // $presence = Presence::where('employee_id', $employeeId)
            //     ->where('date', $today->toDateString())
            //     ->first();

            // =======================
            // CHECK IN
            // =======================
            if (!$presence) {

                Presence::create([
                    'employee_id' => $employeeId,
                    'date' => $today,
                    // 'date' => $today->toDateString(),
                    'check_in' => Carbon::now(),
                    'status' => 'present',
                    'check_in_lat' => $request->check_in_lat,
                    'check_in_long' => $request->check_in_long,
                ]);

                return redirect()->route('presences.index')
                    ->with('success', 'Check-in berhasil');
            }

            // =======================
            // CHECK OUT
            // =======================
            if (!$presence->check_out) {

                $presence->update([
                    'check_out' => Carbon::now(),
                    'check_out_lat' => $request->check_in_lat,   // ambil dari input yang sama
                    'check_out_long' => $request->check_in_long,
                ]);

                return redirect()->route('presences.index')
                    ->with('success', 'Check-out berhasil');
            }

            // =======================
            // SUDAH SELESAI
            // =======================
            return redirect()->route('presences.index')
                ->with('error', 'Kamu sudah check-in dan check-out hari ini');
        }
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
    public function edit(Presence $presence)
    {
        $employees = Employee::all();

        return view('presence.edit', compact('presence', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Presence $presence)
    {
        $request->validate([
            'employee_id' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
            'date' => 'required | date',
            'status' => 'required | string',
        ]);

        $presence->update($request->all());

        return redirect()->route('presences.index')->with('Success', 'Presence Update Recorded Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presence $presence)
    {
        $presence->delete();

        return redirect()->route('presences.index')->with('Success', 'Presence Deleted Recorded Successfully');
    }
}
