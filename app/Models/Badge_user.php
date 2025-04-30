<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge_User extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'badge_user';

    // The attributes that are mass assignable.
    protected $fillable = [
        'badge_id',
        'user_id',
        'awarded_at',
    ];

    // The relationships this model has (assuming you have Badge and User models).
    
    /**
     * Get the badge associated with the badge_user.
     */
    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    /**
     * Get the user associated with the badge_user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
