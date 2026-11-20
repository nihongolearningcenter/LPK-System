<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'code', 'type', 'short_description', 'description',
        'target_participant', 'duration_weeks', 'session_per_week',
        'session_duration_minutes', 'curriculum', 'method', 'price',
        'requirements', 'certificate_name', 'thumbnail', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function classes()
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    // Title alias accessor
    public function getTitleAttribute()
    {
        return $this->name;
    }

    // Scope untuk program aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
