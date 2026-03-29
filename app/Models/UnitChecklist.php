<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use App\Traits\BelongsToBranch;

class UnitChecklist extends Model
{
    use BelongsToBranch, LogsActivity;

    protected $fillable = [
        'job_order_id',
        'type',
        'check_date',
        'km_reading',
        'fuel_level',
        'items',
        'documents',
        'notes',
        'photos',
        'checker_id',
    ];

    protected $casts = [
        'check_date' => 'datetime',
        'items' => 'array',
        'documents' => 'array',
        'photos' => 'array',
    ];

    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checker_id');
    }
}
