<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('soap_logs', function (Blueprint $table) {
        $table->string('diagnosa_id')->nullable()->after('doctor_id');
    });
}

public function down()
{
    Schema::table('soap_logs', function (Blueprint $table) {
        $table->dropColumn('diagnosa_id');
    });
}
};
