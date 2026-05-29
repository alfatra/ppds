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
        Schema::table('soap_logs', function (Blueprint $table) {
            // Add fields for manual patient entry
            $table->string('patient_name_manual')->nullable()->comment('Patient name when entered manually (not from API)');
            $table->string('patient_registration_no')->nullable()->comment('Registration number for manual entry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soap_logs', function (Blueprint $table) {
            $table->dropColumn(['patient_name_manual', 'patient_registration_no']);
        });
    }
};
