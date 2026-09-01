<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;

/**
 * Holds the resolved current Tenant for this HTTP request.
 * Bound as a singleton in the IoC container by TenantServiceProvider.
 */
class TenantManager
{
    private ?Tenant $tenant = null;

    public function set(Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function get(): ?Tenant
    {
        return $this->tenant;
    }

    public function id(): int
    {
        return (int) $this->tenant?->id;
    }

    public function resolved(): bool
    {
        return $this->tenant !== null;
    }
}
