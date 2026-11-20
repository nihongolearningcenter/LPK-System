<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->longText('content');
            $table->string('thumbnail')->nullable();
            $table->enum('target', ['all', 'students', 'instructors', 'specific_class'])->default('all');
            $table->foreignId('class_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['pengumuman', 'artikel', 'berita'])->default('pengumuman');
            $table->string('slug')->nullable();
            $table->boolean('is_published')->default(false);
            $table->datetime('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
