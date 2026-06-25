<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyActivity extends Model
{
    protected $fillable = [
        'user_id',
        'medical_activity_id',
        'activity_date',
        'patient_name',
        'medical_record_no',
        'notes',
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicalActivity()
    {
        return $this->belongsTo(MedicalActivity::class);
    }
}
