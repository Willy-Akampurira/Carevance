<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\MedicalRecord;

class Patient extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',
        'branch_id',
        'name',
        'gender',
        'dob',
        'contact',
        'email',
        'address',
        'medical_history',
        'entry_date',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'dob' => 'date',
        'entry_date' => 'date',   // ✅ cast entry_date to date
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
