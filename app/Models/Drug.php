<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Import related models
use App\Models\Prescription;
use App\Models\PurchaseOrderItem;
use App\Models\DrugCategory;
use App\Models\StockLot;
use App\Models\StockAdjustment;

class Drug extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'unit',
        'reserved',
        'reorder_level',
        'description',
    ];

    protected $casts = [
        'reserved' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function category()
    {
        return $this->belongsTo(DrugCategory::class, 'category_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function stockLots()
    {
        return $this->hasMany(StockLot::class);
    }

    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }

    /**
     * Helper: Get total quantity across all stock lots
     */
    public function totalQuantity()
    {
        return $this->stockLots()->sum('quantity');
    }

    /**
     * Accessor: Is this drug out of stock?
     */
    public function getIsOutOfStockAttribute()
    {
        return $this->totalQuantity() <= 0;
    }

    /**
     * Scope: Out of stock drugs (lot-level)
     */
    public function scopeOutOfStock($query)
    {
        return $query->whereDoesntHave('stockLots', function ($q) {
            $q->where('quantity', '>', 0);
        });
    }

    /**
     * Scope: expired drugs (lot-level)
     */
    public function scopeExpired($query)
    {
        return $query->whereHas('stockLots', function ($q) {
            $q->whereDate('expiry_date', '<=', now());
        });
    }

    /**
     * Scope: nearing expiry drugs (within threshold days, lot-level)
     */
    public function scopeNearing($query, int $days)
    {
        $today = now();
        $limit = $today->copy()->addDays($days);

        return $query->whereHas('stockLots', function ($q) use ($today, $limit) {
            $q->whereDate('expiry_date', '>=', $today)
              ->whereDate('expiry_date', '<=', $limit);
        });
    }
}
