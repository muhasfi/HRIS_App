<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'payrolls';

    protected $fillable = [
        'employee_id',
        'salary',
        'bonuses',
        'deductions',
        'net_salary',
        'pay_date',
        'total_alpha',
        'total_late_minutes',
        'deduction_alpha',
        'deduction_late'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
