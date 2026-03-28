<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToBranch;

class Unit extends Model
{
    use BelongsToBranch;

    protected $guarded = [];

    public function maintenanceLogs()
    {
        return $this->hasMany(UnitMaintenanceLog::class);
    }

    public function jobOrders()
    {
        return $this->hasMany(JobOrder::class);
    }
}
