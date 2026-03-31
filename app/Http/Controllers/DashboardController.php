<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Presence;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
public function index()
{
    $user = Auth::user();
    $isHR = session('role') === 'HR';

    // ==============================
    // EMPLOYEE
    // ==============================
    if (!$isHR) {

        $employeeId = $user->employee->id;

        $leaveBalances = LeaveBalance::with('leaveType')
            ->where('employee_id', $employeeId)
            ->where('year', now()->year)
            ->get();

        $leaveRequests = LeaveRequest::with('leaveType')
            ->where('employee_id', $employeeId)
            ->latest()
            ->take(5)
            ->get();

        return view('employee.dashboard.index', compact(
            'leaveBalances',
            'leaveRequests'
        ));
    }

    // ==============================
    // HR / ADMIN
    // ==============================

    $employees   = Employee::count();
    $departments = Department::count();
    $payrolls    = Payroll::count();
    $presences   = Presence::count();

    $tasks = Task::latest()->take(5)->get();

    $presenceChart = Presence::select(
            DB::raw("to_char(date, 'Mon') as month"),
            DB::raw("count(*) as total")
        )
        ->groupBy('month')
        ->orderBy(DB::raw("min(date)"))
        ->pluck('total', 'month');

    $payrollChart = Payroll::select(
            DB::raw("to_char(pay_date, 'Mon') as month"),
            DB::raw("sum(net_salary) as total")
        )
        ->groupBy('month')
        ->orderBy(DB::raw("min(pay_date)"))
        ->pluck('total', 'month');

    $pendingLeaves = LeaveRequest::with(['employee', 'leaveType'])
        ->where('status', 'pending')
        ->latest()
        ->get();

    return view('admin.dashboard.index', compact(
        'employees',
        'departments',
        'payrolls',
        'presences',
        'tasks',
        'presenceChart',
        'payrollChart',
        'pendingLeaves',
        'isHR'
    ));
}

     public function presence()
    {
        $data = Presence::where('status', 'present')
                ->selectRaw('MONTH(date) as month, YEAR(date) as year, COUNT(*) as total_present')
                ->groupBy('year', 'month')
                ->orderBy('month', 'asc')
                ->get();

        $temp = [];
        $i = 0;

        foreach ($data as $item) {
            $temp[$i] = $item->total_present;
            $i++;
        }

        return response()->json($temp);
    }

    
}

