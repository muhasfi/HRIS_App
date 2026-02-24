<?php

namespace App\Http\Middleware;

use App\Models\Employee;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = auth()->user();

        // Pastikan user punya employee
        if (!$user->employee) {
            abort(403, 'User has no employee profile.');
        }

        $employee = $user->employee;

        // Ambil role name (sesuaikan dengan kolom kamu: name atau title)
        $roleName = $employee->role->title;

        // Simpan ke session (opsional)
        $request->session()->put('role', $roleName);
        $request->session()->put('employee_id', $employee->id);

        if (!in_array($roleName, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
