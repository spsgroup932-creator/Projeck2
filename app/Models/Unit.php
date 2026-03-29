<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\BelongsToBranch;

use App\Traits\LogsActivity;

class Unit extends Model
{
    use HasFactory, BelongsToBranch, LogsActivity;

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
