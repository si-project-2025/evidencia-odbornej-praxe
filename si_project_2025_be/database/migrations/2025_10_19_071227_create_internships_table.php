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
        Schema::create('internships', function (Blueprint $table) {
            $table->integer('internships_id', true);
            $table->enum('semester', ['Z', 'L']);
            $table->integer('year');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('end_at')->nullable();
            $table->integer('users_id')->index('fk_interships_users1_idx');
            $table->integer('company_id')->index('fk_interships_companies1_idx');
            $table->integer('status_id')->index('fk_interships_status1_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
};
