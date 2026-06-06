<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'staff_id',
        'shift_id',
        'date',
        'clock_in',
        'clock_out',
        'ip_address',
    ];

    /**
     * Attendance belongs to a staff member.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Attendance belongs to a shift.
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
