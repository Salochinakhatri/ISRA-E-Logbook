<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\TrainingEntry;
use App\Models\User;
use App\Models\UserType;
use App\Services\TenantManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MultiTenantTest extends TestCase
{
    use DatabaseTransactions;
    public function test_unknown_domain_returns_404(): void
    {
        $response = $this->get('http://unknown-domain.test/');
        $response->assertStatus(404);
    }

    public function test_cpsp1_domain_resolves_tenant_and_shows_login(): void
    {
        $response = $this->get('http://cpsp1.test/');
        $response->assertStatus(200);
        $response->assertSee('CPSP ePortal');
        $response->assertSee('LOGIN TO YOUR ACCOUNT');
    }

    public function test_cpsp2_domain_resolves_tenant_and_shows_login(): void
    {
        $response = $this->get('http://cpsp2.test/');
        $response->assertStatus(200);
        $response->assertSee('CPSP ePortal');
        $response->assertSee('LOGIN TO YOUR ACCOUNT');
    }

    public function test_trainee_login_success_and_redirection(): void
    {
        $cpsp1 = Tenant::where('domain', 'cpsp1.test')->firstOrFail();
        $traineeType = UserType::where('tenant_id', $cpsp1->id)->where('name', 'Trainee')->firstOrFail();

        $response = $this->post('http://cpsp1.test/login', [
            'user_type_id' => $traineeType->id,
            'username'     => '2022-23675',
            'password'     => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('user_id');
        $response->assertSessionHas('username', '2022-23675');
    }

    public function test_invalid_credentials_fail(): void
    {
        $cpsp1 = Tenant::where('domain', 'cpsp1.test')->firstOrFail();
        $traineeType = UserType::where('tenant_id', $cpsp1->id)->where('name', 'Trainee')->firstOrFail();

        $response = $this->post('http://cpsp1.test/login', [
            'user_type_id' => $traineeType->id,
            'username'     => '2022-23675',
            'password'     => 'wrongpassword',
        ]);

        $response->assertRedirect('http://cpsp1.test');
        $response->assertSessionHas('login_error');
    }

    public function test_unauthenticated_user_redirected_from_dashboard(): void
    {
        $response = $this->get('http://cpsp1.test/dashboard');
        $response->assertRedirect('http://cpsp1.test');
    }

    public function test_authenticated_trainee_accesses_dashboard(): void
    {
        $cpsp1 = Tenant::where('domain', 'cpsp1.test')->firstOrFail();
        $user  = User::withoutGlobalScope('tenant')
            ->where('tenant_id', $cpsp1->id)
            ->where('username', '2022-23675')
            ->firstOrFail();

        $response = $this->withSession([
            'user_id'      => $user->id,
            'user_type_id' => $user->user_type_id,
            'username'     => $user->username,
            'email'        => $user->email,
            'user_type'    => 'Trainee',
        ])->get('http://cpsp1.test/dashboard');

        $response->assertStatus(200);
        $response->assertSee('INTERNAL MEDICINE');
        $response->assertSee('Isra e-Logbook');
        $response->assertSee('Training');
        $response->assertSee('Rotational');
        $response->assertSee('Journal');
    }

    public function test_tenant_scoping_training_entry_isolation(): void
    {
        $tenantManager = app(TenantManager::class);

        $cpsp1 = Tenant::where('domain', 'cpsp1.test')->firstOrFail();
        $cpsp2 = Tenant::where('domain', 'cpsp2.test')->firstOrFail();

        $user1 = User::withoutGlobalScope('tenant')->where('tenant_id', $cpsp1->id)->firstOrFail();
        $user2 = User::withoutGlobalScope('tenant')->where('tenant_id', $cpsp2->id)->firstOrFail();

        // Under Tenant 1
        $tenantManager->set($cpsp1);
        $entry1 = TrainingEntry::create([
            'user_id'           => $user1->id,
            'entry_type'        => 'training',
            'form_type'         => '1',
            'hospt_reg_no'      => 'REG-TENANT-1-ISOLATION',
            'pt_diagnosis'      => 'Appendicitis',
            'brief_desc'        => 'Laparoscopic appendectomy performed',
            'level_id'          => '3',
            'outcome_id'        => '8',
            'under_sup_name'    => 'Dr. Supervisor',
            'pt_gender'         => 'Male',
            'pt_age'            => '35',
            'pt_age_type'       => 'Year[s]',
            'std_post'          => 'No',
            'entry_status'      => 'Draft',
        ]);

        $this->assertEquals($cpsp1->id, $entry1->tenant_id);

        // Tenant 1 should see entry1
        $this->assertNotNull(TrainingEntry::where('hospt_reg_no', 'REG-TENANT-1-ISOLATION')->first());

        // Switch to Tenant 2
        $tenantManager->set($cpsp2);
        $this->assertNull(TrainingEntry::where('hospt_reg_no', 'REG-TENANT-1-ISOLATION')->first());

        // Clean up test entry
        $entry1->delete();
    }

    public function test_program_badges_for_urogyn_and_obgyn(): void
    {
        $cpsp2 = Tenant::where('domain', 'cpsp2.test')->firstOrFail();
        $user2  = User::withoutGlobalScope('tenant')
            ->where('tenant_id', $cpsp2->id)
            ->where('username', '2022-23675')
            ->firstOrFail();

        $responseMs = $this->withSession([
            'user_id'      => $user2->id,
            'user_type_id' => $user2->user_type_id,
            'username'     => $user2->username,
            'email'        => $user2->email,
            'user_type'    => 'Trainee',
        ])->get('http://cpsp2.test/training?program=ms');

        $responseMs->assertStatus(200);
        $responseMs->assertSee('MS (GYNAECOLOGY & OBSTETRICS)', false);

        $responseDgo = $this->withSession([
            'user_id'      => $user2->id,
            'user_type_id' => $user2->user_type_id,
            'username'     => $user2->username,
            'email'        => $user2->email,
            'user_type'    => 'Trainee',
        ])->get('http://cpsp2.test/training?program=dgo');

        $responseDgo->assertStatus(200);
        $responseDgo->assertSee('DGO (GYNAECOLOGY & OBSTETRICS)', false);

        $cpsp1 = Tenant::where('domain', 'cpsp1.test')->firstOrFail();
        $user1  = User::withoutGlobalScope('tenant')
            ->where('tenant_id', $cpsp1->id)
            ->where('username', '2022-23675')
            ->firstOrFail();

        $responseMd = $this->withSession([
            'user_id'      => $user1->id,
            'user_type_id' => $user1->user_type_id,
            'username'     => $user1->username,
            'email'        => $user1->email,
            'user_type'    => 'Trainee',
        ])->get('http://cpsp1.test/training?program=md');

        $responseMd->assertStatus(200);
        $responseMd->assertSee('MD (INTERNAL MEDICINE)');
    }

    public function test_trainee_can_create_and_list_training_entry(): void
    {
        $cpsp1 = Tenant::where('domain', 'cpsp1.test')->firstOrFail();
        $user  = User::withoutGlobalScope('tenant')->where('tenant_id', $cpsp1->id)->where('username', '2022-23675')->firstOrFail();

        $session = [
            'user_id'      => $user->id,
            'user_type_id' => $user->user_type_id,
            'username'     => $user->username,
            'email'        => $user->email,
            'user_type'    => 'Trainee',
        ];

        // Access create form
        $this->withSession($session)->get('http://cpsp1.test/training/create')->assertStatus(200);

        // Store new entry
        $postResponse = $this->withSession($session)->post('http://cpsp1.test/training', [
            'form_type'         => '1',
            'hospt_reg_no'      => 'REG-AUTO-TEST-001',
            'date_of_admission' => '15-08-2026',
            'pt_gender'         => 'Male',
            'pt_age'            => '28',
            'pt_age_type'       => 'Year[s]',
            'pt_diagnosis'      => 'Acute Appendicitis',
            'under_sup_name'    => 'Dr. Supervisor Demo',
            'level_id'          => '3',
            'outcome_id'        => '8',
            'brief_desc'        => 'Successful procedure with standard protocol.',
            'std_post'          => 'No',
        ]);

        $postResponse->assertRedirect('http://cpsp1.test/training');

        // Check it appears in listing
        $listResponse = $this->withSession($session)->get('http://cpsp1.test/training');
        $listResponse->assertStatus(200);
        $listResponse->assertSee('REG-AUTO-TEST-001');
        $listResponse->assertSee('Acute Appendicitis');
    }

    public function test_journal_entry_creation_and_listing(): void
    {
        $cpsp1 = Tenant::where('domain', 'cpsp1.test')->firstOrFail();
        $user  = User::withoutGlobalScope('tenant')->where('tenant_id', $cpsp1->id)->where('username', '2022-23675')->firstOrFail();

        $session = [
            'user_id'      => $user->id,
            'user_type_id' => $user->user_type_id,
            'username'     => $user->username,
            'email'        => $user->email,
            'user_type'    => 'Trainee',
        ];

        $postResponse = $this->withSession($session)->post('http://cpsp1.test/journal', [
            'date_of_diss'    => '20-08-2026',
            'fac_by'          => 'Dr. Facilitator',
            'ref_of_art_disc' => 'NEJM Article On Clinical Trials 2026',
            'std_post'        => 'No',
        ]);

        $postResponse->assertRedirect('http://cpsp1.test/journal');

        $listResponse = $this->withSession($session)->get('http://cpsp1.test/journal');
        $listResponse->assertStatus(200);
        $listResponse->assertSee('Dr. Facilitator');
        $listResponse->assertSee('NEJM Article On Clinical Trials 2026');
    }

    public function test_suggestions_submission_and_listing(): void
    {
        $cpsp2 = Tenant::where('domain', 'cpsp2.test')->firstOrFail();
        $user  = User::withoutGlobalScope('tenant')->where('tenant_id', $cpsp2->id)->where('username', '2022-23675')->firstOrFail();

        $session = [
            'user_id'      => $user->id,
            'user_type_id' => $user->user_type_id,
            'username'     => $user->username,
            'email'        => $user->email,
            'user_type'    => 'Trainee',
        ];

        $postResponse = $this->withSession($session)->post('http://cpsp2.test/suggestions', [
            'suggestion_text' => 'Please add an export feature for weekly logs.',
        ]);

        $postResponse->assertRedirect('http://cpsp2.test/suggestions');

        $listResponse = $this->withSession($session)->get('http://cpsp2.test/suggestions');
        $listResponse->assertStatus(200);
        $listResponse->assertSee('Please add an export feature for weekly logs.');
    }

    public function test_supervisor_sees_supervisor_dashboard(): void
    {
        $cpsp1 = Tenant::where('domain', 'cpsp1.test')->firstOrFail();
        $supervisor = User::withoutGlobalScope('tenant')
            ->where('tenant_id', $cpsp1->id)
            ->where('username', 'supervisor01')
            ->firstOrFail();

        $response = $this->withSession([
            'user_id'      => $supervisor->id,
            'user_type_id' => $supervisor->user_type_id,
            'username'     => $supervisor->username,
            'email'        => $supervisor->email,
            'user_type'    => 'Supervisor',
        ])->get('http://cpsp1.test/dashboard');

        $response->assertStatus(200);
        $response->assertSee('CPSP e-Logbook');
        $response->assertSee('Current Trainees - Awaiting Approval Entries');
        $response->assertSee('supervisor01');
    }

    public function test_logout_invalidates_session(): void
    {
        $response = $this->withSession([
            'user_id'   => 1,
            'username'  => '2022-23675',
            'user_type' => 'Trainee',
        ])->post('http://cpsp1.test/logout');

        $response->assertRedirect('http://cpsp1.test');
        $response->assertSessionMissing('user_id');
    }

    public function test_switching_tenant_does_not_404(): void
    {
        $cpsp1 = Tenant::where('domain', 'cpsp1.test')->firstOrFail();
        $user1 = User::withoutGlobalScope('tenant')->where('tenant_id', $cpsp1->id)->where('username', '2022-23675')->firstOrFail();

        // User logged into cpsp1 then accesses cpsp2 dashboard
        $response = $this->withSession([
            'user_id'   => $user1->id,
            'tenant_id' => $user1->tenant_id,
            'username'  => $user1->username,
            'user_type' => 'Trainee',
        ])->get('http://cpsp2.test/dashboard');

        // Should gracefully redirect to login for cpsp2, NEVER 404
        $response->assertRedirect('http://cpsp2.test');
    }

    public function test_search_with_empty_filters_completes_successfully(): void
    {
        $cpsp2 = Tenant::where('domain', 'cpsp2.test')->firstOrFail();
        $user2 = User::withoutGlobalScope('tenant')->where('tenant_id', $cpsp2->id)->where('username', '2022-23675')->firstOrFail();

        $response = $this->withSession([
            'user_id'   => $user2->id,
            'tenant_id' => $user2->tenant_id,
            'username'  => $user2->username,
            'user_type' => 'Trainee',
        ])->get('http://cpsp2.test/training?program=ms&f_status=&f_level=&f_post_from=&f_post_to=&f_adm_from=&f_adm_to=&f_reg=');

        $response->assertStatus(200);
        $response->assertSee('Training');
    }

    public function test_supervisor_can_view_and_approve_trainee_entries(): void
    {
        $cpsp1 = Tenant::where('domain', 'cpsp1.test')->firstOrFail();
        $trainee = User::withoutGlobalScope('tenant')->where('tenant_id', $cpsp1->id)->where('username', '2022-23675')->firstOrFail();
        $supervisor = User::withoutGlobalScope('tenant')->where('tenant_id', $cpsp1->id)->where('username', 'supervisor01')->firstOrFail();

        // Create an entry awaiting approval
        $entry = TrainingEntry::withoutGlobalScope('tenant')->create([
            'tenant_id'         => $cpsp1->id,
            'user_id'           => $trainee->id,
            'entry_type'        => 'training',
            'form_type'         => '1',
            'hospt_reg_no'      => 'REG-APPR-TEST-001',
            'pt_diagnosis'      => 'Acute Cholecystitis',
            'brief_desc'        => 'Laparoscopic cholecystectomy performed uneventfully',
            'level_id'          => '3',
            'outcome_id'        => '8',
            'under_sup_name'    => 'Dr. Supervisor Demo',
            'pt_gender'         => 'Female',
            'pt_age'            => '42',
            'pt_age_type'       => 'Year[s]',
            'std_post'          => 'Yes',
            'entry_status'      => 'Awaiting Approval',
        ]);

        // Supervisor visits /supervisor/entries
        $response = $this->withSession([
            'user_id'      => $supervisor->id,
            'tenant_id'    => $supervisor->tenant_id,
            'user_type_id' => $supervisor->user_type_id,
            'username'     => $supervisor->username,
            'user_type'    => 'Supervisor',
        ])->get('http://cpsp1.test/supervisor/entries');

        $response->assertStatus(200);
        $response->assertSee('Acute Cholecystitis');
        $response->assertSee('Awaiting Approval');

        // Supervisor approves the entry
        $approveResponse = $this->withSession([
            'user_id'      => $supervisor->id,
            'tenant_id'    => $supervisor->tenant_id,
            'user_type_id' => $supervisor->user_type_id,
            'username'     => $supervisor->username,
            'user_type'    => 'Supervisor',
        ])->post("http://cpsp1.test/supervisor/entries/training/{$entry->id}/status", [
            'status'             => 'Approved',
            'supervisor_remarks' => 'Well documented procedure and good technique.',
        ]);

        $approveResponse->assertRedirect();
        $entry->refresh();
        $this->assertEquals('Approved', $entry->entry_status);
        $this->assertNotNull($entry->approved_at);
        $this->assertEquals($supervisor->id, $entry->approved_by);
        $this->assertEquals('Well documented procedure and good technique.', $entry->supervisor_remarks);

        // Clean up
        $entry->delete();
    }
}
