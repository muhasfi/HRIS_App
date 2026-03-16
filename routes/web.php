<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeScheduleController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('role:HR,Developer,Sales Person');
    Route::get('/dashboard/presence', [DashboardController::class, 'presence']);

    //employee
    Route::resource('employees', EmployeeController::class)->middleware('role:HR');
    
    //Department
    Route::resource('departments', DepartmentController::class)->middleware('role:HR');
    
    //Role
    Route::resource('roles', RoleController::class)->middleware('role:HR');
    
    //presence
    Route::resource('presences', PresenceController::class)->middleware('role:HR,Developer,Sales Person');
    
    //payroll
    Route::resource('payrolls', PayrollController::class)->middleware('role:HR,Developer,Sales Person');
    Route::get('/payrolls/{id}/slip', [PayrollController::class, 'slip'])->name('payrolls.slip');

    //Employee Schedules
    // Route::resource('employee-schedules', EmployeeScheduleController::class);
    Route::get('employee-schedules', [EmployeeScheduleController::class, 'index'])->name('employee-schedules.index');

    Route::get('employee-schedules/{employee}/edit', [EmployeeScheduleController::class, 'edit'])->name('employee-schedules.edit');

    Route::put('employee-schedules/{employee}', [EmployeeScheduleController::class, 'update'])->name('employee-schedules.update');
    
    //leave-requests
    Route::resource('leave-requests', LeaveRequestController::class)->middleware('role:HR,Developer,Sales Person');
    Route::resource('leave-balances', LeaveBalanceController::class)->middleware('role:HR,Developer,Sales Person');
    Route::resource('leave-types', LeaveTypeController::class)->middleware('role:HR,Developer,Sales Person');

    Route::get('/leave-requests/confirm/{id}',[LeaveRequestController::class,'confirm'])->name('leave-requests.confirm')->middleware('role:HR');
    Route::get('/leave-requests/reject/{id}', [LeaveRequestController::class,'reject'])->name('leave-requests.reject')->middleware('role:HR');
    
    //task
    Route::resource('tasks', TaskController::class)->middleware(['role:Developer,HR']);
    Route::get('tasks/done/{id}', [TaskController::class, 'done'])->name('tasks.done')->middleware(['role:HR,Developer,Sales Person']);
    Route::get('tasks/pending/{id}', [TaskController::class, 'pending'])->name('tasks.pending')->middleware(['role:HR,Developer,Sales Person']);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
