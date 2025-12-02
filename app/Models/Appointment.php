<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;        // Import Patient model for relationship
use App\Models\Prescription;   // Import Prescription model for relationship
use App\Models\MedicalRecord;  // Import MedicalRecord model for relationship

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor',
        'scheduled_at',
        'reason',
        'notes',
        'visit_summary',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    /**
     * Relationship: Appointment belongs to a Patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relationship: Appointment has many Prescriptions
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Relationship: Appointment has many Medical Records
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    /**
     * Scope for upcoming appointments
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                     ->orderBy('scheduled_at');
    }

    /**
     * Scope for completed appointments (visit history)
     */
    public function scopeHistory($query)
    {
        return $query->where('status', 'completed')
                     ->orderByDesc('scheduled_at');
    }
}
