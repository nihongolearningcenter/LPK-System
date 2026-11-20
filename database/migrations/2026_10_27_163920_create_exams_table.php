<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['latihan', 'ujian_harian', 'ujian_tengah', 'ujian_akhir', 'simulasi_jlpt'])->default('latihan');
            $table->enum('jlpt_level', ['N5', 'N4', 'N3', 'N2', 'N1', 'dasar'])->nullable();
            $table->integer('duration_minutes')->default(60);
            $table->integer('total_questions')->default(0);
            $table->decimal('passing_score', 5, 2)->default(70.00);
            $table->boolean('randomize_questions')->default(true);
            $table->boolean('randomize_options')->default(true);
            $table->boolean('show_result_immediately')->default(true);
            $table->boolean('show_explanation')->default(true);
            $table->integer('max_attempts')->default(0); // 0 = tidak terbatas
            $table->datetime('available_from')->nullable();
            $table->datetime('available_until')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
