<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Import related models
use App\Models\PurchaseOrder;
use App\Models\Drug;

class PurchaseOrderItem extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'purchase_order_id',
        'drug_id',
        'description',
        'quantity',
        'unit_price',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'quantity'   => 'integer',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    /**
     * Relationships
     */

    // Each item belongs to a purchase order
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    // Each item may optionally belong to a drug
    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }
}
