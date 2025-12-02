<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;

class FinancialRecord extends Model
{
    use HasFactory;

    protected $table = 'financial_records';

    protected $fillable = [
        'patient_id',
        'invoice_number',
        'invoice_date',
        'amount',
        'status',
        'insurance_provider',
        'claim_number',
        'claim_status',
        'payment_method',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'payment_date' => 'date',
        'amount'       => 'decimal:2',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // Relationship
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Scopes
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePendingClaims($query)
    {
        return $query->where('claim_status', 'pending');
    }
}
