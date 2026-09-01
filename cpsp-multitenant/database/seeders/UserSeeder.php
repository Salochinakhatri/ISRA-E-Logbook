<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserType;
use App\Services\TenantManager;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Standard demo password: "password"
     */
    private const DEMO_HASH = '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm';
    private const SALAR_HASH = '$2y$10$dbjemfm8coEl/2anUCdwmeLZQnn7KmGMuVRRISEOX9.SKCzAHrjYm';

    public function run(TenantManager $tenantManager): void
    {
        // ── Users for cpsp1.test ─────────────────────────────────────────────
        $cpsp1 = Tenant::where('domain', 'cpsp1.test')->firstOrFail();
        $tenantManager->set($cpsp1);

        $this->seedUsersForTenant($cpsp1, [
            ['username' => '2022-23675', 'email' => 'trainee.demo@cpsp1.local',     'type' => 'Trainee',    'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Trainee Demo',    'phone' => '+92-300-0000000', 'bio' => 'Trainee in Internal Medicine.'],
            ['username' => 'supervisor01', 'email' => 'supervisor.demo@cpsp1.local', 'type' => 'Supervisor', 'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Supervisor Demo', 'phone' => '+92-300-1111111', 'bio' => ''],
            ['username' => 'fellow01',     'email' => 'fellow.demo@cpsp1.local',     'type' => 'Fellow',     'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Fellow Demo',     'phone' => '+92-300-2222222', 'bio' => ''],
            ['username' => '2011-2686',   'email' => 'salar.trainee@cpsp1.local',   'type' => 'Trainee',    'hash' => self::SALAR_HASH, 'full_name' => 'Dr. Salar',           'phone' => '',                'bio' => ''],
        ]);

        // ── Users for cpsp2.test ─────────────────────────────────────────────
        $cpsp2 = Tenant::where('domain', 'cpsp2.test')->firstOrFail();
        $tenantManager->set($cpsp2);

        $this->seedUsersForTenant($cpsp2, [
            ['username' => '2022-23675', 'email' => 'trainee.demo@cpsp2.local',     'type' => 'Trainee',    'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Trainee Demo',    'phone' => '+92-300-0000000', 'bio' => 'Trainee in Obs & Gynae / Urogynaecology.'],
            ['username' => 'supervisor01', 'email' => 'supervisor.demo@cpsp2.local', 'type' => 'Supervisor', 'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Supervisor Demo', 'phone' => '+92-300-1111111', 'bio' => ''],
            ['username' => 'fellow01',     'email' => 'fellow.demo@cpsp2.local',     'type' => 'Fellow',     'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Fellow Demo',     'phone' => '+92-300-2222222', 'bio' => ''],
            ['username' => '2011-2686',   'email' => 'salar.trainee@cpsp2.local',   'type' => 'Trainee',    'hash' => self::SALAR_HASH, 'full_name' => 'Dr. Salar',           'phone' => '',                'bio' => ''],
        ]);
    }

    /** @param array<int, array<string, string>> $users */
    private function seedUsersForTenant(Tenant $tenant, array $users): void
    {
        $passwordHash = password_hash('password', PASSWORD_BCRYPT);

        foreach ($users as $data) {
            $userType = UserType::where('tenant_id', $tenant->id)
                ->where('name', $data['type'])
                ->firstOrFail();

            $user = User::withoutGlobalScope('tenant')->updateOrCreate(
                ['tenant_id' => $tenant->id, 'username' => $data['username']],
                [
                    'user_type_id' => $userType->id,
                    'email'        => $data['email'],
                    'password'     => $passwordHash,
                ]
            );

            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name'     => $data['full_name'],
                    'phone'         => $data['phone'],
                    'bio'           => $data['bio'],
                    'profile_image' => '',
                ]
            );
        }
    }
}
