<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Drug;
use App\Models\StockLot;

class DrugCategory extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relationship: One category has many drugs
     */
    public function drugs()
    {
        return $this->hasMany(Drug::class, 'category_id');
    }

    /**
     * Relationship: A category has many stock lots through its drugs
     */
    public function stockLots()
    {
        return $this->hasManyThrough(StockLot::class, Drug::class, 'category_id', 'drug_id');
    }
}
