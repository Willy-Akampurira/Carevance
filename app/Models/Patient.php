<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Appointment;    // Import Appointment model for relationship
use App\Models\Prescription;   // Import Prescription model for relationship
use App\Models\MedicalRecord;  // Import MedicalRecord model for relationship

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'gender',
        'dob',
        'contact',
        'email',
        'address',
        'medical_history',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'dob' => 'date',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Patient has many Appointments
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Relationship: Patient has many Prescriptions
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Relationship: Patient has many Medical Records
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
