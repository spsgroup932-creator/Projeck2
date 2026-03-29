<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\BelongsToBranch;
use App\Traits\LogsActivity;

class Customer extends Model
{
    use HasFactory, BelongsToBranch, LogsActivity;

    protected $guarded = [];
    
    public function jobOrders()
    {
        return $this->hasMany(JobOrder::class);
    }
}
