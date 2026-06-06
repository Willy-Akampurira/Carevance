<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];

    /**
     * A shift can have many attendance records.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
