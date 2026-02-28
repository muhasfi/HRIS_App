<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master.employee_schedules';
    protected $fillable = [
        'employee_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public static function days()
    {
        return [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];
    }
}
