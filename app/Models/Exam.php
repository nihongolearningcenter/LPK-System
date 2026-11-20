<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'created_by',
        'title',
        'description',
        'type',
        'jlpt_level',
        'duration_minutes',
        'total_questions',
        'passing_score',
        'randomize_questions',
        'randomize_options',
        'show_result_immediately',
        'show_explanation',
        'max_attempts',
        'available_from',
        'available_until',
        'is_published',
    ];

    protected $casts = [
        'duration_minutes'        => 'integer',
        'total_questions'         => 'integer',
        'passing_score'           => 'decimal:2',
        'randomize_questions'     => 'boolean',
        'randomize_options'       => 'boolean',
        'show_result_immediately' => 'boolean',
        'show_explanation'        => 'boolean',
        'max_attempts'            => 'integer',
        'is_published'            => 'boolean',
        'available_from'          => 'datetime',
        'available_until'         => 'datetime',
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
                    ->withPivot('sort_order', 'points')
                    ->withTimestamps();
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
