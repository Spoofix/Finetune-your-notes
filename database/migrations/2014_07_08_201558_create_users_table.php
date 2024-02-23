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
            $table->string('name');
            $table->string('second_name')->nullable();
            $table->string('organization')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('requires_password_update', ['1', '']);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('temp_password')->nullable();
            $table->integer('role_id');
            $table->enum('status', ['PENDING', 'ACTIVE', 'LOCKED'])->default('ACTIVE');
            $table->string('remember_token', 100)->nullable();
            $table->timestamp('last_login')->nullable()->default('2024-02-22 21:03:45');
            $table->integer('org_id')->nullable();
            $table->integer('org_role_id')->nullable();
            $table->string('profile')->nullable();
            $table->timestamps();
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
