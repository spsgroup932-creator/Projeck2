<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait and register model observers.
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $oldValues = array_intersect_key($model->getOriginal(), $model->getDirty());
            $newValues = $model->getDirty();
            
            // Only log if there are actual changes
            if (count($newValues) > 0) {
                $model->logActivity('updated', $oldValues, $newValues);
            }
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', $model->getAttributes(), null);
        });
    }

    /**
     * Store the activity log.
     */
    public function logActivity($action, $oldValues = null, $newValues = null)
    {
        // Don't log ActivityLog model changes to avoid infinite loop
        if ($this instanceof ActivityLog) return;

        $user = Auth::user();
        
        ActivityLog::create([
            'user_id' => $user ? $user->id : null,
            'branch_id' => $user ? $user->branch_id : ($this->branch_id ?? null),
            'action' => $action,
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $this->getActivityDescription($action),
        ]);
    }

    /**
     * Define custom description if needed.
     */
    protected function getActivityDescription($action)
    {
        $name = class_basename($this);
        return "{$name} was {$action}";
    }
}
