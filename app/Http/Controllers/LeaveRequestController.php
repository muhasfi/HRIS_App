<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveRequests = LeaveRequest::orderBy('employee_id', 'asc')->get();

        return view('leave-request.index', compact('leaveRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::select('id','fullname')->get();
        $leaveTypes = LeaveType::all();

        return view('leave-request.create', compact('employees', 'leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => [
                'required',
                Rule::exists(LeaveType::class, 'id'),
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // max 2MB
        ]);

        if (Auth::user()->role == 'hr') {
            $request->validate([
                'employee_id' => ['required', Rule::exists(Employee::class, 'id'),]
            ]);

            $employeeId = $request->employee_id;
        } else {
            $employeeId = Auth::user()->employee->id;
        }

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        $totalDays = $start->diffInDays($end) + 1;

        // Handle upload file attachment
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            
            // Format: attachment_<unique>_<tanggal>.<ext>
            // Contoh: attachment_a1b2c3d4_2026-03-17.pdf
            $fileName = 'attachment_emp' . $employeeId . '_' . uniqid() . '_' . now()->format('Y-m-d') . '.' . $extension;
            
            $attachmentPath = $file->storeAs('leave-attachments', $fileName, 'public');
        }

        LeaveRequest::create([
            'employee_id' => $employeeId,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'status' => 'pending',
            'reason' => $request->reason,
            'attachment'    => $attachmentPath,
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        return view('leave-request.show', compact('leaveRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveRequest $leaveRequest)
    {
        $employees = Employee::all();
        $leaveTypes = LeaveType::all();

        return view('leave-request.edit', compact('leaveTypes', 'employees', 'leaveRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'employee_id' => 'required',
            'leave_type_id' => [
                'required',
                Rule::exists(LeaveType::class, 'id'),
            ],
            'start_date' => 'required | date',
            'end_date' => 'required | date'
        ]);

        if (Auth::user()->role == 'hr') {
            $request->validate([
                'employee_id' => ['required', Rule::exists(Employee::class, 'id')]
            ]);

            $employeeId = $request->employee_id;
        } else {
            $employeeId = Auth::user()->employee->id;
        }

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        $totalDays = $start->diffInDays($end) + 1;

        // ✅ Handle upload attachment baru (jika ada)
        $attachmentPath = $leaveRequest->attachment; // default: tetap pakai file lama
        if ($request->hasFile('attachment')) {
            // Hapus file lama jika ada
            if ($leaveRequest->attachment && Storage::disk('public')->exists($leaveRequest->attachment)) {
                Storage::disk('public')->delete($leaveRequest->attachment);
            }

            $file      = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $fileName  = 'attachment_emp' . $employeeId . '_' . uniqid() . '_' . now()->format('Y-m-d') . '.' . $extension;

            $attachmentPath = $file->storeAs('leave-attachments', $fileName, 'public');
        }

        $leaveRequest->update([
            'employee_id' => $request->employee_id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'status' => 'pending',
            'reason' => $request->reason,
            'attachment'    => $attachmentPath,
        ]);

        return redirect()->route('leave-requests.index')->with('Success', 'Leave Request Update Successfully');
    
    }

    public function confirm(int $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->route('leave-requests.index')
                ->with('error', 'Leave request has already been processed');
        }

        $year = Carbon::parse($leaveRequest->start_date)->year;

        // ✅ Ambil leave balance dari tabel leave_balances
        $leaveBalance = LeaveBalance::where('employee_id', $leaveRequest->employee_id)
            ->where('leave_type_id', $leaveRequest->leave_type_id)
            ->where('year', $year)
            ->first();

        // ✅ Cek apakah balance ada
        if (!$leaveBalance) {
            return redirect()->route('leave-requests.index')
                ->with('error', 'Leave balance not found for this employee');
        }

        // ✅ Cek sisa saldo cukup atau tidak (fleksibel sesuai data di DB)
        if (!$leaveBalance->hasEnoughBalance($leaveRequest->total_days)) {
            return redirect()->route('leave-requests.index')
                ->with('error', "Insufficient leave balance. Remaining: {$leaveBalance->remaining_days} days");
        }

        // ✅ Gunakan DB transaction agar data konsisten
        DB::transaction(function () use ($leaveRequest, $leaveBalance) {
            // Kurangi saldo cuti
            $leaveBalance->deductBalance($leaveRequest->total_days);

            // Update status request
            $leaveRequest->update([
                'status'      => 'confirm',
                'approved_by' => Auth::user()->id,
                'approved_at' => now(),
            ]);
        });

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request approved successfully');
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
