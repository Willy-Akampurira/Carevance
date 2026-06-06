<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseOrder;
use App\Models\Drug;

class PurchaseOrderItem extends Model
{
    use BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',
        'branch_id',
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
