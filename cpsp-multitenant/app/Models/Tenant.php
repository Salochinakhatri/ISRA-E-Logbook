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
        'specialty_title',
        'programs',
        'competency_type',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'programs'  => 'array',
        'settings'  => 'array',
    ];

    // ─── Accessors ────────────────────────────────────────────────────────────

    /**
     * Return a public URL to the tenant's logo asset.
     * Falls back to a default asset or placeholder.
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo && file_exists(public_path($this->logo))) {
            return asset($this->logo);
        }

        return asset('assets/images/logo.png');
    }

    /**
     * Return the tenant's specialty title, dynamically adapting if a program is active.
     */
    public function getSpecialtyTitle(?string $program = null): string
    {
        if ($program !== null && $program !== '') {
            $meta = self::getProgramMeta($program);
            if (! empty($meta['department'])) {
                return strtoupper($meta['department']);
            }
        }

        if (! empty($this->specialty_title)) {
            return strtoupper($this->specialty_title);
        }

        return 'INTERNAL MEDICINE';
    }

    /**
     * Return associative array of [code => label] for supported programs.
     *
     * @return array<string, string>
     */
    public function getAvailablePrograms(): array
    {
        if (! empty($this->programs) && is_array($this->programs)) {
            $normalized = [];
            foreach ($this->programs as $key => $val) {
                if (is_int($key)) {
                    $normalized[(string) $val] = (string) $val;
                } else {
                    $normalized[(string) $key] = (string) $val;
                }
            }
            return $normalized;
        }

        // Fallback based on specialty/domain
        if (str_contains(strtolower($this->name ?? ''), 'gyn') || str_contains(strtolower($this->domain ?? ''), 'cpsp2')) {
            return [
                'MS'  => 'MS (Gynaecology & Obstetrics)',
                'DGO' => 'DGO (Gynaecology & Obstetrics)',
            ];
        }

        return [
            'MD'  => 'MD (Internal Medicine)',
            'IMM' => 'IMM (Internal Medicine)',
        ];
    }

    /**
     * Return the competency catalog type ('obgyn', 'fcps_imm', etc.).
     */
    public function getCompetencyType(): string
    {
        if (! empty($this->competency_type)) {
            return $this->competency_type;
        }

        if (str_contains(strtolower($this->name ?? ''), 'gyn') || str_contains(strtolower($this->domain ?? ''), 'cpsp2')) {
            return 'obgyn';
        }

        return 'fcps_imm';
    }

    /**
     * Get dynamic metadata (badge class, icon, label, department) for any program code.
     *
     * @return array{code: string, label: string, department: string, icon: string, badge_class: string}
     */
    public static function getProgramMeta(string $program): array
    {
        $prog = strtolower(trim($program));

        $registry = [
            'ms' => [
                'code'        => 'MS',
                'label'       => 'MS (GYNAECOLOGY & OBSTETRICS)',
                'department'  => 'Gynaecology & Obstetrics',
                'icon'        => 'fa-solid fa-stethoscope',
                'badge_class' => 'badge--obgyn',
            ],
            'dgo' => [
                'code'        => 'DGO',
                'label'       => 'DGO (GYNAECOLOGY & OBSTETRICS)',
                'department'  => 'Gynaecology & Obstetrics',
                'icon'        => 'fa-solid fa-heart-pulse',
                'badge_class' => 'badge--obgyn',
            ],
            'md' => [
                'code'        => 'MD',
                'label'       => 'MD (INTERNAL MEDICINE)',
                'department'  => 'Internal Medicine',
                'icon'        => 'fa-solid fa-stethoscope',
                'badge_class' => 'badge--blue',
            ],
            'imm' => [
                'code'        => 'IMM',
                'label'       => 'IMM (INTERNAL MEDICINE)',
                'department'  => 'Internal Medicine',
                'icon'        => 'fa-solid fa-heart-pulse',
                'badge_class' => 'badge--obgyn',
            ],
            'obgyn' => [
                'code'        => 'OBGYN',
                'label'       => 'OBSTETRICS AND GYNAECOLOGY',
                'department'  => 'Gynaecology & Obstetrics',
                'icon'        => 'fa-solid fa-heart-pulse',
                'badge_class' => 'badge--obgyn',
            ],
        ];

        return $registry[$prog] ?? [
            'code'        => strtoupper($program),
            'label'       => strtoupper($program),
            'department'  => '',
            'icon'        => 'fa-solid fa-award',
            'badge_class' => 'badge--blue',
        ];
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

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }
}
