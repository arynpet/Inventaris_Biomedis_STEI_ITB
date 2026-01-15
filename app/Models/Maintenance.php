<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maintenance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item_id',
        'type',
        'scheduled_date',
        'completed_date',
        'cost',
        'status',
        'technician_name',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'cost' => 'integer',
    ];

    /**
     * Get the item that this maintenance belongs to.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Scope: Get pending maintenances
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get in-progress maintenances
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope: Get completed maintenances
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Get overdue maintenances (scheduled date passed)
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'completed')
            ->where('scheduled_date', '<', now());
    }

    /**
     * Get formatted type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'calibration' => 'Kalibrasi',
            'repair' => 'Perbaikan',
            'cleaning' => 'Pembersihan',
            'inspection' => 'Inspeksi',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get formatted status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'in_progress' => 'Dalam Proses',
            'completed' => 'Selesai',
            default => ucfirst($this->status),
        };
    }
}
