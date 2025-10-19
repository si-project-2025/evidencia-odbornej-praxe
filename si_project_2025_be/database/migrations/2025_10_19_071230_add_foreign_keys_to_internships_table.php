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
        Schema::table('internships', function (Blueprint $table) {
            $table->foreign(['company_id'], 'fk_interships_companies1')->references(['company_id'])->on('companies')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['status_id'], 'fk_interships_status1')->references(['status_id'])->on('status')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['users_id'], 'fk_interships_users1')->references(['users_id'])->on('users')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->dropForeign('fk_interships_companies1');
            $table->dropForeign('fk_interships_status1');
            $table->dropForeign('fk_interships_users1');
        });
    }
};
