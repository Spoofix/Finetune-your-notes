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
            $table->integer('org_id');
            $table->integer('org_role_id');
            $table->string('profile');
            $table->string('second_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('org_id');
            $table->dropColumn('org_role_id');
            $table->dropColumn('profile');
            $table->dropColumn('second_name');
        });
    }
};
