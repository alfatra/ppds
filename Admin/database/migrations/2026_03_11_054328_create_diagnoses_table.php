<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->string('diagnose_id')->unique();
            $table->text('diagnose_name');
            $table->json('bp_js_reference_info')->nullable();
            $table->boolean('is_chronic_disease')->default(false);
            $table->boolean('is_infectious')->default(false);
            $table->boolean('is_disease')->default(false);
            $table->boolean('is_nutrition_diagnosis')->default(false);
            $table->boolean('is_external_diagnosis')->default(false);
            $table->boolean('is_potential_prb')->default(false);
            $table->boolean('is_covered_by_bpjs')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};