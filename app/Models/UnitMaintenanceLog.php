<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;

class UnitMaintenanceLog extends Model
{
    use HasFactory, BelongsToBranch;

    protected $fillable = [
        'unit_id',
        'service_date',
        'description',
        'cost',
        'mechanic_name',
        'current_mileage',
        'branch_id',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
