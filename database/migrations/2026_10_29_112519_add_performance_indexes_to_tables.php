<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index(['role', 'is_active'], 'users_role_is_active_idx');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->index('status', 'classes_status_idx');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->index('status', 'enrollments_status_idx');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('status', 'payments_status_idx');
        });

        Schema::table('attendance', function (Blueprint $table) {
            $table->index(['class_id', 'date'], 'attendance_class_date_idx');
            $table->index('student_id', 'attendance_student_idx');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->index(['created_by', 'is_published'], 'lessons_created_published_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_is_active_idx');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropIndex('classes_status_idx');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('enrollments_status_idx');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_status_idx');
        });

        Schema::table('attendance', function (Blueprint $table) {
            $table->dropIndex('attendance_class_date_idx');
            $table->dropIndex('attendance_student_idx');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropIndex('lessons_created_published_idx');
        });
    }
};
