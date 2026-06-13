<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireSuperadmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->guest(route('login'));
        }

        if (!$request->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized. Access is restricted to system administrators.');
        }

        return $next($request);
    }
}
