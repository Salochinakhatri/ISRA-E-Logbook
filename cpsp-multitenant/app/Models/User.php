<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_type_id',
        'role_id',
        'username',
        'email',
        'password',
        'remember_token',
    ];

    protected $hidden = ['password', 'remember_token'];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')->withTimestamps();
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function trainingEntries(): HasMany
    {
        return $this->hasMany(TrainingEntry::class);
    }

    public function rotationalEntries(): HasMany
    {
        return $this->hasMany(RotationalEntry::class);
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function presentedEntries(): HasMany
    {
        return $this->hasMany(PresentedEntry::class);
    }

    public function publishedEntries(): HasMany
    {
        return $this->hasMany(PublishedEntry::class);
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Check if user has a specific role by name or slug.
     */
    public function hasRole(string|array $roleNames): bool
    {
        $roleNames = (array) $roleNames;
        $lowerRoles = array_map('strtolower', $roleNames);

        // Check direct role
        if ($this->role) {
            if (in_array(strtolower($this->role->name), $lowerRoles, true) ||
                in_array(strtolower($this->role->slug), $lowerRoles, true)) {
                return true;
            }
        }

        // Check assigned roles through pivot
        if ($this->relationLoaded('roles')) {
            foreach ($this->roles as $r) {
                if (in_array(strtolower($r->name), $lowerRoles, true) ||
                    in_array(strtolower($r->slug), $lowerRoles, true)) {
                    return true;
                }
            }
        } else {
            $matched = $this->roles()
                ->where(function ($q) use ($lowerRoles) {
                    $q->whereIn(DB::raw('LOWER(name)'), $lowerRoles)
                      ->orWhereIn(DB::raw('LOWER(slug)'), $lowerRoles);
                })
                ->exists();
            if ($matched) {
                return true;
            }
        }

        // Fallback to userType for backward compatibility
        return in_array(strtolower((string) optional($this->userType)->name), $lowerRoles, true);
    }

    public function isTrainee(): bool
    {
        return $this->hasRole('trainee');
    }

    public function isSupervisor(): bool
    {
        return $this->hasRole('supervisor');
    }

    public function isFellow(): bool
    {
        return $this->hasRole('fellow');
    }

    public function verifyPassword(string $plain): bool
    {
        return password_verify($plain, (string) $this->password);
    }
}
