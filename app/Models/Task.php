<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function calendarEvent()
    {
        return $this->hasOne(CalendarEvent::class);
    }
}
