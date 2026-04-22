<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'department_id',
        'role_id',
        'status',
    ];

    /**
     * Relationship: Staff belongs to a Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship: Staff belongs to a Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relationship: Staff has many Activity Logs
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Relationship: Staff has many Attendance records
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function performanceReports()
    {
        return $this->hasMany(PerformanceReport::class);
    }
}
