<?php

namespace App\Traits;

use App\Scopes\TenantScope;

/**
 * BelongsToTenant
 *
 * Apply this trait to every Eloquent model that must be isolated by tenant and branch.
 *
 * What it does:
 *   1. Registers the TenantScope global scope → all SELECT queries are automatically
 *      filtered by the current tenant_id (and branch_id when available).
 *   2. Hooks into the `creating` Eloquent event → auto-fills tenant_id and branch_id
 *      on every new record so controllers never need to set them manually.
 *
 * Usage:
 *   class Patient extends Model {
 *       use BelongsToTenant;
 *       ...
 *   }
 *
 * Bypassing the scope (e.g., for super-admin cross-tenant queries):
 *   Patient::withoutGlobalScope(TenantScope::class)->get();
 *   Patient::withoutGlobalScopes()->get();
 */
trait BelongsToTenant
{
    /**
     * Boot the trait — registers the global scope and the creating event listener.
     * Laravel automatically calls bootXxx() for any trait used by a Model.
     */
    protected static function bootBelongsToTenant(): void
    {
        // ── 1. Register the global query scope ────────────────────────────────
        static::addGlobalScope(new TenantScope());

        // ── 2. Auto-fill tenant_id and branch_id on record creation ───────────
        static::creating(function ($model) {
            // Only fill if the column is not already set (allows explicit overrides)
            if (empty($model->tenant_id)) {
                $tenantId = static::resolveCurrentTenantId();

                if ($tenantId !== null) {
                    $model->tenant_id = $tenantId;
                }
            }

            if (empty($model->branch_id)) {
                $branchId = static::resolveCurrentBranchId();

                if ($branchId !== null) {
                    $model->branch_id = $branchId;
                }
            }
        });
    }

    /**
     * Resolve tenant_id from container → session → null.
     */
    protected static function resolveCurrentTenantId(): ?int
    {
        if (app()->bound('currentTenant')) {
            return (int) app('currentTenant')->id;
        }

        if (session()->has('tenant_id')) {
            return (int) session('tenant_id');
        }

        return null;
    }

    /**
     * Resolve branch_id from container → session → null.
     */
    protected static function resolveCurrentBranchId(): ?int
    {
        if (app()->bound('currentBranch')) {
            return (int) app('currentBranch')->id;
        }

        if (session()->has('branch_id')) {
            return (int) session('branch_id');
        }

        return null;
    }

    // ──────────────────────────────────────────────────────────────────────
    // Convenience relationship helpers (available on every tenant-scoped model)
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Get the tenant (Clinic) this record belongs to.
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }

    /**
     * Get the branch this record belongs to.
     */
    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class);
    }
}
