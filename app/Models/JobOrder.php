<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToBranch;

use App\Traits\LogsActivity;

class JobOrder extends Model
{
    use HasFactory, BelongsToBranch, LogsActivity;

    protected $fillable = [
        'spj_number',
        'customer_id',
        'unit_id',
        'driver_id',
        'user_id',
        'sales_market',
        'destination',
        'departure_date',
        'departure_time',
        'return_date',
        'duration',
        'description',
        'payment_status',
        'price_per_day',
        'days_count',
        'total_price',
        'closing_number',
        'closing_date',
        'is_closed',
        'branch_id',
        'digital_signature',
    ];

    public function payments()
    {
        return $this->hasMany(JobOrderPayment::class);
    }

    public function claims()
    {
        return $this->hasMany(JobOrderClaim::class);
    }

    public function getPaidAmountAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getRemainingBalanceAttribute()
    {
        $balance = ($this->total_price + $this->claims()->sum('amount')) - $this->paid_amount;
        return max(0, $balance);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checklists()
    {
        return $this->hasMany(UnitChecklist::class);
    }
}
