<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    protected $fillable = [
        'goal_id',
        'title',
        'description',
        'status',
        'due_date',
        'priority',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Constantes pour les statuts
    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    // Constantes pour les priorités
    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';

    // Relations
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    public function calendarEvent(): HasOne
    {
        return $this->hasOne(CalendarEvent::class);
    }

    // Méthodes utilitaires
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isHighPriority(): bool
    {
        return $this->priority === self::PRIORITY_HIGH;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', self::PRIORITY_HIGH);
    }

    public function scopeDueSoon($query)
    {
        return $query->where('due_date', '<=', now()->addDays(3));
    }
}