<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Services\TenantManager;
use Illuminate\Database\Eloquent\Builder;

/**
 * Automatically scopes all queries to the current tenant
 * and fills tenant_id on creation.
 *
 * Apply this trait to every tenant-scoped Eloquent model.
 */
trait BelongsToTenant
{
    /**
     * Boot the trait — adds global scope and creation listener.
     */
    protected static function bootBelongsToTenant(): void
    {
        // Add a global WHERE tenant_id = ? clause to every query
        static::addGlobalScope('tenant', function (Builder $builder): void {
            $tenantId = app(TenantManager::class)->id();
            if ($tenantId > 0) {
                $builder->where(static::getTableNameForScope() . '.tenant_id', $tenantId);
            }
        });

        // Automatically fill tenant_id on new records
        static::creating(function (self $model): void {
            if (empty($model->tenant_id)) {
                $model->tenant_id = app(TenantManager::class)->id();
            }
        });
    }

    private static function getTableNameForScope(): string
    {
        return (new static())->getTable();
    }
}
