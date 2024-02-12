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
        Schema::create('reportform_takedown_details', function (Blueprint $table) {
            $table->id();
            $table->string('abuse_type');
            $table->string('evidence_urls');
            $table->string('additional_notes');
            $table->string('observed_date');
            $table->string('attachments');
            $table->string('carbon_copy_request_to');
            $table->string('reported_by_user_id');
            $table->enum('reported_via', ['report_form', 'takedown'])->default('takedown');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportform_takedown_details');
    }
};
