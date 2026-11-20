<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'created_by',
        'title',
        'description',
        'file_attachment',
        'due_date',
        'max_score',
        'allow_late_submission',
        'is_published',
    ];

    protected $casts = [
        'due_date'              => 'datetime',
        'max_score'             => 'decimal:2',
        'allow_late_submission' => 'boolean',
        'is_published'          => 'boolean',
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
