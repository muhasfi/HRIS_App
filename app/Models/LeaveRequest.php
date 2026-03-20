<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'leave_requests';

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'status',
        'total_days',
        'reason',
        'approved_by',
        'approved_at',
        'attachment',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'approved_at'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function leaveBalance()
    {
        return $this->hasOne(LeaveBalance::class, 'employee_id', 'employee_id')
            ->where('leave_type_id', $this->leave_type_id)
            ->where('year', now()->year);
    }
}
