<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $isHR = session('role') === 'HR';
    
        // ── HR: tidak diubah, langsung ke view HR ─────────────
        if ($isHR) {
            $payrolls = Payroll::all();
            return view('admin.payroll.index', compact('payrolls'));
        }
    
        // ── EMPLOYEE: filter + pagination ─────────────────────
        $query = Payroll::with('employee')
            ->where('employee_id', session('employee_id'));
    
        // Filter bulan (format: YYYY-MM)
        if ($request->filled('month')) {
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('pay_date', $year)
                ->whereMonth('pay_date', $month);
        }
    
        // Pagination 5 per halaman, query string ikut
        $payrolls = $query->latest('pay_date')->paginate(5)->withQueryString();
    
        // Statistik metric (dari semua data milik employee, bukan yang terfilter)
        $base = Payroll::where('employee_id', session('employee_id'));
    
        $payrollStats = [
            'total'       => 'Rp ' . number_format(
                                (clone $base)->sum('net_salary') / 1_000_000, 1
                            ) . 'M',
            'total_count' => (clone $base)->count(),
            'avg_net'     => (clone $base)->avg('net_salary') ?? 0,
        ];
    
        // Dropdown bulan dari data milik employee (PostgreSQL: TO_CHAR)
        $months = Payroll::where('employee_id', session('employee_id'))
            ->selectRaw("TO_CHAR(pay_date, 'YYYY-MM') as value,
                        TO_CHAR(pay_date, 'Month YYYY') as label")
            ->distinct()
            ->orderByDesc('value')
            ->get()
            ->map(fn ($row) => ['value' => $row->value, 'label' => trim($row->label)])
            ->toArray();
    
        $currentMonth = \Carbon\Carbon::now()->translatedFormat('F Y');
    
        return view('employee.payroll.index', compact(
            'payrolls',
            'payrollStats',
            'currentMonth',
            'months'
        ));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();

        return view('admin.payroll.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', Rule::exists(Employee::class, 'id')],
            'bonuses' => 'nullable|numeric|min:0',
            'pay_date' => 'required|date',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        $basicSalary = $employee->salary;
        $bonuses = $request->bonuses ?? 0;

        // ===============================
        // Ambil bulan & tahun payroll
        // ===============================
        $payDate = Carbon::parse($request->pay_date);
        $month = $payDate->month;
        $year  = $payDate->year;

        $exists = Payroll::where('employee_id', $employee->id)
            ->whereMonth('pay_date', $month)
            ->whereYear('pay_date', $year)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Payroll bulan ini sudah dibuat');
        }

        // ===============================
        // Ambil data presence bulan tsb
        // ===============================
        $presences = Presence::where('employee_id', $employee->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        // ===============================
        // Hitung alpha & late
        // ===============================
        $totalAlpha = $presences->where('status', 'alpha')->count();
        $totalLateMinutes = (int) $presences->sum('late_minutes');

        // ===============================
        // Hitung potongan
        // ===============================

        $workingDays = 22; // bisa nanti dibuat configurable
        $dailySalary = $basicSalary / $workingDays;

        // Potongan alpha (1 hari penuh)
        $deductionAlpha = $totalAlpha * $dailySalary;

        // Potongan late (proporsional jam kerja)
        $workMinutesPerDay = 8 * 60;
        $deductionLate = ($totalLateMinutes / $workMinutesPerDay) * $dailySalary;

        $totalDeductions = $deductionAlpha + $deductionLate;

        // ===============================
        // Net salary
        // ===============================
        $netSalary = $basicSalary - $totalDeductions + $bonuses;

        // ===============================
        // Simpan payroll (SNAPSHOT)
        // ===============================
        Payroll::create([
            'employee_id' => $employee->id,
            'salary' => $basicSalary,
            'bonuses' => $bonuses,
            'deductions' => $totalDeductions,
            'net_salary' => $netSalary,
            'pay_date' => $payDate,
            'status' => 'draft',

            // snapshot attendance
            'total_alpha' => $totalAlpha,
            'total_late_minutes' => $totalLateMinutes,
            'deduction_alpha' => $deductionAlpha,
            'deduction_late' => $deductionLate,
        ]);

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll generated successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payroll $payroll)
    {
        if (session('role') == 'HR') {
            return view('admin.payroll.show', compact('payroll'));
        }
        return redirect()->back()->with('error', 'Akses ditolak');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payroll $payroll)
    {
        $employees = Employee::all();

        return view('admin.payroll.edit', compact('payroll', 'employees'));
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

    public function slip($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        return view('admin.payroll.slip', compact('payroll'));
    }
}
