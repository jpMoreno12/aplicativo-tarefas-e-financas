<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'goal_id',
        'parent_id',
        'title',
        'due_date',
        'completed_at',
        'reward',
        'position',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the task.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the goal that owns the task.
     */
    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    /**
     * Get the parent task.
     */
    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    /**
     * Get the subtasks.
     */
    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    /**
     * Check if the task is completed.
     */
    public function isCompleted()
    {
        return !is_null($this->completed_at);
    }

    /**
     * Mark the task as completed.
     */
    public function markAsCompleted()
    {
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Mark the task as pending.
     */
    public function markAsPending()
    {
        $this->completed_at = null;
        $this->save();
    }
}
