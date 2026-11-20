<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'subject_id',
        'score_assignments',
        'score_midterm',
        'score_final',
        'score_attendance',
        'final_grade',
        'grade_letter',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'score_assignments' => 'decimal:2',
        'score_midterm'     => 'decimal:2',
        'score_final'       => 'decimal:2',
        'score_attendance'  => 'decimal:2',
        'final_grade'       => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
