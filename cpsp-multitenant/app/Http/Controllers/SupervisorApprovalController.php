<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\PresentedEntry;
use App\Models\PublishedEntry;
use App\Models\RotationalEntry;
use App\Models\TrainingEntry;
use App\Models\TraineeWorkshop;
use App\Models\TraineeSynopsis;
use App\Models\TraineeDissertation;
use App\Models\User;
use App\Services\CompetencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class SupervisorApprovalController extends Controller
{
    public function __construct(private CompetencyService $competencyService) {}

    /**
     * Display a listing of submitted trainee entries awaiting approval or with status filter.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $userId = (int) session('user_id');
        $user = User::with('userType')->find($userId);

        if (! $user || $user->isTrainee()) {
            return redirect()->route('dashboard');
        }

        $isAdmin = $user->isAdmin();
        $assignedTraineeIds = $user->getAssignedTraineeIds();

        $traineeId          = $request->query('trainee_id') ? (int) $request->query('trainee_id') : null;
        $typeFilter         = strtolower((string) $request->query('type', 'all'));
        $statusFilter       = (string) $request->query('status', 'Awaiting Approval');
        $admissionDateFrom  = trim((string) $request->query('admission_date_from', ''));
        $admissionDateTo    = trim((string) $request->query('admission_date_to', ''));
        $diagnosis          = trim((string) $request->query('diagnosis', ''));
        $postDateFrom       = trim((string) $request->query('post_date_from', ''));
        $postDateTo         = trim((string) $request->query('post_date_to', ''));
        $level              = trim((string) $request->query('level', ''));
        $hosptRegNo         = trim((string) $request->query('hospt_reg_no', ''));
        $search             = trim((string) $request->query('search', ''));

        if ($isAdmin) {
            $trainees = User::where(function ($q) {
                $q->whereHas('userType', fn($sub) => $sub->where('name', 'Trainee'))
                  ->orWhereHas('roles', fn($sub) => $sub->where('slug', 'trainee')->orWhere('name', 'Trainee'));
            })
                ->with('profile')
                ->orderBy('username')
                ->get();
        } else {
            $trainees = User::whereIn('id', !empty($assignedTraineeIds) ? $assignedTraineeIds : [-1])
                ->with('profile')
                ->orderBy('username')
                ->get();
        }

        $selectedTrainee = null;
        if ($traineeId) {
            $selectedTrainee = $trainees->firstWhere('id', $traineeId) ?? User::with('profile')->find($traineeId);
        }

        // Helper date normalizer
        $normalizeDate = function (?string $val): ?string {
            if (!$val) return null;
            $val = trim($val);
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
                return $val;
            }
            if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $val, $m)) {
                return "{$m[3]}-{$m[2]}-{$m[1]}";
            }
            try {
                return \Carbon\Carbon::parse($val)->format('Y-m-d');
            } catch (\Throwable) {
                return null;
            }
        };

        $filters = [
            'trainee_id'          => $traineeId,
            'admission_date_from' => $normalizeDate($admissionDateFrom),
            'admission_date_to'   => $normalizeDate($admissionDateTo),
            'diagnosis'           => $diagnosis,
            'post_date_from'      => $normalizeDate($postDateFrom),
            'post_date_to'        => $normalizeDate($postDateTo),
            'level'               => $level,
            'hospt_reg_no'        => $hosptRegNo,
            'search'              => $search,
        ];

        // Helper to scope entries to supervisor's assigned trainees or specifically targeted supervisor_id
        $applySupervisorScope = function ($query) use ($isAdmin, $userId, $assignedTraineeIds) {
            if (! $isAdmin) {
                $query->where(function ($q) use ($userId, $assignedTraineeIds) {
                    $q->where('supervisor_id', $userId)
                      ->orWhereIn('user_id', !empty($assignedTraineeIds) ? $assignedTraineeIds : [-1]);
                });
            }
        };

        // Build combined entries
        $items = collect();

        if (in_array($typeFilter, ['all', 'training'], true)) {
            $trainings = TrainingEntry::with(['user.profile', 'approvedBy', 'supervisor.profile'])
                ->where('std_post', 'Yes')
                ->tap($applySupervisorScope);
            $this->applyFilters($trainings, 'training', $traineeId, $statusFilter, $filters);
            $items = $items->concat(
                $trainings->latest('created_at')->get()->map(fn($row) => $this->normalizeEntry('training', $row))
            );
        }

        if (in_array($typeFilter, ['all', 'rotational'], true)) {
            $rotationals = RotationalEntry::with(['user.profile', 'approvedBy', 'supervisor.profile'])
                ->where('std_post', 'Yes')
                ->tap($applySupervisorScope);
            $this->applyFilters($rotationals, 'rotational', $traineeId, $statusFilter, $filters);
            $items = $items->concat(
                $rotationals->latest('created_at')->get()->map(fn($row) => $this->normalizeEntry('rotational', $row))
            );
        }

        if (in_array($typeFilter, ['all', 'journal'], true)) {
            $journals = JournalEntry::with(['user.profile', 'approvedBy', 'supervisor.profile'])
                ->where('std_post', 'Yes')
                ->tap($applySupervisorScope);
            $this->applyFilters($journals, 'journal', $traineeId, $statusFilter, $filters);
            $items = $items->concat(
                $journals->latest('created_at')->get()->map(fn($row) => $this->normalizeEntry('journal', $row))
            );
        }

        if (in_array($typeFilter, ['all', 'presented'], true)) {
            $presenteds = PresentedEntry::with(['user.profile', 'approvedBy', 'supervisor.profile'])
                ->where('std_post', 'Yes')
                ->tap($applySupervisorScope);
            $this->applyFilters($presenteds, 'presented', $traineeId, $statusFilter, $filters);
            $items = $items->concat(
                $presenteds->latest('created_at')->get()->map(fn($row) => $this->normalizeEntry('presented', $row))
            );
        }

        if (in_array($typeFilter, ['all', 'published'], true)) {
            $publisheds = PublishedEntry::with(['user.profile', 'approvedBy', 'supervisor.profile'])
                ->where('std_post', 'Yes')
                ->tap($applySupervisorScope);
            $this->applyFilters($publisheds, 'published', $traineeId, $statusFilter, $filters);
            $items = $items->concat(
                $publisheds->latest('created_at')->get()->map(fn($row) => $this->normalizeEntry('published', $row))
            );
        }

        // Sort items descending by created_at
        $items = $items->sortByDesc('created_at')->values();

        // Calculate counts for quick filter tabs
        $statusCounts = [
            'awaiting'    => $this->countSubmittedByStatus('Awaiting Approval', $traineeId, $typeFilter, $user, $filters),
            'approved'    => $this->countSubmittedByStatus('Approved', $traineeId, $typeFilter, $user, $filters),
            'resubmit'    => $this->countSubmittedByStatus('Discuss and Resubmit', $traineeId, $typeFilter, $user, $filters),
            'all'         => $this->countSubmittedByStatus('all', $traineeId, $typeFilter, $user, $filters),
        ];

        return view('supervisor.entries', [
            'tenant'            => app(\App\Services\TenantManager::class)->get(),
            'user'              => $user,
            'entries'           => $items,
            'trainees'          => $trainees,
            'traineeId'         => $traineeId,
            'selectedTrainee'   => $selectedTrainee,
            'typeFilter'        => $typeFilter,
            'statusFilter'      => $statusFilter,
            'admissionDateFrom' => $admissionDateFrom,
            'admissionDateTo'   => $admissionDateTo,
            'diagnosis'         => $diagnosis,
            'postDateFrom'      => $postDateFrom,
            'postDateTo'        => $postDateTo,
            'level'             => $level,
            'hosptRegNo'        => $hosptRegNo,
            'search'            => $search,
            'statusCounts'      => $statusCounts,
            'levels'            => \App\Services\LookupService::levels(),
        ]);
    }

    /**
     * Apply all search and date filters to a query.
     */
    private function applyFilters($query, string $type, ?int $traineeId, string $statusFilter, array $filters): void
    {
        if ($traineeId) {
            $query->where('user_id', $traineeId);
        }

        if ($statusFilter !== 'all' && $statusFilter !== '') {
            $query->where('entry_status', $statusFilter);
        }

        // Date of admission / procedure
        $admField = match ($type) {
            'training', 'rotational' => 'date_of_admission',
            'journal'                => 'date_of_diss',
            'presented'              => 'rec_date',
            'published'              => 'pub_date',
            default                  => null,
        };

        if ($admField) {
            if (!empty($filters['admission_date_from'])) {
                $query->whereDate($admField, '>=', $filters['admission_date_from']);
            }
            if (!empty($filters['admission_date_to'])) {
                $query->whereDate($admField, '<=', $filters['admission_date_to']);
            }
        }

        // Post date (created_at)
        if (!empty($filters['post_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['post_date_from']);
        }
        if (!empty($filters['post_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['post_date_to']);
        }

        // Diagnosis / Title
        if (!empty($filters['diagnosis'])) {
            $d = $filters['diagnosis'];
            if (in_array($type, ['training', 'rotational'], true)) {
                $query->where('pt_diagnosis', 'like', "%{$d}%");
            } elseif ($type === 'journal') {
                $query->where(fn($sq) => $sq->where('topic', 'like', "%{$d}%")->orWhere('ref_of_art_disc', 'like', "%{$d}%"));
            } elseif ($type === 'presented') {
                $query->where('rec_title', 'like', "%{$d}%");
            } elseif ($type === 'published') {
                $query->where('pub_title', 'like', "%{$d}%");
            }
        }

        // Supervision level (applicable to training & rotational)
        if (!empty($filters['level'])) {
            if (in_array($type, ['training', 'rotational'], true)) {
                $query->where('level_id', $filters['level']);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Hospital Reg No / Autopsy / MLC (applicable to training & rotational)
        if (!empty($filters['hospt_reg_no'])) {
            if (in_array($type, ['training', 'rotational'], true)) {
                $query->where('hospt_reg_no', 'like', "%{$filters['hospt_reg_no']}%");
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // General search
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            if (in_array($type, ['training', 'rotational'], true)) {
                $query->where(fn($sq) => $sq->where('pt_diagnosis', 'like', "%{$s}%")
                    ->orWhere('hospt_reg_no', 'like', "%{$s}%")
                    ->orWhere('alt_procedure', 'like', "%{$s}%")
                    ->orWhere('brief_desc', 'like', "%{$s}%"));
            } elseif ($type === 'journal') {
                $query->where(fn($sq) => $sq->where('topic', 'like', "%{$s}%")->orWhere('ref_of_art_disc', 'like', "%{$s}%"));
            } elseif ($type === 'presented') {
                $query->where(fn($sq) => $sq->where('rec_title', 'like', "%{$s}%")->orWhere('rec_venue', 'like', "%{$s}%"));
            } elseif ($type === 'published') {
                $query->where(fn($sq) => $sq->where('pub_title', 'like', "%{$s}%")->orWhere('pub_journal', 'like', "%{$s}%"));
            }
        }
    }

    /**
     * Return complete details of a specific entry (useful for modal inspection).
     */
    public function show(string $type, int $id): JsonResponse|View
    {
        $userId = (int) session('user_id');
        $user = User::find($userId);

        $entry = $this->findEntryModel($type, $id);
        if (! $entry) {
            abort(404, 'Entry not found');
        }

        if ($user && ! $this->canSupervisorAccessEntry($user, $entry)) {
            abort(403, 'Unauthorized. This entry belongs to a trainee not assigned to you.');
        }

        $entry->loadMissing(['user.profile', 'approvedBy']);
        $normalized = $this->normalizeEntry($type, $entry);

        if (request()->wantsJson()) {
            return response()->json($normalized);
        }

        return view('supervisor.entry_detail', ['entry' => $normalized]);
    }

    /**
     * Update approval status of a single entry.
     */
    public function updateStatus(Request $request, string $type, int $id): RedirectResponse|JsonResponse
    {
        $supervisorId = (int) session('user_id');
        $user = User::find($supervisorId);

        $validated = $request->validate([
            'status'             => ['required', 'in:Approved,Awaiting Approval,Discuss and Resubmit'],
            'supervisor_remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $entry = $this->findEntryModel($type, $id);
        if (! $entry) {
            abort(404, 'Entry not found');
        }

        if ($user && ! $this->canSupervisorAccessEntry($user, $entry)) {
            abort(403, 'Unauthorized. You can only approve entries submitted by your assigned trainees.');
        }

        $newStatus = $validated['status'];
        $remarks   = $validated['supervisor_remarks'] ?? $entry->supervisor_remarks ?? '';

        $updateData = [
            'entry_status'       => $newStatus,
            'supervisor_remarks' => $remarks,
        ];

        if ($newStatus === 'Approved') {
            $updateData['approved_at'] = now();
            $updateData['approved_by'] = $supervisorId;
        } elseif ($newStatus === 'Awaiting Approval') {
            $updateData['approved_at'] = null;
            $updateData['approved_by'] = null;
        }

        $entry->update($updateData);

        $msg = "Entry has been updated to '{$newStatus}' successfully.";

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msg, 'entry_status' => $newStatus]);
        }

        return redirect()->back()->with('flash_ok', $msg);
    }

    /**
     * Bulk update approval status for multiple entries.
     */
    public function bulkUpdateStatus(Request $request): RedirectResponse
    {
        $supervisorId = (int) session('user_id');
        $user = User::find($supervisorId);

        $validated = $request->validate([
            'selected_entries'   => ['required', 'array'],
            'bulk_status'        => ['required', 'in:Approved,Awaiting Approval,Discuss and Resubmit'],
            'bulk_remarks'       => ['nullable', 'string', 'max:1000'],
        ]);

        $status  = $validated['bulk_status'];
        $remarks = $validated['bulk_remarks'] ?? null;
        $count   = 0;

        foreach ($validated['selected_entries'] as $itemKey) {
            $parts = explode(':', (string) $itemKey);
            if (count($parts) !== 2) {
                continue;
            }

            [$type, $idStr] = $parts;
            $id = (int) $idStr;
            $entry = $this->findEntryModel($type, $id);
            if (! $entry) {
                continue;
            }

            // Security check: only allow updating entries assigned to this supervisor
            if ($user && ! $this->canSupervisorAccessEntry($user, $entry)) {
                continue;
            }

            $updateData = ['entry_status' => $status];
            if ($remarks !== null && $remarks !== '') {
                $updateData['supervisor_remarks'] = $remarks;
            }

            if ($status === 'Approved') {
                $updateData['approved_at'] = now();
                $updateData['approved_by'] = $supervisorId;
            } elseif ($status === 'Awaiting Approval') {
                $updateData['approved_at'] = null;
                $updateData['approved_by'] = null;
            }

            $entry->update($updateData);
            $count++;
        }

        return redirect()->back()->with('flash_ok', "{$count} entries successfully updated to '{$status}'.");
    }

    /**
     * Determine if a supervisor is authorized to access / approve an entry.
     */
    private function canSupervisorAccessEntry(User $supervisor, mixed $entry): bool
    {
        if ($supervisor->isAdmin()) {
            return true;
        }

        if (isset($entry->supervisor_id) && $entry->supervisor_id && (int) $entry->supervisor_id === (int) $supervisor->id) {
            return true;
        }

        $assignedTraineeIds = $supervisor->getAssignedTraineeIds();
        if (in_array((int) $entry->user_id, $assignedTraineeIds, true)) {
            return true;
        }

        return false;
    }

    /**
     * Find the model instance by type string and id.
     */
    private function findEntryModel(string $type, int $id): mixed
    {
        return match ($type) {
            'training'   => TrainingEntry::find($id),
            'rotational' => RotationalEntry::find($id),
            'journal'    => JournalEntry::find($id),
            'presented'  => PresentedEntry::find($id),
            'published'  => PublishedEntry::find($id),
            default      => null,
        };
    }

    /**
     * Count submitted entries by status.
     */
    private function countSubmittedByStatus(string $status, ?int $traineeId, string $typeFilter, ?User $user = null, array $filters = []): int
    {
        $total = 0;
        $models = [
            'training'   => TrainingEntry::query(),
            'rotational' => RotationalEntry::query(),
            'journal'    => JournalEntry::query(),
            'presented'  => PresentedEntry::query(),
            'published'  => PublishedEntry::query(),
        ];

        $isAdmin = $user && $user->isAdmin();
        $assignedTraineeIds = $user ? $user->getAssignedTraineeIds() : [];

        foreach ($models as $type => $query) {
            if ($typeFilter !== 'all' && $typeFilter !== $type) {
                continue;
            }

            $query->where('std_post', 'Yes');

            if ($user && ! $isAdmin) {
                $query->where(function ($sq) use ($user, $assignedTraineeIds) {
                    $sq->where('supervisor_id', $user->id)
                       ->orWhereIn('user_id', !empty($assignedTraineeIds) ? $assignedTraineeIds : [-1]);
                });
            }

            $this->applyFilters($query, $type, $traineeId, $status, $filters);

            $total += $query->count();
        }

        return $total;
    }

    /**
     * Normalize entry objects into a consistent format for the view and modal inspection.
     */
    private function normalizeEntry(string $type, mixed $row): array
    {
        $traineeName = $row->user?->profile?->full_name;
        if (! $traineeName) {
            $traineeName = $row->user?->username ?? 'Trainee #' . $row->user_id;
        }

        $traineeUsername = $row->user?->username ?? '';
        $traineeRollNo   = $row->user?->profile?->reg_no ?? $row->user?->profile?->roll_no ?? $traineeUsername;
        $traineeProgram  = $row->program ?? $row->user?->profile?->program ?? '';

        $dateFormatted = '—';
        $title = '—';
        $subMeta = '';
        $typeLabel = ucfirst($type);
        $badgeClass = match ($type) {
            'training'   => 'badge--primary',
            'rotational' => 'badge--info',
            'journal'    => 'badge--purple',
            'presented'  => 'badge--orange',
            'published'  => 'badge--teal',
            default      => 'badge--muted',
        };

        $formTypeLabel = '—';
        $hosptRegNo = '';
        $mlcNo = '';
        $gender = '';
        $age = '';
        $procedureName = '';
        $underSupName = $row->under_sup_name ?: ($row->supervisor?->profile?->full_name ?: ($row->supervisor?->username ?? '—'));

        if ($type === 'training' || $type === 'rotational') {
            $typeLabel = $type === 'training' ? 'Training' : 'Rotational Training';
            $formTypeLabel = \App\Services\LookupService::formTypeLabel($row->form_type ?? null);
            $dateFormatted = $row->date_of_admission ? $row->date_of_admission->format('d-m-Y') : '—';
            $title = $row->pt_diagnosis ?: 'No diagnosis specified';
            $hosptRegNo = $row->hospt_reg_no ?: '—';
            $mlcNo = $row->hospt_reg_no ?: '—';
            $gender = $row->pt_gender ?: '—';
            $age = $row->pt_age ? ($row->pt_age . ' ' . ($row->pt_age_type ?? 'Year[s]')) : '—';
            $procedureName = $row->alt_procedure ?: $formTypeLabel;
            $subMeta = "Reg: " . ($row->hospt_reg_no ?: '—') . " | Age: {$age} | Gender: {$gender}";
        } elseif ($type === 'journal') {
            $typeLabel = 'Journal Club';
            $formTypeLabel = 'Journal Club Discussion';
            $dateFormatted = $row->date_of_diss ? $row->date_of_diss->format('d-m-Y') : '—';
            $title = $row->topic ?: ($row->ref_of_art_disc ?: 'Journal Club Entry');
            $procedureName = $row->topic ?: 'Journal Discussion';
            $subMeta = "Facilitator: " . ($row->fac_by ?: '—');
        } elseif ($type === 'presented') {
            $typeLabel = 'Paper Presented';
            $formTypeLabel = 'Paper / Poster Presentation';
            $dateFormatted = $row->rec_date ? $row->rec_date->format('d-m-Y') : '—';
            $title = $row->rec_title ?: 'Paper / Poster Presented';
            $procedureName = $row->rec_title ?: 'Presentation';
            $subMeta = "Venue: " . ($row->rec_venue ?: '—') . ($row->conf_name ? " | Conf: {$row->conf_name}" : '');
        } elseif ($type === 'published') {
            $typeLabel = 'Paper Published';
            $formTypeLabel = 'Publication / Research Article';
            $dateFormatted = $row->pub_date ? $row->pub_date->format('d-m-Y') : '—';
            $title = $row->pub_title ?: 'Paper Published';
            $procedureName = $row->pub_title ?: 'Publication';
            $subMeta = "Journal: " . ($row->pub_journal ?: '—') . ($row->pub_authors ? " | Authors: {$row->pub_authors}" : '');
        }

        $levelMap = [
            '1'    => 'Observer Status',
            '2'    => 'Assistant Status',
            '3'    => 'Performed under direct Supervision',
            '4'    => 'Performed under indirect supervision',
            '5'    => 'Performed independently',
            '5555' => 'Other',
        ];
        $levelName = isset($row->level_id) && $row->level_id !== null ? ($levelMap[$row->level_id] ?? (string)$row->level_id) : '';

        $outcomeMap = [
            '1'  => 'Cured',
            '2'  => 'Admitted to inpatient facility',
            '3'  => 'Treated and called for follow-up',
            '4'  => 'Referred to other specialty unit',
            '5'  => 'Death of the patient / Expired',
            '6'  => 'Other',
            '7'  => 'Improved',
            '8'  => 'Discharged',
            '9'  => 'Treated',
            '10' => 'Under Treatment',
            '11' => 'Treatment Failure',
            '12' => 'Follow Up',
        ];
        $outcomeName = isset($row->outcome_id) && $row->outcome_id !== null ? ($outcomeMap[$row->outcome_id] ?? (string)$row->outcome_id) : '—';

        // Competencies
        $comIds = $row->com_ids ?? $row->rot_ids ?? null;
        $comDetailIds = $row->com_detail_ids ?? $row->rot_detail_ids ?? null;
        if (is_string($comIds)) {
            $comIds = json_decode($comIds, true);
        }
        if (is_string($comDetailIds)) {
            $comDetailIds = json_decode($comDetailIds, true);
        }

        $compMap = $this->competencyService->labelMap((string) ($row->program ?? ''));
        $competencyHtml = $this->competencyService->formatCell($comIds, $comDetailIds, $compMap);

        $competencyGroups = [];
        $competencyDetails = [];
        if (!empty($comIds) && is_array($comIds)) {
            foreach ($comIds as $gid) {
                $gid = (int) $gid;
                if ($gid > 0) {
                    $competencyGroups[] = $compMap[$gid] ?? ('Group #' . $gid);
                }
            }
        }
        if (!empty($comDetailIds) && is_array($comDetailIds)) {
            foreach ($comDetailIds as $did) {
                $did = (int) $did;
                if ($did > 0) {
                    $competencyDetails[] = $compMap[$did] ?? ('Detail #' . $did);
                }
            }
        }

        return [
            'id'                  => $row->id,
            'type'                => $type,
            'type_label'          => $typeLabel,
            'badge_class'         => $badgeClass,
            'user_id'             => $row->user_id,
            'trainee_name'        => $traineeName,
            'trainee_username'    => $traineeUsername,
            'trainee_roll_no'     => $traineeRollNo,
            'trainee_program'     => $traineeProgram,
            'form_type'           => $row->form_type ?? '',
            'form_type_label'     => $formTypeLabel,
            'hospt_reg_no'        => $hosptRegNo,
            'mlc_no'              => $mlcNo,
            'date_formatted'      => $dateFormatted,
            'procedure_name'      => $procedureName,
            'gender'              => $gender,
            'age'                 => $age,
            'pt_gender'           => $gender,
            'pt_age'              => $age,
            'title'               => $title,
            'pt_diagnosis'        => $row->pt_diagnosis ?? $title,
            'under_sup_name'      => $underSupName,
            'level_id'            => $row->level_id ?? '',
            'level_name'          => $levelName,
            'outcome_id'          => $row->outcome_id ?? '',
            'outcome_name'        => $outcomeName,
            'sub_meta'            => $subMeta,
            'brief_desc'          => $row->brief_desc ?? $row->ref_of_art_disc ?? $row->full_ref ?? '',
            'alt_procedure'       => $row->alt_procedure ?? '',
            'program'             => $row->program ?? '',
            'competency_html'     => $competencyHtml,
            'competency_groups'   => $competencyGroups,
            'competency_details'  => $competencyDetails,
            'com_ids'             => $comIds,
            'com_detail_ids'      => $comDetailIds,
            'entry_status'        => $row->entry_status ?? 'Awaiting Approval',
            'supervisor_remarks'  => $row->supervisor_remarks ?? '',
            'approved_at'         => $row->approved_at ? $row->approved_at->format('d-m-Y H:i') : null,
            'approved_by_name'    => $row->approvedBy?->profile?->full_name ?: ($row->approvedBy?->username ?? ''),
            'created_at'          => $row->created_at,
            'created_formatted'   => $row->created_at ? $row->created_at->format('d-m-Y H:i') : '—',
            'post_date'           => $row->created_at ? $row->created_at->format('d-m-Y H:i') : '—',
        ];
    }

    /**
     * Review academic progression: Workshops, Synopsis, and Dissertation.
     */
    public function academicReview(Request $request, ?int $traineeId = null): View|JsonResponse|RedirectResponse
    {
        $userId = (int) session('user_id');
        $user = User::with('userType')->find($userId);

        if (! $user || $user->isTrainee()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return redirect()->route('dashboard');
        }

        $id = $traineeId ?: (int) $request->query('trainee_id');
        if (! $id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Trainee ID is required.'], 400);
            }
            return redirect()->route('dashboard')->with('error', 'Trainee not specified.');
        }

        $isAdmin = $user->isAdmin();
        $assignedTraineeIds = $user->getAssignedTraineeIds();

        if (! $isAdmin && ! in_array($id, $assignedTraineeIds, true)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'You do not have access to this trainee.'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Unauthorized access to trainee.');
        }

        $trainee = User::with(['profile'])->findOrFail($id);

        $workshops = TraineeWorkshop::where('user_id', $trainee->id)->orderBy('id', 'asc')->get();
        $synopses = TraineeSynopsis::where('user_id', $trainee->id)->orderBy('id', 'asc')->get();
        $dissertations = TraineeDissertation::where('user_id', $trainee->id)->orderBy('id', 'asc')->get();

        if ($request->ajax() || $request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'trainee' => [
                    'id'       => $trainee->id,
                    'name'     => $trainee->profile?->full_name ?: $trainee->username,
                    'username' => $trainee->username,
                    'email'    => $trainee->email,
                    'phone'    => $trainee->profile?->phone ?: 'N/A',
                ],
                'workshops' => $workshops->map(fn($w, $idx) => [
                    'hash'          => $idx + 1,
                    'id'            => $w->id,
                    'workshop_name' => $w->workshop_name,
                    'time_duration' => $w->time_duration ?: 'N/A',
                    'status'        => $w->status ?: 'Completed',
                ]),
                'synopses' => $synopses->map(fn($s, $idx) => [
                    'hash'          => $idx + 1,
                    'id'            => $s->id,
                    'synopsis_name' => $s->synopsis_name,
                    'status'        => $s->status ?: 'Approved',
                    'status_date'   => $s->status_date ? $s->status_date->format('d/m/Y') : ($s->created_at ? $s->created_at->format('d/m/Y') : '—'),
                ]),
                'dissertations' => $dissertations->map(fn($d, $idx) => [
                    'hash'             => $idx + 1,
                    'id'               => $d->id,
                    'application_date' => $d->application_date ? $d->application_date->format('d/m/Y') : ($d->created_at ? $d->created_at->format('d/m/Y') : '—'),
                    'status'           => $d->status ?: 'Submitted',
                    'status_date'      => $d->status_date ? $d->status_date->format('d/m/Y') : '—',
                ]),
            ]);
        }

        $tenant = $trainee->tenant ?? ($user->tenant ?? app(\App\Services\TenantManager::class)->get());

        return view('supervisor.academic-review', compact('user', 'trainee', 'workshops', 'synopses', 'dissertations', 'tenant'));
    }
}
