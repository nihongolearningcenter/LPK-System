<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'japanese',
        'hiragana',
        'romaji',
        'meaning_id',
        'part_of_speech',
        'jlpt_level',
        'category',
        'example_sentence_jp',
        'example_sentence_id',
        'audio_file',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
