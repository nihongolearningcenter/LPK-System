<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique()->nullable(); // Kode program, mis: N5-2024
            $table->enum('type', ['dasar', 'jlpt_n5', 'jlpt_n4', 'jlpt_n3', 'kaiwa', 'kerja', 'tokutei_ginou'])->default('dasar');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('target_participant')->nullable();
            $table->integer('duration_weeks')->nullable();
            $table->string('session_per_week')->nullable();
            $table->integer('session_duration_minutes')->nullable(); // Durasi per sesi
            $table->longText('curriculum')->nullable(); // JSON atau teks
            $table->string('method')->nullable(); // Online, Offline, Hybrid
            $table->decimal('price', 12, 2)->default(0);
            $table->text('requirements')->nullable();
            $table->string('certificate_name')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
