<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReport extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_id',
        'title',
        'score',
        'remarks',
        'period_start',
        'period_end',
        'total_hours',
        'generated_by_system',
    ];

    /**
     * Relationship: each performance report belongs to one staff member.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
