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
            // Tambahkan field nama_dpjp setelah field plan hanya jika belum ada
            if (!Schema::hasColumn('soap_logs', 'nama_dpjp')) {
                $table->string('nama_dpjp')->nullable()->after('plan');
            }
        });
    }

    /**+
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soap_logs', function (Blueprint $table) {
            $table->dropColumn('nama_dpjp');
        });
    }
};
