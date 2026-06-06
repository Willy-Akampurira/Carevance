<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'departments';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relationship: Department has many Staff
     */
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}
