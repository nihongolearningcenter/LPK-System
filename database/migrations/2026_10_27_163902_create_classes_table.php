<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name'); // Nama kelas, mis: Kelas N5 - Pagi A
            $table->string('code')->unique()->nullable();
            $table->enum('method', ['online', 'offline', 'hybrid'])->default('offline');
            $table->string('room')->nullable(); // Ruangan
            $table->string('meeting_link')->nullable(); // link meeting
            $table->integer('max_students')->default(20);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'ongoing', 'upcoming', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
