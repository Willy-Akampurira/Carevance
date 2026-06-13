<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckTenantActive
 *
 * Runs after ResolveTenant has bound 'currentTenant' in the service container.
 * Aborts with a branded error page when the clinic workspace is suspended,
 * cancelled, or has an expired trial/subscription.
 *
 * Bypasses:
 *   - No tenant context (root / superadmin domain) → always passes through.
 *   - Superadmin users → always passes through regardless of tenant status.
 */
class CheckTenantActive
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only check when a tenant is actually resolved
        if (!app()->bound('currentTenant')) {
            return $next($request);
        }

        // Superadmins can always access any tenant context (for debugging / provisioning)
        if (auth()->check() && auth()->user()->hasRole('superadmin')) {
            return $next($request);
        }

        $tenant = app('currentTenant');

        if (!$tenant->isActive()) {
            $status = $tenant->status;

            return response()->view('errors.tenant-inactive', compact('tenant', 'status'), 403);
        }

        return $next($request);
    }
}
