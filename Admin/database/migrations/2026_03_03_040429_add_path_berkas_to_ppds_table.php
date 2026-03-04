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
        Schema::table('ppds', function (Blueprint $table) {
            // Tambahkan kolom untuk menyimpan path file setelah kolom 'alamat'
            $table->string('path_berkas')->nullable()->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppds', function (Blueprint $table) {
            $table->dropColumn('path_berkas');
        });
    }
};
