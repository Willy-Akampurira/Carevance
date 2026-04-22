<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'financial_record_id',
        'amount',
        'payment_method',
        'payment_date',
    ];

    /**
     * Each payment belongs to a financial record (invoice).
     */
    public function invoice()
    {
        return $this->belongsTo(FinancialRecord::class, 'financial_record_id');
    }
}
