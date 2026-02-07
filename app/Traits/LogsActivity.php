<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        // Log create
        static::created(function ($model) {
            self::logActivity('create', $model);
        });

        // Log update
        static::updated(function ($model) {
            self::logActivity('update', $model);
        });

        // Log delete
        static::deleted(function ($model) {
            self::logActivity('delete', $model);
        });
    }

    protected static function logActivity($action, $model)
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();
        $modelType = get_class($model);
        $modelId = $model->id ?? null;

        $description = self::getActivityDescription($action, $model);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'division_id' => $user->division_id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    protected static function getActivityDescription($action, $model)
    {
        $modelName = class_basename(get_class($model));
        $actionText = [
            'create' => 'Membuat',
            'update' => 'Mengupdate',
            'delete' => 'Menghapus',
        ][$action] ?? ucfirst($action);

        $name = $model->name ?? $model->judul ?? $model->email ?? ('ID ' . ($model->id ?? ''));
        
        return "{$actionText} {$modelName}: {$name}";
    }

    public static function logCustomActivity($action, $description, $modelType = null, $modelId = null)
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'division_id' => $user->division_id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}

