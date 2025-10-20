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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('users_id', true);
            $table->string('email', 191)->unique('email_unique');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->string('alt_email', 255)->nullable();
            $table->string('name', 100);
            $table->string('surname', 100);
            $table->string('study_program', 100)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->timestamps();
            $table->timestamp('last_login')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('role_id')->index('fk_users_roles_idx');
            $table->integer('address_id')->nullable()->index('fk_users_address1_idx');
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
