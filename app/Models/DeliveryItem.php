<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'delivery_id',
        'drug_id',
        'batch_number',
        'expiry_date',
        'quantity_received',
        'unit_cost',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'quantity_received' => 'integer',
        'unit_cost' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }
}
