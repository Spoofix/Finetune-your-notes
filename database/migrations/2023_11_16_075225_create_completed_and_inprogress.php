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
        Schema::create('completed_and_inprogress', function (Blueprint $table) {
            $table->id();
            $table->integer('domain_id');
            $table->integer('reporting_user_id');
            $table->date('authorization_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('time_elapsed');
            $table->enum('status', ['waiting_for_authorization', 'initiated', 'completed'])->default('waiting_for_authorization');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('completed_and_inprogress');
    }
};
