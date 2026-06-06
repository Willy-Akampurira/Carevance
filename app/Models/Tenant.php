<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'subdomain',
        'email',
        'phone',
        'plan',
        'status',
        'trial_ends_at',
        'subscription_ends_at',
        'meta',
    ];

    protected $casts = [
        'trial_ends_at'          => 'datetime',
        'subscription_ends_at'   => 'datetime',
        'meta'                   => 'array',
    ];

    // ──────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────

    /**
     * A tenant has many branches (clinic locations).
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * A tenant has many users.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // ──────────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Return the primary/default branch for this tenant.
     */
    public function primaryBranch(): ?Branch
    {
        return $this->branches()->where('is_primary', true)->first()
            ?? $this->branches()->where('status', 'active')->first();
    }

    /**
     * Check whether the tenant's subscription or trial is still valid.
     */
    public function isActive(): bool
    {
        if ($this->status === 'active') {
            return $this->subscription_ends_at === null
                || $this->subscription_ends_at->isFuture();
        }

        if ($this->status === 'trial') {
            return $this->trial_ends_at === null
                || $this->trial_ends_at->isFuture();
        }

        return false;
    }

    // ──────────────────────────────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['active', 'trial']);
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }
}
