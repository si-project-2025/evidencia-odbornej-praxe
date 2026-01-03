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
            $table->id('internships_id');
            $table->enum('semester', ['Z', 'L']);
            $table->integer('year');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('end_at')->nullable();
            $table->unsignedBigInteger('users_id')->index('fk_interships_users1_idx');
            $table->unsignedBigInteger('company_id')->index('fk_interships_companies1_idx');
            $table->unsignedBigInteger('status_id')->index('fk_interships_status1_idx');
            $table->unsignedBigInteger('contact_person_id')->nullable()->index('fk_interships_contact_person1_idx');
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
