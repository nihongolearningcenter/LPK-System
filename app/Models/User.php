<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ─── Role Helpers ────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Cek apakah siswa sudah mengikuti program (memiliki kelas aktif atau pendaftaran disetujui).
     */
    public function hasEnrolledProgram(): bool
    {
        return $this->classes()->wherePivot('status', 'active')->exists()
            || $this->enrollments()->whereIn('status', ['accepted', 'enrolled'])->exists();
    }

    /**
     * Cek apakah siswa sudah membayar program (memiliki tagihan berstatus lunas / paid).
     */
    public function hasPaidProgram(): bool
    {
        return $this->payments()->where('status', 'paid')->exists();
    }

    /**
     * Akses menu pembelajaran (jadwal hingga progres) hanya aktif jika siswa
     * telah mengikuti program DAN telah membayar (lunas).
     */
    public function canAccessLearningFeatures(): bool
    {
        if (!$this->isStudent()) {
            return true;
        }

        return $this->hasEnrolledProgram() && $this->hasPaidProgram();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(public_path($this->avatar))) {
            return asset($this->avatar);
        }
        return asset('images/profile.png');
    }

    // ─── Relationships ───────────────────────────────────────────
    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function instructorProfile()
    {
        return $this->hasOne(InstructorProfile::class);
    }

    public function taughtClasses()
    {
        return $this->hasMany(ClassRoom::class, 'instructor_id');
    }

    public function classStudents()
    {
        return $this->hasMany(ClassStudent::class, 'student_id');
    }

    public function classes()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_students', 'student_id', 'class_id')
                    ->withPivot('joined_at', 'status')
                    ->withTimestamps();
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class, 'student_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'student_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'student_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }
}
