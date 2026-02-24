<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Presence;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {

        // Hitung total
    $employees = Employee::count();
    $departments = Department::count();
    $payrolls = Payroll::count();
    $presences = Presence::count();

    $tasks = Task::latest()->take(5)->get();

    // ==============================
    // CHART PRESENCE PER BULAN
    // ==============================
    $presenceChart = Presence::select(
            DB::raw("to_char(date, 'Mon') as month"),
            DB::raw("count(*) as total")
        )
        ->groupBy('month')
        ->orderBy(DB::raw("min(date)"))
        ->pluck('total', 'month');

    // ==============================
    // CHART PAYROLL PER BULAN
    // ==============================
    $payrollChart = Payroll::select(
            DB::raw("to_char(pay_date, 'Mon') as month"),
            DB::raw("sum(net_salary) as total")
        )
        ->groupBy('month')
        ->orderBy(DB::raw("min(pay_date)"))
        ->pluck('total', 'month');

    return view('dashboard.index', compact(
        'employees',
        'departments',
        'payrolls',
        'presences',
        'tasks',
        'presenceChart',
        'payrollChart'
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

