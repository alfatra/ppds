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
            // Menambahkan kolom untuk menyimpan nama diagnosa
            $table->string('diagnosis')->nullable()->after('plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soap_logs', function (Blueprint $table) {
            $table->dropColumn('diagnosis');
        });
    }
};
