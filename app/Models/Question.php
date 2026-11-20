<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'created_by',
        'question_text',
        'question_image',
        'question_audio',
        'type',
        'jlpt_level',
        'category',
        'correct_answer',
        'explanation',
        'points',
        'difficulty',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'points'    => 'integer',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_questions')
                    ->withPivot('sort_order', 'points')
                    ->withTimestamps();
    }
}
