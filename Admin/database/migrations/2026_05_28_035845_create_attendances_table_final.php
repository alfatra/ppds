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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('attendance_date')->comment('Tanggal absensi');
            $table->enum('status', ['hadir', 'alpha', 'izin', 'sakit'])->default('hadir')->comment('Status kehadiran');
            $table->text('keterangan')->nullable()->comment('Keterangan tambahan');
            $table->timestamps();
            
            // Index untuk query yang lebih cepat
            $table->unique(['user_id', 'attendance_date'])->comment('Pastikan tidak ada duplikat absensi per hari');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
