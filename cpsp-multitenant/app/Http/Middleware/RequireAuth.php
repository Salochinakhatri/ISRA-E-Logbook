<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Validates that the current session belongs to an authenticated user
 * of the currently active tenant.
 *
 * If the user switched tenants or the user is not found in the current tenant,
 * the session is cleared and the user is redirected to the login page.
 */
class RequireAuth
{
    public function __construct(private TenantManager $tenantManager) {}

    public function handle(Request $request, Closure $next): Response
    {
        $userId = session('user_id');
        if (! $userId) {
            return redirect()->route('login');
        }

        $tenant = $this->tenantManager->get();
        $sessionTenantId = session('tenant_id');

        // Check if session belongs to another tenant
        if ($tenant && $sessionTenantId && (int) $sessionTenantId !== (int) $tenant->id) {
            session()->forget(['user_id', 'user_type_id', 'username', 'email', 'user_type', 'tenant_id']);
            return redirect()->route('login');
        }

        // Verify the user exists for the current active tenant
        $user = User::find($userId);
        if (! $user) {
            session()->forget(['user_id', 'user_type_id', 'username', 'email', 'user_type', 'tenant_id']);
            return redirect()->route('login');
        }

        return $next($request);
    }
}
