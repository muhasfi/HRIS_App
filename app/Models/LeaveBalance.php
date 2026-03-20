<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $table = 'leave_balances';
    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'year',
        'total_days',
        'used_days',
        'remaining_days'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    // ✅ Cek apakah sisa cuti cukup
    public function hasEnoughBalance(int $days): bool
    {
        return $this->remaining_days >= $days;
    }

    // ✅ Kurangi saldo cuti
    public function deductBalance(int $days): void
    {
        $this->increment('used_days', $days);
        $this->decrement('remaining_days', $days);
    }

    // ✅ Kembalikan saldo cuti (saat request dibatalkan/ditolak)
    public function restoreBalance(int $days): void
    {
        $this->decrement('used_days', $days);
        $this->increment('remaining_days', $days);
    }
}
