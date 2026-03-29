<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\BelongsToBranch;

class JobOrderPayment extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'job_order_id',
        'branch_id',
        'amount',
        'method',
        'payment_date',
        'user_id'
    ];

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
