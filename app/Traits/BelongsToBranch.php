<?php

namespace App\Traits;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToBranch
{
    protected static function bootBelongsToBranch()
    {
        static::creating(function ($model) {
            if (empty($model->branch_id) && Auth::check() && Auth::user()->branch_id) {
                $model->branch_id = Auth::user()->branch_id;
            }
        });

        static::addGlobalScope('branch', function (Builder $builder) {
            // Hindari infinite loop kawan. Kita hanya apply scope kalau user sudah ter-load kawan.
            if (auth()->hasUser()) {
                $user = auth()->user();
                if ($user->role !== 'super admin') {
                    $builder->where($builder->getQuery()->from . '.branch_id', $user->branch_id);
                }
            }
        });
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
