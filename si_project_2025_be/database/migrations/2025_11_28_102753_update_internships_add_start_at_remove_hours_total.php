<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('internships', 'start_at')) {
            Schema::table('internships', function (Blueprint $table) {
                $table->timestamp('start_at')->nullable()->after('year');
            });
        }

        if (Schema::hasColumn('internships', 'hours_total')) {
            Schema::table('internships', function (Blueprint $table) {
                $table->dropColumn('hours_total');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('internships', 'hours_total')) {
            Schema::table('internships', function (Blueprint $table) {
                $table->integer('hours_total')->after('semester');
            });
        }

        if (Schema::hasColumn('internships', 'start_at')) {
            Schema::table('internships', function (Blueprint $table) {
                $table->dropColumn('start_at');
            });
        }
    }
};
