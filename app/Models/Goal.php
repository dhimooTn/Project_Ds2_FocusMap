<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'visibility',
        'location',
        'lat',
        'lng',
        'status',
        'progress',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        'progress' => 'integer',
    ];

    // Constantes pour le statut
    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_ARCHIVED = 'archived';

    // Constantes pour la visibilité
    public const VISIBILITY_PRIVATE = 'private';
    public const VISIBILITY_PUBLIC = 'public';

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function journal(): HasMany
    {
        return $this->hasMany(Journal::class);
    }

    public function mindmapNodes(): HasMany
    {
        return $this->hasMany(MindmapNode::class);
    }

    // Méthodes utilitaires
    public function calculateProgress(): void
    {
        $totalTasks = $this->tasks()->count();
        
        if ($totalTasks === 0) {
            $this->update(['progress' => 0]);
            return;
        }

        $completedTasks = $this->tasks()->where('status', Task::STATUS_COMPLETED)->count();
        $progress = round(($completedTasks / $totalTasks) * 100);
        
        $this->update(['progress' => $progress]);
    }

    public function isPublic(): bool
    {
        return $this->visibility === self::VISIBILITY_PUBLIC;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', self::VISIBILITY_PUBLIC);
    }

    public function scopeWithLocation($query)
    {
        return $query->whereNotNull('lat')->whereNotNull('lng');
    }
}