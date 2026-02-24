<?php

namespace App\Models;

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
        'check_in_lat',
        'check_in_long',
        'check_out_lat',
        'check_out_long',

    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
