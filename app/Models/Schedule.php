<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'class_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
        'notes',
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function getDayAttribute()
    {
        return $this->day_of_week;
    }
}
