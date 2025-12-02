<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Drug;

class Prescription extends Model
{
    use HasFactory;

    protected $table = 'prescriptions';

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'drug_id',
        'drug_name',
        'dosage',
        'frequency',
        'duration_days',
        'start_date',
        'end_date',
        'issued_by',
        'status',
        'category',
        'renewal_requested',
        'notes',
    ];

    protected $casts = [
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'start_date'        => 'date',
        'end_date'          => 'date',
        'renewal_requested' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    /**
     * Query Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeHistory($query)
    {
        return $query->whereIn('status', ['completed', 'expired', 'missed', 'dispensed']);
    }

    public function scopeRenewalRequests($query)
    {
        return $query->where('renewal_requested', true)
                     ->orWhere('status', 'renewal_requested');
    }
}
