<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $fillable = [
        'diagnose_id',
        'diagnose_name',
        'bp_js_reference_info',
        'is_chronic_disease',
        'is_infectious',
        'is_disease',
        'is_nutrition_diagnosis',
        'is_external_diagnosis',
        'is_potential_prb',
        'is_covered_by_bpjs',
    ];

    protected $casts = [
        'bp_js_reference_info' => 'json',
    ];
}
