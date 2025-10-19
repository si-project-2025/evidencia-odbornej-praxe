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
            $table->foreign(['address_id'], 'fk_users_address1')->references(['address_id'])->on('address')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['role_id'], 'fk_users_roles')->references(['role_id'])->on('roles')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_users_address1');
            $table->dropForeign('fk_users_roles');
        });
    }
};
