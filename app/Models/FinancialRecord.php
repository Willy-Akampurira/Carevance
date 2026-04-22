<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\FinancialRecordItem;
use App\Models\Payment;

class FinancialRecord extends Model
{
    use HasFactory;

    protected $table = 'financial_records';

    protected $fillable = [
        'patient_id',
        'invoice_number',
        'invoice_date',
        'amount',              // grand total
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

    // ------------------- Relationships -------------------

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function items()
    {
        return $this->hasMany(FinancialRecordItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ------------------- Scopes -------------------

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

    // ------------------- Helpers -------------------

    // Calculate grand total from items
    public function getCalculatedTotalAttribute()
    {
        return $this->items->sum(fn($item) => $item->quantity * $item->unit_price);
    }

    // Calculate total paid from payments
    public function getTotalPaidAttribute()
    {
        return $this->payments->sum('amount');
    }

    // Calculate outstanding balance
    public function getBalanceAttribute()
    {
        return $this->amount - $this->total_paid;
    }
}
