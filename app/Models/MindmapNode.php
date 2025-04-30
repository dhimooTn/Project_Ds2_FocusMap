<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MindmapNode extends Model
{
    protected $fillable = [
        'goal_id',
        'parent_id',
        'label',
        'content',
    ];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function parent()
    {
        return $this->belongsTo(MindmapNode::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MindmapNode::class, 'parent_id');
    }
}
