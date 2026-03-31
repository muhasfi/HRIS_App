<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSchedule;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $isHR = session('role') === 'HR';

        if ($isHR) {

            $presences = Presence::latest()->get();

            return view('admin.presence.index', compact('presences'));

        } else {

            $employeeId = session('employee_id');
            $today = now()->toDateString();

            $presences = Presence::where('employee_id', $employeeId)
                ->latest()
                ->get();

            $todayPresence = Presence::where('employee_id', $employeeId)
                ->where('date', $today)
                ->first();

            $dayOfWeek = now()->dayOfWeek;

            $hasSchedule = EmployeeSchedule::where('employee_id', $employeeId)
                ->where('day_of_week', $dayOfWeek)
                ->exists();

            return view(
                'employee.presence.index',
                compact('presences', 'todayPresence', 'hasSchedule')
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();

        if (session('role') === 'HR') {
            return view('admin.presence.create', compact('employees'));
        }

        return view('employee.presence.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd(session()->all());
        // =========================================
        // HR INPUT MANUAL
        // =========================================
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
        }

        // =========================================
        // EMPLOYEE CHECK-IN / CHECK-OUT
        // =========================================

        $employeeId = session('employee_id');
        $today = Carbon::today();
        $now = Carbon::now();
        $dayOfWeek = $now->dayOfWeek; // 0-6

        // Cari presence hari ini
        $presence = Presence::where('employee_id', $employeeId)
            ->whereDate('date', $today)
            ->first();

        // Cari schedule hari ini
        $schedule = EmployeeSchedule::where('employee_id', $employeeId)
            ->where('day_of_week', $dayOfWeek)
            ->first();
            

        if (!$schedule) {
            return redirect()->route('presences.index')
                ->with('error', 'Tidak ada jadwal shift hari ini');
        }

        // =======================
        // HITUNG STATUS
        // =======================
       
        $startTime = Carbon::parse($schedule->start_time);

        $status = $now->greaterThan($startTime) ? 'late' : 'present';

        // =======================
        // CHECK IN
        // =======================
        if (!$presence) {

            $checkIn = $now;
            $workStart = Carbon::parse($schedule->start_time);

            $lateMinutes = 0;
            $status = 'present';

            if ($checkIn->gt($workStart)) {
                $lateMinutes = (int) $workStart->diffInMinutes($checkIn);
                $status = 'late';
            }

            $request->validate([
                'check_in_lat'   => 'required',
                'check_in_long'  => 'required',
                'photo_check_in' => 'required|starts_with:data:image',
            ]);

            $photo = null;

            if ($request->photo_check_in) {
                $imageData = base64_decode(str_replace('data:image/jpeg;base64,', '', $request->photo_check_in));
                $filename = 'presences/checkin_' . uniqid() . '.jpg';
                Storage::disk('public')->put($filename, $imageData);

                $photo = $filename;
            }

            Presence::create([
                'employee_id' => $employeeId,
                'date' => $today->toDateString(),
                'check_in' => $checkIn,
                'status' => $status,
                'late_minutes' => $lateMinutes,
                'check_in_lat' => $request->check_in_lat,
                'check_in_long' => $request->check_in_long,
                'photo_check_in' => $photo
            ]);
            
            return redirect()->route('presences.index')->with('success', 'Check-in berhasil');
        }

        // =======================
        // CHECK OUT
        // =======================
        if (!$presence->check_out) {

            $request->validate([
                'check_in_lat'   => 'required',
                'check_in_long'  => 'required',
                'photo_check_in' => 'required|starts_with:data:image',
            ]);

            $photo = null;

            if ($request->photo_check_in) {
                $imageData = base64_decode(str_replace('data:image/jpeg;base64,', '', $request->photo_check_in));
                $filename = 'presences/checkout_' . uniqid() . '.jpg';
                Storage::disk('public')->put($filename, $imageData);

                $photo = $filename;
            }

            $presence->update([
                'check_out' => $now,
                'check_out_lat' => $request->check_in_lat,
                'check_out_long' => $request->check_in_long,
                'photo_check_out' => $photo
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

    /**
     * Display the specified resource.
     */
    public function show(Presence $presence)
    {
        return view('admin.presence.show', compact('presence'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presence $presence)
    {
        $employees = Employee::all();

        return view('admin.presence.edit', compact('presence', 'employees'));
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
