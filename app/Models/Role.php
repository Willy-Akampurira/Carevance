<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'guard_name',
    ];

    // Relationship: one role can have many staff members
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}
