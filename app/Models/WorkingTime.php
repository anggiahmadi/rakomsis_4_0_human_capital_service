<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingTime extends Model
{
    protected $fillable = [
        'tenant_id',
        'location_id',
        'division_id',
        'employee_id',
        'location_name',
        'division_name',
        'employee_name',
        'type',
        'allotment',
        'office_hours',
        'shift_started_at',
        'shift_ended_at',
    ];

    protected $casts = [
        'office_hours' => 'array', // Cast office_hours to an array
        'shift_started_at' => 'datetime',
        'shift_ended_at' => 'datetime',
    ];
}
