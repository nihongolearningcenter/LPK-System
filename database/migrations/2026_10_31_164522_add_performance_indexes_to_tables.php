<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add composite and single-column indexes for frequently queried columns.
     * These dramatically reduce full-table scans on common dashboard/listing queries.
     */
    public function up(): void
    {
        // users — role + is_active combo used on every dashboard stats query
        Schema::table('users', function (Blueprint $table) {
            if (!$this->indexExists('users', 'users_role_is_active_index')) {
                $table->index(['role', 'is_active'], 'users_role_is_active_index');
            }
        });

        // enrollments — status + created_at for recent-enrollments listing
        Schema::table('enrollments', function (Blueprint $table) {
            if (!$this->indexExists('enrollments', 'enrollments_status_created_at_index')) {
                $table->index(['status', 'created_at'], 'enrollments_status_created_at_index');
            }
            if (!$this->indexExists('enrollments', 'enrollments_email_index')) {
                $table->index('email', 'enrollments_email_index');
            }
        });

        // payments — status + paid_at for monthly revenue aggregation
        Schema::table('payments', function (Blueprint $table) {
            if (!$this->indexExists('payments', 'payments_status_paid_at_index')) {
                $table->index(['status', 'paid_at'], 'payments_status_paid_at_index');
            }
            if (!$this->indexExists('payments', 'payments_student_id_status_index')) {
                $table->index(['student_id', 'status'], 'payments_student_id_status_index');
            }
            if (!$this->indexExists('payments', 'payments_enrollment_id_index')) {
                $table->index('enrollment_id', 'payments_enrollment_id_index');
            }
        });

        // classes — status index for active-class queries
        Schema::table('classes', function (Blueprint $table) {
            if (!$this->indexExists('classes', 'classes_status_index')) {
                $table->index('status', 'classes_status_index');
            }
        });

        // exam_attempts — student_id + status for score aggregation
        Schema::table('exam_attempts', function (Blueprint $table) {
            if (!$this->indexExists('exam_attempts', 'exam_attempts_student_status_index')) {
                $table->index(['student_id', 'status'], 'exam_attempts_student_status_index');
            }
        });

        // announcements — is_published + target + published_at for announcement listing
        Schema::table('announcements', function (Blueprint $table) {
            if (!$this->indexExists('announcements', 'announcements_published_target_index')) {
                $table->index(['is_published', 'target', 'published_at'], 'announcements_published_target_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndexIfExists('users_role_is_active_index');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndexIfExists('enrollments_status_created_at_index');
            $table->dropIndexIfExists('enrollments_email_index');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndexIfExists('payments_status_paid_at_index');
            $table->dropIndexIfExists('payments_student_id_status_index');
            $table->dropIndexIfExists('payments_enrollment_id_index');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropIndexIfExists('classes_status_index');
        });

        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropIndexIfExists('exam_attempts_student_status_index');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndexIfExists('announcements_published_target_index');
        });
    }

    /**
     * Check if an index already exists (avoids duplicate index errors on re-run).
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection();
        $dbName     = $connection->getDatabaseName();
        $driver     = $connection->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite: pragma index_list
            $indexes = $connection->select("PRAGMA index_list(\"{$table}\")");
            return collect($indexes)->pluck('name')->contains($indexName);
        }

        // MySQL / MariaDB
        $result = $connection->select(
            "SELECT COUNT(*) as cnt
             FROM information_schema.statistics
             WHERE table_schema = ? AND table_name = ? AND index_name = ?",
            [$dbName, $table, $indexName]
        );

        return ($result[0]->cnt ?? 0) > 0;
    }
};
