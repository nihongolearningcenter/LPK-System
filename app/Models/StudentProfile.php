<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_number',
        'birth_date',
        'birth_place',
        'gender',
        'address',
        'city',
        'province',
        'postal_code',
        'education_level',
        'school_name',
        'whatsapp',
        'emergency_contact_name',
        'emergency_contact_phone',
        'japanese_level',
        'photo',
        'id_card',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
