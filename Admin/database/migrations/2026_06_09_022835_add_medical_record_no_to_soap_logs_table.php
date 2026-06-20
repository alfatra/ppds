<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('soap_logs', function (Blueprint $table) {
            $table->string('medical_record_no')->nullable()->after('patient_registration_no');
        });
    }

    public function down()
    {
        Schema::table('soap_logs', function (Blueprint $table) {
            $table->dropColumn('medical_record_no');
        });
    }
};
