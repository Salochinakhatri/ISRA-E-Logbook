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
        // ── Tenant 1: ePortal (Internal Medicine / FCPS) ────────────────────
        $cpsp1 = Tenant::updateOrCreate(
            ['domain' => 'cpsp1.test'],
            [
                'name'            => 'ePortal – Internal Medicine',
                'logo'            => 'assets/tenants/cpsp1/logo.png',
                'theme_color'     => '#2E7D32',   // dark green
                'specialty_title' => 'INTERNAL MEDICINE',
                'programs'        => [
                    'MD'  => 'MD (Internal Medicine)',
                    'IMM' => 'IMM (Internal Medicine)',
                ],
                'competency_type' => 'fcps_imm',
                'settings'        => [
                    'portal_title' => 'ePortal – Internal Medicine',
                ],
                'is_active'       => true,
            ]
        );

        foreach (['Trainee', 'Supervisor', 'Fellow'] as $typeName) {
            UserType::firstOrCreate([
                'tenant_id' => $cpsp1->id,
                'name'      => $typeName,
            ]);
        }

        // ── Tenant 2: ePortal (Obstetrics & Gynaecology) ────────────────────
        $cpsp2 = Tenant::updateOrCreate(
            ['domain' => 'cpsp2.test'],
            [
                'name'            => 'ePortal – Obstetrics & Gynaecology',
                'logo'            => 'assets/tenants/cpsp2/logo.png',
                'theme_color'     => '#28a745',   // green
                'specialty_title' => 'GYNAECOLOGY & OBSTETRICS',
                'programs'        => [
                    'MS'  => 'MS (Gynaecology & Obstetrics)',
                    'DGO' => 'DGO (Gynaecology & Obstetrics)',
                ],
                'competency_type' => 'obgyn',
                'settings'        => [
                    'portal_title' => 'ePortal – Obstetrics & Gynaecology',
                ],
                'is_active'       => true,
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
