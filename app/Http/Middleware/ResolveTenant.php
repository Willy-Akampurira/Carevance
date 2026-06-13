<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use App\Models\Branch;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $baseHost = parse_url(config('app.url'), PHP_URL_HOST);

        // If the host is exactly the base host or www.baseHost, it's root platform routing. Skip scoping.
        if ($host === $baseHost || $host === 'www.' . $baseHost) {
            return $next($request);
        }

        // Extract subdomain relative to the base host
        $subdomain = null;
        if (str_ends_with($host, '.' . $baseHost)) {
            $subdomain = substr($host, 0, -strlen('.' . $baseHost));
        } else {
            $hostParts = explode('.', $host);
            if (count($hostParts) > 2) {
                $subdomain = $hostParts[0];
            }
        }

        // 1. Skip scoping for root-level platform landing pages or superadmin routing
        if (!$subdomain || in_array($subdomain, ['www', 'superadmin', 'landing'])) {
            return $next($request);
        }

        // 2. Query the tenant record matching the URL subdomain prefix
        $tenant = Tenant::where('subdomain', $subdomain)->first();

        if (!$tenant) {
            abort(404, "Clinic workspace '{$subdomain}' does not exist.");
        }

        // 3. Bind the active tenant into Laravel's service container
        app()->instance('currentTenant', $tenant);

        // 4. Resolve the branch context
        $branch = null;
        if (auth()->check() && auth()->user()->branch_id) {
            $branch = Branch::where('tenant_id', $tenant->id)
                ->find(auth()->user()->branch_id);
        }

        if (!$branch) {
            $branch = Branch::where('tenant_id', $tenant->id)->first();
        }

        if ($branch) {
            app()->instance('currentBranch', $branch);
        }

        return $next($request);
    }
}