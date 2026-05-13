<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'work_agreement_id',
        'employee_id',
        'employee_name',
        'basic_salary',
        'allowances',
        'deductions',
        'start_date',
        'end_date',
        'is_active',
        'notes',
        'payment_frequency',
    ];

    protected $casts = [
        'allowances' => 'array',
        'deductions' => 'array',
        'is_active' => 'boolean',
    ];

    public function workAgreement()
    {
        return $this->belongsTo(WorkAgreement::class);
    }
}
