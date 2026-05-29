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
        Schema::create('attendance_configs', function (Blueprint $table) {
            $table->id();
            $table->string('config_name')->comment('Nama konfigurasi (misal: PPDS, Dokter, dll)');
            $table->integer('max_absence_days')->comment('Jumlah hari maksimal absensi yang diperbolehkan');
            $table->integer('max_sick_days')->nullable()->comment('Jumlah hari maksimal sakit yang diperbolehkan');
            $table->integer('max_permission_days')->nullable()->comment('Jumlah hari maksimal izin yang diperbolehkan');
            $table->integer('period_in_days')->default(30)->comment('Periode perhitungan dalam hari (default 30 hari)');
            $table->text('description')->nullable()->comment('Deskripsi konfigurasi');
            $table->boolean('is_active')->default(true)->comment('Apakah konfigurasi aktif');
            $table->timestamps();
            
            $table->index('config_name');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_configs');
    }
};
