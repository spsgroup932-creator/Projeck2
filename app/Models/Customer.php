<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToBranch;

class Customer extends Model
{
    use BelongsToBranch;

    protected $guarded = [];
    
    public function jobOrders()
    {
        return $this->hasMany(JobOrder::class);
    }
}
