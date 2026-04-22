<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'invoice_date',
        'invoice_number',
        'amount',
        'status',
        'notes',
    ];

    /* ============================================================
     | Relationships
     |============================================================ */

    /**
     * Each invoice belongs to a supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Each invoice may have multiple payments.
     */
    public function payments()
    {
        return $this->hasMany(SupplierPayment::class, 'invoice_id');
    }

    /**
     * Optional: link invoice to deliveries if needed
     * (e.g., invoice generated from a delivery).
     */
    public function deliveries()
    {
        return $this->belongsToMany(Delivery::class, 'delivery_invoice')
                    ->withTimestamps();
    }
}