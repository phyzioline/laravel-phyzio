<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
        'route',
        'method'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime'
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    /**
     * Log an activity
     */
    public static function log($action, $model = null, $oldValues = null, $newValues = null, $description = null)
    {
        $user = auth()->user();
        $clinic = null;
        
        // Try to get clinic from user
        if ($user) {
            try {
                $clinic = \App\Models\Clinic::where('company_id', $user->id)->first();
            } catch (\Exception $e) {
                // Ignore
            }
        }

        return static::create([
            'clinic_id' => $clinic?->id,
            'user_id' => $user?->id,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'route' => request()->route()?->getName(),
            'method' => request()->method()
        ]);
    }

    /**
     * Get human-readable action description
     */
    public function getActionDescriptionAttribute()
    {
        $actions = [
            'created' => __('created'),
            'updated' => __('updated'),
            'deleted' => __('deleted'),
            'viewed' => __('viewed'),
            'exported' => __('exported'),
            'printed' => __('printed')
        ];

        $modelName = class_basename($this->model_type);
        
        return ($actions[$this->action] ?? $this->action) . ' ' . strtolower($modelName);
    }
}

