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
        'priority',
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

    /**
     * Get priority levels array.
     */
    public static function getPriorityLevels()
    {
        return [
            'muito_facil' => 'Muito Fácil',
            'facil' => 'Fácil',
            'medio' => 'Médio',
            'dificil' => 'Difícil',
            'muito_dificil' => 'Muito Difícil',
        ];
    }

    /**
     * Get priority label.
     */
    public function getPriorityLabel()
    {
        $levels = self::getPriorityLevels();
        return $levels[$this->priority] ?? 'Médio';
    }

    /**
     * Get priority badge class.
     */
    public function getPriorityBadgeClass()
    {
        return match($this->priority) {
            'muito_facil' => 'bg-success',
            'facil' => 'bg-info',
            'medio' => 'bg-warning',
            'dificil' => 'bg-danger',
            'muito_dificil' => 'bg-dark',
            default => 'bg-warning',
        };
    }

    /**
     * Get priority icon.
     */
    public function getPriorityIcon()
    {
        return match($this->priority) {
            'muito_facil' => 'bi-circle',
            'facil' => 'bi-circle-half',
            'medio' => 'bi-dash-circle',
            'dificil' => 'bi-exclamation-circle',
            'muito_dificil' => 'bi-exclamation-triangle-fill',
            default => 'bi-dash-circle',
        };
    }
}
