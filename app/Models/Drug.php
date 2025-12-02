<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Import related models
use App\Models\Prescription;
use App\Models\PurchaseOrderItem;

class Drug extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'category',
        'quantity',
        'expiry_date',
        'reorder_level',
        'description',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $dates = ['expiry_date'];

    /**
     * Relationships
     */

    // A drug can appear in many prescriptions
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    // A drug can appear in many purchase order items
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
