<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'attempt_number',
        'started_at',
        'submitted_at',
        'time_spent_seconds',
        'score',
        'max_score',
        'correct_count',
        'wrong_count',
        'unanswered_count',
        'is_passed',
        'status',
    ];

    protected $casts = [
        'started_at'         => 'datetime',
        'submitted_at'       => 'datetime',
        'score'              => 'decimal:2',
        'max_score'          => 'decimal:2',
        'is_passed'          => 'boolean',
        'attempt_number'     => 'integer',
        'time_spent_seconds' => 'integer',
        'correct_count'      => 'integer',
        'wrong_count'        => 'integer',
        'unanswered_count'   => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class, 'attempt_id');
    }
}
