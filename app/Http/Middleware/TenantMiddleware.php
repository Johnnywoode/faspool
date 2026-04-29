<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Simple domain-based tenant resolution
        $host = $request->getHost();
        // $host1 = \Illuminate\Support\Facades\Request::getHost();
        // For development, we might use a header or a default tenant if not found
        $tenant = Tenant::where('domain', $host)->first();

        if (!$tenant) {
            // Fallback for development: check for X-Tenant-ID header
            $tenantId = $request->header('X-Tenant-ID');
            if ($tenantId) {
                $tenant = Tenant::find($tenantId);
            }
        }

        if (!$tenant) {
            // If still no tenant, we could default to the first one for dev or abort
            $tenant = Tenant::first();
        }

        if ($tenant) {
            app()->instance('tenant', $tenant);
        }

        return $next($request);
    }
}
