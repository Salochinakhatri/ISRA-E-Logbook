<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TrainingEntry;
use App\Models\User;
use App\Services\CompetencyService;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class TrainingController extends Controller
{
    public function __construct(
        private TenantManager $tenantManager,
        private CompetencyService $competencyService,
    ) {}

    public function index(Request $request): View
    {
        $userId  = (int) session('user_id');
        $user    = User::with('userType')->find($userId);
        if (! $user) {
            return redirect()->route('login');
        }
        $program = (string) ($request->input('program') ?? '');

        $query = TrainingEntry::where('user_id', $userId);

        if ($program !== '') {
            $query->where('program', $program);
        }

        // Filters
        $status  = $request->input('f_status', '');
        $level   = $request->input('f_level', '');
        $reg     = $request->input('f_reg', '');

        if ($status !== '') {
            $query->where('entry_status', $status);
        }
        if ($level !== '') {
            $query->where('level_id', $level);
        }
        if ($reg !== '') {
            $query->where('hospt_reg_no', 'like', "%{$reg}%");
        }

        if ($from = $this->parseBritishDate($request->input('f_post_from', ''))) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $this->parseBritishDate($request->input('f_post_to', ''))) {
            $query->whereDate('created_at', '<=', $to);
        }
        if ($aFrom = $this->parseBritishDate($request->input('f_adm_from', ''))) {
            $query->whereDate('date_of_admission', '>=', $aFrom);
        }
        if ($aTo = $this->parseBritishDate($request->input('f_adm_to', ''))) {
            $query->whereDate('date_of_admission', '<=', $aTo);
        }

        $entries  = $query->latest('created_at')->get();
        $compMap  = $this->competencyService->labelMap($program);

        $lastEntry      = TrainingEntry::where('user_id', $userId)->latest('created_at')->first();
        $lastEntryLabel = $lastEntry ? $lastEntry->created_at?->format('F j, Y') : null;

        $filters = compact('status', 'level', 'reg') + [
            'post_from' => $request->input('f_post_from', ''),
            'post_to'   => $request->input('f_post_to', ''),
            'adm_from'  => $request->input('f_adm_from', ''),
            'adm_to'    => $request->input('f_adm_to', ''),
        ];

        return view('training.index', compact('entries', 'user', 'program', 'compMap', 'lastEntryLabel', 'filters'));
    }

    public function create(Request $request): View
    {
        $program   = (string) ($request->input('program') ?? '');
        $compTree  = $this->competencyService->treeData($program);
        $formOld   = array_merge(session()->pull('form_old', []), session()->getOldInput());
        $formErrors = session()->pull('form_errors', []);

        $tenant            = $this->tenantManager->get();
        $availablePrograms = $tenant ? $tenant->getAvailablePrograms() : ['MD' => 'MD', 'IMM' => 'IMM'];
        $formTypes         = \App\Services\LookupService::formTypes();
        $levels            = \App\Services\LookupService::levels();
        $outcomes          = \App\Services\LookupService::outcomes();
        $genders           = \App\Services\LookupService::genders();
        $ageUnits          = \App\Services\LookupService::ageUnits();

        $userId = (int) session('user_id');
        $user   = User::with(['profile', 'supervisor.profile'])->find($userId);

        $assignedSupervisor = $user?->supervisor;
        if (! $assignedSupervisor) {
            $pivotSupId = DB::table('supervisor_trainee')->where('trainee_id', $userId)->value('supervisor_id');
            if ($pivotSupId) {
                $assignedSupervisor = User::with('profile')->find($pivotSupId);
            }
        }

        $supervisors = User::where(function ($q) {
            $q->whereHas('userType', fn($sub) => $sub->where('name', 'Supervisor'))
              ->orWhereHas('roles', fn($sub) => $sub->where('slug', 'supervisor')->orWhere('name', 'Supervisor'));
        })
            ->with('profile')
            ->orderBy('username')
            ->get();

        return view('training.create', compact(
            'program', 'compTree', 'formOld', 'formErrors',
            'availablePrograms', 'formTypes', 'levels', 'outcomes', 'genders', 'ageUnits', 'tenant',
            'assignedSupervisor', 'supervisors'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $validator = Validator::make($request->all(), [
            'form_type'          => ['required', 'string'],
            'hospt_reg_no'       => ['required', 'string', 'max:120'],
            'date_of_admission'  => ['required', 'string'],
            'pt_gender'          => ['required', 'string', 'max:20'],
            'pt_age'             => ['required', 'string', 'max:20'],
            'pt_age_type'        => ['required', 'string', 'max:30'],
            'pt_diagnosis'       => ['required', 'string', 'max:500'],
            'under_sup_name'     => ['nullable', 'string', 'max:255'],
            'supervisor_id'      => ['nullable', 'integer'],
            'level_id'           => ['required', 'string'],
            'outcome_id'         => ['required', 'string'],
            'brief_desc'         => ['required', 'string'],
            'entry_for_prog_id'  => ['nullable', 'string', 'max:10'],
            'std_post'           => ['required', 'in:Yes,No'],
        ], [
            'required' => 'The :attribute field is required.',
        ], [
            'form_type'          => 'Form Type',
            'hospt_reg_no'       => 'Hospital Reg. No / Autopsy / MLC No',
            'date_of_admission'  => 'Date of Admission',
            'pt_gender'          => 'Gender',
            'pt_age'             => 'Age',
            'pt_age_type'        => 'Age Type',
            'pt_diagnosis'       => 'Diagnosis / Suspected Diagnosis / Cause',
            'level_id'           => 'Level',
            'outcome_id'         => 'Outcome',
            'brief_desc'         => 'Brief Description',
            'entry_for_prog_id'  => 'Program',
            'std_post'           => 'Send to Supervisor',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('form_errors', array_merge(['Please fill all the required fields.'], $validator->errors()->all()))
                ->with('flash_err', 'Please fill all the required fields.');
        }

        $validated = $validator->validated();
        $admDate = $this->parseBritishDate($validated['date_of_admission']);

        $user = User::with('supervisor')->find($userId);
        $supervisorId = !empty($validated['supervisor_id']) ? (int) $validated['supervisor_id'] : null;
        $underSupName = trim((string) ($validated['under_sup_name'] ?? ''));

        // If trainee typed supervisor name manually, match supervisor in current tenant
        if ($underSupName !== '') {
            $tenantId = $user?->tenant_id ?: 1;
            $matchedSupervisor = User::where('tenant_id', $tenantId)
                ->where(function ($q) {
                    $q->whereHas('userType', fn($sub) => $sub->where('name', 'Supervisor'))
                      ->orWhereHas('roles', fn($sub) => $sub->where('slug', 'supervisor')->orWhere('name', 'Supervisor'));
                })
                ->where(function ($q) use ($underSupName) {
                    $clean = trim(preg_replace('/^dr\.?\s*/i', '', $underSupName));
                    $q->where('username', 'LIKE', '%' . $underSupName . '%')
                      ->orWhere('email', 'LIKE', '%' . $underSupName . '%');
                    if ($clean !== '') {
                        $q->orWhere('username', 'LIKE', '%' . $clean . '%');
                    }
                    $q->orWhereHas('profile', function ($pq) use ($underSupName, $clean) {
                        $pq->where('full_name', 'LIKE', '%' . $underSupName . '%');
                        if ($clean !== '') {
                            $pq->orWhere('full_name', 'LIKE', '%' . $clean . '%');
                        }
                    });
                })
                ->first();

            if ($matchedSupervisor) {
                $supervisorId = $matchedSupervisor->id;
                // Automatically assign trainee to this supervisor if not already assigned
                if ($user && (! $user->supervisor_id || $user->supervisor_id !== $supervisorId)) {
                    $user->supervisor_id = $supervisorId;
                    $user->save();
                    DB::table('supervisor_trainee')->updateOrInsert(
                        ['trainee_id' => $userId],
                        ['supervisor_id' => $supervisorId, 'updated_at' => now(), 'created_at' => now()]
                    );
                }
            }
        }

        if (! $supervisorId) {
            $supervisorId = $user?->supervisor_id;
            if (! $supervisorId) {
                $supervisorId = (int) (DB::table('supervisor_trainee')->where('trainee_id', $userId)->value('supervisor_id') ?: 0) ?: null;
            }
        }

        if ($underSupName === '' && $supervisorId) {
            $supUser = User::with('profile')->find($supervisorId);
            $underSupName = $supUser?->profile?->full_name ?: $supUser?->username ?: '';
        }

        TrainingEntry::create([
            'user_id'           => $userId,
            'supervisor_id'     => $supervisorId,
            'entry_type'        => 'training',
            'form_type'         => $validated['form_type'],
            'hospt_reg_no'      => $validated['hospt_reg_no'],
            'date_of_admission' => $admDate,
            'pt_gender'         => $validated['pt_gender'],
            'pt_age'            => $validated['pt_age'],
            'pt_age_type'       => $validated['pt_age_type'],
            'pt_diagnosis'      => $validated['pt_diagnosis'],
            'under_sup_name'    => $underSupName ?: '—',
            'level_id'          => $validated['level_id'],
            'outcome_id'        => $validated['outcome_id'],
            'brief_desc'        => $validated['brief_desc'],
            'entry_for_prog_id' => $validated['entry_for_prog_id'] ?? '',
            'program'           => $program,
            'com_ids'           => $request->input('com_id') ? array_map('intval', (array) $request->input('com_id')) : null,
            'com_detail_ids'    => $request->input('com_detail_id') ? array_map('intval', (array) $request->input('com_detail_id')) : null,
            'alt_procedure'     => (string) $request->input('alt_procedure', ''),
            'std_post'          => $validated['std_post'],
            'entry_status'      => $validated['std_post'] === 'Yes' ? 'Awaiting Approval' : 'Draft',
        ]);

        $params = $program !== '' ? ['program' => $program] : [];

        return redirect()->route('training.index', $params)
            ->with('flash_ok', 'Training entry saved successfully.');
    }

    public function show(int $id): View
    {
        $userId = (int) session('user_id');
        $user   = \App\Models\User::with('userType')->find($userId);

        if ($user && ! $user->isTrainee()) {
            $entry = TrainingEntry::findOrFail($id);
        } else {
            $entry = TrainingEntry::where('user_id', $userId)->findOrFail($id);
        }

        $compMap = $this->competencyService->labelMap($entry->program ?? '');

        return view('training.show', compact('entry', 'compMap'));
    }

    private function parseBritishDate(?string $s): ?string
    {
        $s = trim((string) ($s ?? ''));
        if ($s === '') {
            return null;
        }
        if (! preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $s, $m)) {
            return null;
        }

        return $m[3] . '-' . $m[2] . '-' . $m[1];
    }
}
