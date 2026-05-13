<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    protected $fillable = [
        'tenant_id',
        'payroll_id',
        'employee_id',
        'employee_name',
        'amount',
        'type', // e.g. 'earning' or 'deduction'
        'description',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
