<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialRecordItem extends Model
{
    use HasFactory;

    protected $table = 'financial_record_items';

    protected $fillable = [
        'financial_record_id',
        'description',
        'quantity',
        'unit_price',
        'total',
    ];

    protected $casts = [
        'quantity'   => 'integer',
        'unit_price' => 'decimal:2',
        'total'      => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship back to invoice
    public function financialRecord()
    {
        return $this->belongsTo(FinancialRecord::class);
    }

    // Helper: calculate line total if not stored
    public function getLineTotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }
}
