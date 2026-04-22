<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'staff_id',
        'action',
        'description',
        'ip_address',
    ];

    /**
     * Relationship: Activity Log belongs to Staff
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
