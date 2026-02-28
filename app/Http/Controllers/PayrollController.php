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
    public function index()
    {
        if (session('role') == 'HR') {
            $payrolls = Payroll::all();
        } else {
            $payrolls = Payroll::where('employee_id', session('employee_id'))->get();
        }

        return view('payroll.index', compact('payrolls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();

        return view('payroll.create', compact('employees'));
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
        return view('payroll.show', compact('payroll'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payroll $payroll)
    {
        $employees = Employee::all();

        return view('payroll.edit', compact('payroll', 'employees'));
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
        return view('payroll.slip', compact('payroll'));
    }
}
