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
        Schema::create('internship_verification_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Email kontaktnej osoby
            $table->unsignedBigInteger('internships_id'); // ID praxe
            $table->string('token'); // Hashovaný token
            $table->timestamp('created_at')->nullable();

            $table->foreign('internships_id')
                ->references('internships_id')
                ->on('internships')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_verification_tokens');
    }
};
