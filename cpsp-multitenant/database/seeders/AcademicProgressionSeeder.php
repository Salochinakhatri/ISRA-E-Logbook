<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TraineeDissertation;
use App\Models\TraineeSynopsis;
use App\Models\TraineeWorkshop;
use App\Models\User;
use Illuminate\Database\Seeder;

class AcademicProgressionSeeder extends Seeder
{
    public function run(): void
    {
        $trainees = User::whereHas('userType', fn($q) => $q->where('name', 'Trainee'))
            ->orWhereHas('roles', fn($q) => $q->where('slug', 'trainee')->orWhere('name', 'Trainee'))
            ->get();

        $defaultWorkshops = [
            ['workshop_name' => 'Basic Surgical Skills (BSS)', 'time_duration' => '3 Days', 'status' => 'Completed', 'status_date' => '2023-04-10'],
            ['workshop_name' => 'Research Methodology, Biostatistics & Medical Writing', 'time_duration' => '4 Days', 'status' => 'Completed', 'status_date' => '2023-06-18'],
            ['workshop_name' => 'Communication Skills', 'time_duration' => '3 Days', 'status' => 'Completed', 'status_date' => '2023-09-05'],
            ['workshop_name' => 'Computer and Internet Applications in Medicine', 'time_duration' => '3 Days', 'status' => 'Completed', 'status_date' => '2023-11-20'],
            ['workshop_name' => 'Advanced Cardiac Life Support (ACLS)', 'time_duration' => '2 Days', 'status' => 'In Progress', 'status_date' => '2024-02-14'],
        ];

        foreach ($trainees as $trainee) {
            // Seed Workshops if none exist
            if (TraineeWorkshop::where('user_id', $trainee->id)->doesntExist()) {
                // Give each trainee 2 to 4 workshops
                $count = ($trainee->id % 2 === 0) ? 3 : 4;
                for ($i = 0; $i < $count; $i++) {
                    $w = $defaultWorkshops[$i];
                    TraineeWorkshop::create([
                        'tenant_id'     => $trainee->tenant_id,
                        'user_id'       => $trainee->id,
                        'workshop_name' => $w['workshop_name'],
                        'time_duration' => $w['time_duration'],
                        'status'        => $w['status'],
                        'status_date'   => $w['status_date'],
                    ]);
                }
            }

            // Seed Synopsis if none exist
            if (TraineeSynopsis::where('user_id', $trainee->id)->doesntExist()) {
                TraineeSynopsis::create([
                    'tenant_id'     => $trainee->tenant_id,
                    'user_id'       => $trainee->id,
                    'synopsis_name' => 'Evaluation of Clinical Efficacy and Outcome Metrics in Post-Operative Management: A Multi-Center Prospective Observational Study',
                    'status'        => 'Approved',
                    'status_date'   => '2023-12-05',
                    'remarks'       => 'Approved by CPSP RTMC Department on initial submission.',
                ]);
            }

            // Seed Dissertation if none exist
            if (TraineeDissertation::where('user_id', $trainee->id)->doesntExist()) {
                TraineeDissertation::create([
                    'tenant_id'          => $trainee->tenant_id,
                    'user_id'            => $trainee->id,
                    'dissertation_title' => 'Evaluation of Clinical Efficacy and Outcome Metrics in Post-Operative Management: A Multi-Center Prospective Observational Study',
                    'application_date'   => '2024-03-01',
                    'status'             => 'Submitted',
                    'status_date'        => '2024-03-15',
                    'remarks'            => 'Dissertation manuscript under supervisory and external assessment.',
                ]);
            }
        }
    }
}
