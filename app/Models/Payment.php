<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_number',
        'student_id',
        'enrollment_id',
        'class_id',
        'description',
        'amount',
        'amount_paid',
        'discount',
        'payment_method',
        'payment_proof',
        'due_date',
        'paid_at',
        'status',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'due_date'    => 'date',
        'paid_at'     => 'date',
        'amount'      => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'discount'    => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            if (empty($payment->invoice_number)) {
                $payment->invoice_number = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
