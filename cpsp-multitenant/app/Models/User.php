<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_type_id',
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

    public function isTrainee(): bool
    {
        return strtolower((string) optional($this->userType)->name) === 'trainee';
    }

    public function isSupervisor(): bool
    {
        return strtolower((string) optional($this->userType)->name) === 'supervisor';
    }

    public function verifyPassword(string $plain): bool
    {
        return password_verify($plain, (string) $this->password);
    }
}
