<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Drug;
use App\Models\StockLot;

class Prescription extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'prescriptions';

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'appointment_id',
        'patient_id',
        'drug_id',
        'lot_id',
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
        'quantity',
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

    public function stockLot()
    {
        return $this->belongsTo(StockLot::class, 'lot_id');
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
