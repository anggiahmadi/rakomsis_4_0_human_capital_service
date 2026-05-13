<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresenceRecap extends Model
{
    protected $fillable = [
        'tenant_id',
        'location_id',
        'division_id',
        'code',
        'location_name',
        'division_name',
        'start_date',
        'end_date',
    ];

    public function presenceRecapDetails()
    {
        return $this->hasMany(PresenceRecapDetail::class);
    }
}
