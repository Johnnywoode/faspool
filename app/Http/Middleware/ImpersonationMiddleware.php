<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImpersonationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('impersonated_by')) {
            // Share impersonation status with all views
            view()->share('isImpersonating', true);
            view()->share('impersonatedBy', session('impersonated_by'));
        }

        return $next($request);
    }
}
