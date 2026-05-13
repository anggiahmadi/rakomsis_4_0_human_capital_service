<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryParameter extends Model
{
    protected $fillable = [
        'tenant_id',
        'location_id',
        'division_id',
        'location_name',
        'division_name',
        'allotment',
        'type',
        'name',
        'calculation_method',
        'amount',
        'percentage',
        'parameters',
    ];

    protected $casts = [
        'amount' => 'double',
        'percentage' => 'double',
        'parameters' => 'array', // Cast the JSON field to an array
    ];
}
