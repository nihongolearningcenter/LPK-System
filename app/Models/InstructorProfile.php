<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'birth_date',
        'gender',
        'address',
        'city',
        'whatsapp',
        'education_level',
        'specialization',
        'japanese_certification',
        'bio',
        'photo',
        'join_date',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date'  => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
