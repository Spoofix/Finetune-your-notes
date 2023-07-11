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
            $table->string('organization');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('requires_password_update', [true, false])->default('');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('temp_password')->nullable();
            $table->integer('role_id')->default(2);
            $table->enum('status', ['PENDING', 'ACTIVE', 'LOCKED'])->default('ACTIVE');
            $table->rememberToken();

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
