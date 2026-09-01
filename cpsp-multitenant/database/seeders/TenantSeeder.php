<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\UserType;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // ── Tenant 1: CPSP ePortal (Internal Medicine / FCPS) ────────────────
        $cpsp1 = Tenant::firstOrCreate(
            ['domain' => 'cpsp1.test'],
            [
                'name'        => 'CPSP ePortal – Internal Medicine',
                'logo'        => 'assets/tenants/cpsp1/logo.png',
                'theme_color' => '#2E7D32',   // dark green
                'is_active'   => true,
            ]
        );

        foreach (['Trainee', 'Supervisor', 'Fellow'] as $typeName) {
            UserType::firstOrCreate([
                'tenant_id' => $cpsp1->id,
                'name'      => $typeName,
            ]);
        }

        // ── Tenant 2: Gynae & OBS Portal (UroGyn / ObGyn) ───────────────────
        $cpsp2 = Tenant::firstOrCreate(
            ['domain' => 'cpsp2.test'],
            [
                'name'        => 'Gynae & OBS Portal – Urogynaecology / Obstetrics',
                'logo'        => 'assets/tenants/cpsp2/logo.png',
                'theme_color' => '#28a745',   // green
                'is_active'   => true,
            ]
        );

        foreach (['Trainee', 'Supervisor', 'Fellow'] as $typeName) {
            UserType::firstOrCreate([
                'tenant_id' => $cpsp2->id,
                'name'      => $typeName,
            ]);
        }
    }
}
