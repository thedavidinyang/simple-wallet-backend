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
            $table->id();
            $table->string('phone', 16)->unique()->nullable();
            $table->string('full_name', 225);
            $table->string('email', 225)->unique();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('password');
            $table->string('uuid', 225)->unique();
            $table->string('username', 225)->unique()->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
