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
            $table->string('ttv_td')->nullable()->comment('Tekanan Darah');
            $table->string('ttv_hr')->nullable()->comment('Heart Rate / Nadi');
            $table->string('ttv_rr')->nullable()->comment('Respiratory Rate');
            $table->string('ttv_temp')->nullable()->comment('Suhu');
            $table->string('ttv_spo2')->nullable()->comment('Saturasi Oksigen');
            $table->string('ttv_vas')->nullable()->comment('Nyeri / VAS');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soap_logs', function (Blueprint $table) {
            $table->dropColumn([
                'ttv_td', 'ttv_hr', 'ttv_rr', 'ttv_temp', 'ttv_spo2', 'ttv_vas'
            ]);
        });
    }
};
