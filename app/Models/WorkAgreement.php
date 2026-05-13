<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkAgreement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'location_id',
        'division_id',
        'employee_id',
        'code',
        'location_name',
        'division_name',
        'employee_name',
        'position',
        'bank_account_number',
        'bank_name',
        'start_date',
        'end_date',
        'yearly_leave_allowance',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'yearly_leave_allowance' => 'integer'
    ];
}
