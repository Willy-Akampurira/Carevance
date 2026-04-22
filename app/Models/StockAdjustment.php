<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'drug_id',
        'stock_lot_id',
        'old_quantity',
        'new_quantity',
        'reason',
        'user_id',
    ];

    /**
     * Relationships
     */

    // A stock adjustment belongs to a drug
    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    // A stock adjustment belongs to a specific stock lot
    public function stockLot()
    {
        return $this->belongsTo(StockLot::class);
    }

    // A stock adjustment is performed by a user (manager/admin)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
