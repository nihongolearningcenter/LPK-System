<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique(); // Nomor pendaftaran otomatis
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->nullable()->constrained()->nullOnDelete();
            // Data calon siswa (bisa belum punya akun)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 20);
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->text('address')->nullable();
            $table->string('education_level')->nullable();
            $table->string('japanese_experience')->nullable();
            $table->string('schedule_preference')->nullable();
            $table->string('photo')->nullable();
            $table->string('document_ijazah')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'reviewing', 'accepted', 'rejected', 'enrolled'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
