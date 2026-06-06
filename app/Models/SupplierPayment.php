<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'supplier_id',
        'invoice_id',
        'payment_date',
        'amount',
        'method',
        'reference',
        'notes',
    ];

    /* ============================================================
     | Relationships
     |============================================================ */

    /**
     * Each payment belongs to a supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Each payment belongs to an invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(SupplierInvoice::class, 'invoice_id');
    }
}