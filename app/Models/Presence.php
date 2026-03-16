<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presence extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transactions.presences';

    protected $fillable = [
        'employee_id',
        'check_in',
        'check_out',
        'date',
        'status',
        'late_minutes',
        'check_in_lat',
        'check_in_long',
        'check_out_lat',
        'check_out_long',
        'photo_check_in',
        'photo_check_out',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public static function determineStatus($checkInTime)
    {
        $workStart = Carbon::today()
            ->setTimeFromTimeString(config('app.work_start'));

        $lateLimit = $workStart->copy()
            ->addMinutes(config('app.late_tolerance'));

        return $checkInTime->gt($lateLimit) ? 'late' : 'present';
    }
}
