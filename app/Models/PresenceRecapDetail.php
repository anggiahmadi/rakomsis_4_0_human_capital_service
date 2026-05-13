<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresenceRecapDetail extends Model
{
    protected $fillable = [
        'tenant_id',
        'presence_recap_id',
        'employee_id',
        'employee_name',
        'total_working_days',
        'total_leave_days',
        'total_absent_days',
    ];

    public function presenceRecap()
    {
        return $this->belongsTo(PresenceRecap::class);
    }
}
