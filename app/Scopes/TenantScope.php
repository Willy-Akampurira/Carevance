<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * TenantScope
 *
 * Automatically restricts all Eloquent queries to the current tenant and branch.
 * This scope is injected by the BelongsToTenant trait and fires on every query.
 *
 * Context resolution priority:
 *   1. app('currentTenant') / app('currentBranch')  — set by ResolveTenant middleware
 *   2. session('tenant_id') / session('branch_id')  — fallback for session-driven context
 *
 * The scope is intentionally a no-op when:
 *   - Running in CLI (artisan commands, migrations, seeders)
 *   - No tenant context is available (e.g., superadmin panel, landing page)
 *   - The model is being created by a seeder/factory (context absent)
 */
class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $tenantId = $this->resolveTenantId();
        $branchId = $this->resolveBranchId();

        // If no tenant context is resolved, skip scoping entirely.
        // This allows the superadmin panel and public routes to work without scoping.
        if ($tenantId === null) {
            return;
        }

        $builder->where($model->getTable() . '.tenant_id', $tenantId);

        // Only scope by branch if a branch context has been resolved
        if ($branchId !== null) {
            $builder->where($model->getTable() . '.branch_id', $branchId);
        }
    }

    /**
     * Resolve the current tenant ID from the service container or session.
     */
    protected function resolveTenantId(): ?int
    {
        // Primary: resolved by ResolveTenant middleware and stored in the container
        if (app()->bound('currentTenant')) {
            return (int) app('currentTenant')->id;
        }

        // Fallback: session (for backward compatibility)
        if (session()->has('tenant_id')) {
            return (int) session('tenant_id');
        }

        return null;
    }

    /**
     * Resolve the current branch ID from the service container or session.
     */
    protected function resolveBranchId(): ?int
    {
        // Primary: resolved by ResolveBranch middleware and stored in the container
        if (app()->bound('currentBranch')) {
            return (int) app('currentBranch')->id;
        }

        // Fallback: session
        if (session()->has('branch_id')) {
            return (int) session('branch_id');
        }

        return null;
    }
}
