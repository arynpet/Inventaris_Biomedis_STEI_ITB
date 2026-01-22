<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    //

    protected $fillable = ['user_id', 'action', 'model', 'model_id', 'description', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to create a log entry easily.
     */
    public static function log($model, $modelId, $action, $description)
    {
        return self::create([
            'user_id' => auth()->id(),
            'model' => $model,
            'model_id' => $modelId,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}
