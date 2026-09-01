<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reads the HTTP Host header, resolves the matching Tenant from the database,
 * stores it on TenantManager, and shares it with all Blade views.
 *
 * Returns HTTP 404 when no active tenant matches the domain.
 */
class IdentifyTenant
{
    public function __construct(private TenantManager $tenantManager) {}

    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());

        // Strip www. prefix for flexibility
        $domain = preg_replace('/^www\./', '', $host);

        // 1. Direct domain match (e.g. cpsp1.test, cpsp2.test)
        $tenant = Tenant::where('domain', $domain)
            ->where('is_active', true)
            ->first();

        // 2. Subdomain match (e.g. cpsp1.localhost or cpsp2.localhost)
        if (! $tenant && str_ends_with($domain, '.localhost')) {
            $sub = explode('.', $domain)[0];
            $tenant = Tenant::where('is_active', true)
                ->where(function ($q) use ($sub) {
                    $q->where('domain', $sub)
                      ->orWhere('domain', $sub . '.test');
                })
                ->first();
        }

        // 3. Localhost URL fallback (via session, query, path, or session('tenant_id'))
        if (! $tenant && in_array($domain, ['localhost', '127.0.0.1'])) {
            $requestedTenant = $request->query('tenant') ?? session('dev_tenant');

            // If session('tenant_id') is present, resolve from it
            if (! $requestedTenant && session()->has('tenant_id')) {
                $tenant = Tenant::where('is_active', true)->find(session('tenant_id'));
            }

            if (! $tenant && $requestedTenant) {
                $tenant = Tenant::where('is_active', true)
                    ->where(function ($q) use ($requestedTenant) {
                        $q->where('domain', $requestedTenant)
                          ->orWhere('domain', $requestedTenant . '.test');
                    })
                    ->first();
            }

            // Path-based check e.g. /cpsp1 or /cpsp2
            if (! $tenant) {
                $path = $request->path();
                if (str_starts_with($path, 'cpsp2')) {
                    $tenant = Tenant::where('domain', 'cpsp2.test')->first();
                } elseif (str_starts_with($path, 'cpsp1')) {
                    $tenant = Tenant::where('domain', 'cpsp1.test')->first();
                }
            }

            if ($tenant) {
                session(['dev_tenant' => $tenant->domain]);
            } else {
                $tenant = Tenant::where('domain', 'cpsp1.test')->where('is_active', true)->first();
            }
        }

        if (! $tenant) {
            abort(404, "No active tenant found for domain: {$domain}");
        }

        $this->tenantManager->set($tenant);

        // Share tenant with every Blade view automatically
        View::share('tenant', $tenant);

        return $next($request);
    }
}
