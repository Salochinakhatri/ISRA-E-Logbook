<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
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
            ['username' => '2022-23675',   'email' => 'trainee.demo@cpsp1.local',     'type' => 'Trainee',    'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Trainee Demo',    'phone' => '+92-300-0000000', 'bio' => 'Trainee in Internal Medicine.', 'supervisor' => 'supervisor01'],
            ['username' => 'supervisor01', 'email' => 'supervisor.demo@cpsp1.local', 'type' => 'Supervisor', 'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Supervisor Demo', 'phone' => '+92-300-1111111', 'bio' => 'Supervisor 1'],
            ['username' => 'supervisor02', 'email' => 'supervisor2@cpsp1.local',     'type' => 'Supervisor', 'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Supervisor Two',  'phone' => '+92-300-3333333', 'bio' => 'Supervisor 2'],
            ['username' => 'fellow01',     'email' => 'fellow.demo@cpsp1.local',     'type' => 'Fellow',     'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Fellow Demo',     'phone' => '+92-300-2222222', 'bio' => ''],
            ['username' => '2011-2686',     'email' => 'salar.trainee@cpsp1.local',   'type' => 'Trainee',    'hash' => self::SALAR_HASH, 'full_name' => 'Dr. Salar',           'phone' => '',                'bio' => '', 'supervisor' => 'supervisor01'],
            ['username' => 'trainee1',     'email' => 'trainee1@cpsp1.local',       'type' => 'Trainee',    'hash' => self::DEMO_HASH,  'full_name' => 'Trainee 1',           'phone' => '+92-300-1000001', 'bio' => 'Trainee Resident in Medicine (Supervised by Dr. Supervisor Demo).', 'supervisor' => 'supervisor01'],
            ['username' => 'trainee2',     'email' => 'trainee2@cpsp1.local',       'type' => 'Trainee',    'hash' => self::DEMO_HASH,  'full_name' => 'Trainee 2',           'phone' => '+92-300-1000002', 'bio' => 'Trainee Resident in Medicine (Supervised by Dr. Supervisor Two).', 'supervisor' => 'supervisor02'],
            // Supervisors Asghar & Hafiz with their assigned trainees
            ['username' => 'asghar',       'email' => 'asghar@cpsp1.local',           'type' => 'Supervisor', 'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Asghar',          'phone' => '+92-300-1112233', 'bio' => 'Consultant & Clinical Supervisor'],
            ['username' => 'hafiz',        'email' => 'hafiz@cpsp1.local',            'type' => 'Supervisor', 'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Hafiz',           'phone' => '+92-300-1114455', 'bio' => 'Consultant & Clinical Supervisor'],
            ['username' => 'salochina',    'email' => 'salochina@cpsp1.local',        'type' => 'Trainee',    'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Salochina',       'phone' => '+92-300-2220001', 'bio' => 'Trainee Resident in Medicine (Supervised by Dr. Asghar)', 'supervisor' => 'asghar'],
            ['username' => 'maria',        'email' => 'maria@cpsp1.local',            'type' => 'Trainee',    'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Maria',           'phone' => '+92-300-2220002', 'bio' => 'Trainee Resident in Medicine (Supervised by Dr. Asghar)', 'supervisor' => 'asghar'],
            ['username' => 'amarta',       'email' => 'amarta@cpsp1.local',           'type' => 'Trainee',    'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Amarta',          'phone' => '+92-300-2220003', 'bio' => 'Trainee Resident in Medicine (Supervised by Dr. Hafiz)', 'supervisor' => 'hafiz'],
            ['username' => 'iqra',         'email' => 'iqra@cpsp1.local',             'type' => 'Trainee',    'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Iqra',            'phone' => '+92-300-2220004', 'bio' => 'Trainee Resident in Medicine (Supervised by Dr. Hafiz)', 'supervisor' => 'hafiz'],
        ]);

        // ── Users for cpsp2.test ─────────────────────────────────────────────
        $cpsp2 = Tenant::where('domain', 'cpsp2.test')->firstOrFail();
        $tenantManager->set($cpsp2);

        $this->seedUsersForTenant($cpsp2, [
            ['username' => '2022-23675',   'email' => 'trainee.demo@cpsp2.local',     'type' => 'Trainee',    'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Trainee Demo',    'phone' => '+92-300-0000000', 'bio' => 'Trainee in Obstetrics & Gynaecology.', 'supervisor' => 'supervisor01'],
            ['username' => 'supervisor01', 'email' => 'supervisor.demo@cpsp2.local', 'type' => 'Supervisor', 'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Supervisor Demo', 'phone' => '+92-300-1111111', 'bio' => 'Supervisor 1'],
            ['username' => 'supervisor02', 'email' => 'supervisor2@cpsp2.local',     'type' => 'Supervisor', 'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Supervisor Two',  'phone' => '+92-300-3333333', 'bio' => 'Supervisor 2'],
            ['username' => 'fellow01',     'email' => 'fellow.demo@cpsp2.local',     'type' => 'Fellow',     'hash' => self::DEMO_HASH,  'full_name' => 'Dr. Fellow Demo',     'phone' => '+92-300-2222222', 'bio' => ''],
            ['username' => '2011-2686',     'email' => 'salar.trainee@cpsp2.local',   'type' => 'Trainee',    'hash' => self::SALAR_HASH, 'full_name' => 'Dr. Salar',           'phone' => '',                'bio' => '', 'supervisor' => 'supervisor01'],
            ['username' => 'trainee1',     'email' => 'trainee1@cpsp2.local',       'type' => 'Trainee',    'hash' => self::DEMO_HASH,  'full_name' => 'Trainee 1',           'phone' => '+92-300-1000001', 'bio' => 'Trainee Resident in Obs & Gynae (Supervised by Dr. Supervisor Demo).', 'supervisor' => 'supervisor01'],
            ['username' => 'trainee2',     'email' => 'trainee2@cpsp2.local',       'type' => 'Trainee',    'hash' => self::DEMO_HASH,  'full_name' => 'Trainee 2',           'phone' => '+92-300-1000002', 'bio' => 'Trainee Resident in Obs & Gynae (Supervised by Dr. Supervisor Two).', 'supervisor' => 'supervisor02'],
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

            $role = Role::where('tenant_id', $tenant->id)
                ->where('name', $data['type'])
                ->first();

            $user = User::withoutGlobalScope('tenant')->updateOrCreate(
                ['tenant_id' => $tenant->id, 'username' => $data['username']],
                [
                    'user_type_id' => $userType->id,
                    'role_id'      => $role?->id,
                    'email'        => $data['email'],
                    'password'     => $passwordHash,
                ]
            );

            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }

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

        // Link trainees to their assigned supervisors
        foreach ($users as $data) {
            if (!empty($data['supervisor'])) {
                $trainee = User::withoutGlobalScope('tenant')->where('tenant_id', $tenant->id)->where('username', $data['username'])->first();
                $supervisor = User::withoutGlobalScope('tenant')->where('tenant_id', $tenant->id)->where('username', $data['supervisor'])->first();
                if ($trainee && $supervisor) {
                    $trainee->update(['supervisor_id' => $supervisor->id]);
                    \Illuminate\Support\Facades\DB::table('supervisor_trainee')->updateOrInsert(
                        ['supervisor_id' => $supervisor->id, 'trainee_id' => $trainee->id],
                        ['updated_at' => now(), 'created_at' => now()]
                    );
                }
            }
        }
    }
}
