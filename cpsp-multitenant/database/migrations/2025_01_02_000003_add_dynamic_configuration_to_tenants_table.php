<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('specialty_title', 150)->nullable()->after('name');
            $table->json('programs')->nullable()->after('specialty_title');
            $table->string('competency_type', 50)->nullable()->after('programs');
            $table->json('settings')->nullable()->after('competency_type');
        });

        // Seed initial dynamic configuration for existing tenants
        DB::table('tenants')->where('domain', 'cpsp1.test')->update([
            'specialty_title' => 'INTERNAL MEDICINE',
            'programs'        => json_encode([
                'MD'  => 'MD (Internal Medicine)',
                'IMM' => 'IMM (Internal Medicine)',
            ]),
            'competency_type' => 'fcps_imm',
            'settings'        => json_encode([
                'portal_title' => 'CPSP ePortal – Internal Medicine',
            ]),
        ]);

        DB::table('tenants')->where('domain', 'cpsp2.test')->update([
            'specialty_title' => 'GYNAECOLOGY & OBSTETRICS',
            'programs'        => json_encode([
                'MS'  => 'MS (Gynaecology & Obstetrics)',
                'DGO' => 'DGO (Gynaecology & Obstetrics)',
            ]),
            'competency_type' => 'obgyn',
            'settings'        => json_encode([
                'portal_title' => 'Gynae & OBS Portal',
            ]),
        ]);
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['specialty_title', 'programs', 'competency_type', 'settings']);
        });
    }
};
