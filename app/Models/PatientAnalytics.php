<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAnalytics extends Model
{
    use HasFactory;

    protected $table = 'patient_analytics';

    protected $fillable = [
        'snapshot_date',
        'total_patients',
        'new_patients',
        'age_group_0_18',
        'age_group_19_35',
        'age_group_36_60',
        'age_group_60_plus',
        'top_disease_category',
        'disease_category_counts',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'disease_category_counts' => 'array', // JSON cast to array
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
