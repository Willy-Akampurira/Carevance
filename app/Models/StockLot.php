<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'drug_id',
        'name',
        'category_id',
        'description',
        'unit',
        'quantity',
        'reserved',
        'expiry_date',
        'reorder_level',
        'status', // 'new', 'old', 'depleted'
    ];

    /**
     * Casts ensure proper data types.
     */
    protected $casts = [
        'expiry_date' => 'date',     // Carbon instance
        'reserved'    => 'boolean',
        'quantity'    => 'integer',
    ];

    /**
     * Relationship: A stock lot belongs to a drug.
     */
    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    /**
     * Relationship: A stock lot may belong to a category.
     */
    public function category()
    {
        return $this->belongsTo(DrugCategory::class, 'category_id');
    }

    /**
     * Relationship: A stock lot has many adjustments (audit trail).
     */
    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }

    /**
     * Relationship: A stock lot can be linked to prescriptions.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'lot_id');
    }

    /**
     * Scope: Only active lots (not expired, not depleted).
     */
    public function scopeActive($query)
    {
        return $query->whereDate('expiry_date', '>=', now())
                     ->where('quantity', '>', 0);
    }

    /**
     * Scope: Expired lots.
     */
    public function scopeExpired($query)
    {
        return $query->whereDate('expiry_date', '<', now());
    }
}
