<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('score_assignments', 5, 2)->nullable();
            $table->decimal('score_midterm', 5, 2)->nullable();
            $table->decimal('score_final', 5, 2)->nullable();
            $table->decimal('score_attendance', 5, 2)->nullable();
            $table->decimal('final_grade', 5, 2)->nullable();
            $table->string('grade_letter')->nullable(); // A, B, C, D, E
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['student_id', 'class_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
