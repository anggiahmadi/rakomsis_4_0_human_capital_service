<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'work_agreement_id',
        'employee_id',
        'employee_name',
        'year',
        'start_date',
        'end_date',
        'duration',
        'type',
        'reason',
        'status',
    ];

    public function workAgreement()
    {
        return $this->belongsTo(WorkAgreement::class);
    }
}
