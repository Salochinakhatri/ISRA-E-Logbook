<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'logo',
        'theme_color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Accessors ────────────────────────────────────────────────────────────

    /**
     * Return a public URL to the tenant's logo asset.
     * Falls back to a default SVG placeholder.
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo && file_exists(public_path($this->logo))) {
            return asset($this->logo);
        }

        return asset('assets/tenants/default-logo.png');
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function userTypes(): HasMany
    {
        return $this->hasMany(UserType::class);
    }
}
