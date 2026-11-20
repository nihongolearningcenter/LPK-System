<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
            $table->string('japanese'); // 日本語
            $table->string('hiragana')->nullable(); // ひらがな
            $table->string('romaji')->nullable(); // romaji
            $table->string('meaning_id'); // Arti Bahasa Indonesia
            $table->string('part_of_speech')->nullable(); // Kata benda, kata kerja, dll
            $table->enum('jlpt_level', ['N5', 'N4', 'N3', 'N2', 'N1', 'dasar'])->nullable();
            $table->string('category')->nullable(); // Tema: perkenalan, angka, dll
            $table->text('example_sentence_jp')->nullable();
            $table->text('example_sentence_id')->nullable();
            $table->string('audio_file')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vocabularies');
    }
};
