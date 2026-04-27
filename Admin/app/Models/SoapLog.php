<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SoapLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
    'patient_id',
    'doctor_id',
    'visit_date',
    'subjective',
    'objective',
    'assessment',
    'plan',
    'nama_dpjp', 
    'created_by',
    'updated_by',
    'diagnosa_id',
];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'visit_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relations - if you have Patient and User models
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function diagnosis()
{
    return $this->belongsTo(Diagnosis::class,'diagnosa_id','diagnose_id');
}

    /**
     * Relasi ke user yang membuat data.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

