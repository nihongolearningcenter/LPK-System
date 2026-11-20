<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('question_text'); // Soal (bisa berisi HTML/teks Jepang)
            $table->string('question_image')->nullable();
            $table->string('question_audio')->nullable();
            $table->enum('type', ['pilihan_ganda', 'esai', 'benar_salah'])->default('pilihan_ganda');
            $table->enum('jlpt_level', ['N5', 'N4', 'N3', 'N2', 'N1', 'dasar'])->nullable();
            $table->enum('category', ['kosakata', 'tata_bahasa', 'membaca', 'menyimak', 'kanji'])->nullable();
            $table->string('correct_answer')->nullable(); // Untuk esai/benar-salah
            $table->text('explanation')->nullable(); // Pembahasan jawaban
            $table->integer('points')->default(1);
            $table->enum('difficulty', ['mudah', 'sedang', 'sulit'])->default('sedang');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
