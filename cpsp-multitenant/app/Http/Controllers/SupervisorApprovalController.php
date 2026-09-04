<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\PresentedEntry;
use App\Models\PublishedEntry;
use App\Models\RotationalEntry;
use App\Models\TrainingEntry;
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

        $traineeId = $request->query('trainee_id') ? (int) $request->query('trainee_id') : null;
        $typeFilter = strtolower((string) $request->query('type', 'all'));
        $statusFilter = (string) $request->query('status', 'Awaiting Approval');
        $search = trim((string) $request->query('search', ''));

        $trainees = User::where(function ($q) {
            $q->whereHas('userType', fn($sub) => $sub->where('name', 'Trainee'))
              ->orWhereHas('roles', fn($sub) => $sub->where('slug', 'trainee')->orWhere('name', 'Trainee'));
        })
            ->with('profile')
            ->orderBy('username')
            ->get();

        // Build combined entries
        $items = collect();

        if (in_array($typeFilter, ['all', 'training'], true)) {
            $trainings = TrainingEntry::with(['user.profile', 'approvedBy'])
                ->where('std_post', 'Yes')
                ->when($traineeId, fn($q) => $q->where('user_id', $traineeId))
                ->when($statusFilter !== 'all' && $statusFilter !== '', fn($q) => $q->where('entry_status', $statusFilter))
                ->when($search !== '', fn($q) => $q->where(fn($sq) => $sq->where('pt_diagnosis', 'like', "%{$search}%")->orWhere('hospt_reg_no', 'like', "%{$search}%")))
                ->latest('created_at')
                ->get()
                ->map(fn($row) => $this->normalizeEntry('training', $row));
            $items = $items->concat($trainings);
        }

        if (in_array($typeFilter, ['all', 'rotational'], true)) {
            $rotationals = RotationalEntry::with(['user.profile', 'approvedBy'])
                ->where('std_post', 'Yes')
                ->when($traineeId, fn($q) => $q->where('user_id', $traineeId))
                ->when($statusFilter !== 'all' && $statusFilter !== '', fn($q) => $q->where('entry_status', $statusFilter))
                ->when($search !== '', fn($q) => $q->where(fn($sq) => $sq->where('pt_diagnosis', 'like', "%{$search}%")->orWhere('hospt_reg_no', 'like', "%{$search}%")))
                ->latest('created_at')
                ->get()
                ->map(fn($row) => $this->normalizeEntry('rotational', $row));
            $items = $items->concat($rotationals);
        }

        if (in_array($typeFilter, ['all', 'journal'], true)) {
            $journals = JournalEntry::with(['user.profile', 'approvedBy'])
                ->where('std_post', 'Yes')
                ->when($traineeId, fn($q) => $q->where('user_id', $traineeId))
                ->when($statusFilter !== 'all' && $statusFilter !== '', fn($q) => $q->where('entry_status', $statusFilter))
                ->when($search !== '', fn($q) => $q->where(fn($sq) => $sq->where('topic', 'like', "%{$search}%")->orWhere('ref_of_art_disc', 'like', "%{$search}%")))
                ->latest('created_at')
                ->get()
                ->map(fn($row) => $this->normalizeEntry('journal', $row));
            $items = $items->concat($journals);
        }

        if (in_array($typeFilter, ['all', 'presented'], true)) {
            $presenteds = PresentedEntry::with(['user.profile', 'approvedBy'])
                ->where('std_post', 'Yes')
                ->when($traineeId, fn($q) => $q->where('user_id', $traineeId))
                ->when($statusFilter !== 'all' && $statusFilter !== '', fn($q) => $q->where('entry_status', $statusFilter))
                ->when($search !== '', fn($q) => $q->where('rec_title', 'like', "%{$search}%"))
                ->latest('created_at')
                ->get()
                ->map(fn($row) => $this->normalizeEntry('presented', $row));
            $items = $items->concat($presenteds);
        }

        if (in_array($typeFilter, ['all', 'published'], true)) {
            $publisheds = PublishedEntry::with(['user.profile', 'approvedBy'])
                ->where('std_post', 'Yes')
                ->when($traineeId, fn($q) => $q->where('user_id', $traineeId))
                ->when($statusFilter !== 'all' && $statusFilter !== '', fn($q) => $q->where('entry_status', $statusFilter))
                ->when($search !== '', fn($q) => $q->where('pub_title', 'like', "%{$search}%"))
                ->latest('created_at')
                ->get()
                ->map(fn($row) => $this->normalizeEntry('published', $row));
            $items = $items->concat($publisheds);
        }

        // Sort items descending by created_at
        $items = $items->sortByDesc('created_at')->values();

        // Calculate counts for quick filter tabs
        $statusCounts = [
            'awaiting'    => $this->countSubmittedByStatus('Awaiting Approval', $traineeId, $typeFilter),
            'approved'    => $this->countSubmittedByStatus('Approved', $traineeId, $typeFilter),
            'resubmit'    => $this->countSubmittedByStatus('Discuss and Resubmit', $traineeId, $typeFilter),
            'disapproved' => $this->countSubmittedByStatus('Disapproved', $traineeId, $typeFilter),
            'all'         => $this->countSubmittedByStatus('all', $traineeId, $typeFilter),
        ];

        return view('supervisor.entries', [
            'user'         => $user,
            'entries'      => $items,
            'trainees'     => $trainees,
            'traineeId'    => $traineeId,
            'typeFilter'   => $typeFilter,
            'statusFilter' => $statusFilter,
            'search'       => $search,
            'statusCounts' => $statusCounts,
        ]);
    }

    /**
     * Return complete details of a specific entry (useful for modal inspection).
     */
    public function show(string $type, int $id): JsonResponse|View
    {
        $entry = $this->findEntryModel($type, $id);
        if (! $entry) {
            abort(404, 'Entry not found');
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
        $validated = $request->validate([
            'status'             => ['required', 'in:Approved,Awaiting Approval,Discuss and Resubmit,Disapproved'],
            'supervisor_remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $entry = $this->findEntryModel($type, $id);
        if (! $entry) {
            abort(404, 'Entry not found');
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
        $validated = $request->validate([
            'selected_entries'   => ['required', 'array'],
            'bulk_status'        => ['required', 'in:Approved,Awaiting Approval,Discuss and Resubmit,Disapproved'],
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
    private function countSubmittedByStatus(string $status, ?int $traineeId, string $typeFilter): int
    {
        $total = 0;
        $models = [
            'training'   => TrainingEntry::query(),
            'rotational' => RotationalEntry::query(),
            'journal'    => JournalEntry::query(),
            'presented'  => PresentedEntry::query(),
            'published'  => PublishedEntry::query(),
        ];

        foreach ($models as $type => $query) {
            if ($typeFilter !== 'all' && $typeFilter !== $type) {
                continue;
            }

            $query->where('std_post', 'Yes');
            if ($traineeId) {
                $query->where('user_id', $traineeId);
            }
            if ($status !== 'all') {
                $query->where('entry_status', $status);
            }

            $total += $query->count();
        }

        return $total;
    }

    /**
     * Normalize entry objects into a consistent format for the view.
     */
    private function normalizeEntry(string $type, mixed $row): array
    {
        $traineeName = $row->user?->profile?->full_name;
        if (! $traineeName) {
            $traineeName = $row->user?->username ?? 'Trainee #' . $row->user_id;
        }

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

        if ($type === 'training' || $type === 'rotational') {
            $typeLabel = $type === 'training' ? 'Training' : 'Rotational Training';
            $dateFormatted = $row->date_of_admission ? $row->date_of_admission->format('d-m-Y') : '—';
            $title = $row->pt_diagnosis ?: 'No diagnosis specified';
            $subMeta = "Reg: " . ($row->hospt_reg_no ?: '—') . " | Age: {$row->pt_age} {$row->pt_age_type} | Gender: {$row->pt_gender}";
        } elseif ($type === 'journal') {
            $typeLabel = 'Journal Club';
            $dateFormatted = $row->date_of_diss ? $row->date_of_diss->format('d-m-Y') : '—';
            $title = $row->topic ?: ($row->ref_of_art_disc ?: 'Journal Club Entry');
            $subMeta = "Facilitator: " . ($row->fac_by ?: '—');
        } elseif ($type === 'presented') {
            $typeLabel = 'Paper Presented';
            $dateFormatted = $row->rec_date ? $row->rec_date->format('d-m-Y') : '—';
            $title = $row->rec_title ?: 'Paper / Poster Presented';
            $subMeta = "Venue: " . ($row->rec_venue ?: '—') . ($row->conf_name ? " | Conf: {$row->conf_name}" : '');
        } elseif ($type === 'published') {
            $typeLabel = 'Paper Published';
            $dateFormatted = $row->pub_date ? $row->pub_date->format('d-m-Y') : '—';
            $title = $row->pub_title ?: 'Paper Published';
            $subMeta = "Journal: " . ($row->pub_journal ?: '—') . ($row->pub_authors ? " | Authors: {$row->pub_authors}" : '');
        }

        $levelMap = ['1' => 'Observer', '2' => 'Assistant', '3' => 'Direct', '4' => 'Indirect', '5' => 'Independent', '5555' => 'Other'];
        $levelName = isset($row->level_id) ? ($levelMap[$row->level_id] ?? $row->level_id) : '';

        $outcomeMap = ['1' => 'Cured', '2' => 'Improved', '3' => 'Unchanged', '4' => 'Expired', '5' => 'Referred'];
        $outcomeName = isset($row->outcome_id) ? ($outcomeMap[$row->outcome_id] ?? $row->outcome_id) : '';

        return [
            'id'                 => $row->id,
            'type'               => $type,
            'type_label'         => $typeLabel,
            'badge_class'        => $badgeClass,
            'user_id'            => $row->user_id,
            'trainee_name'       => $traineeName,
            'trainee_username'   => $row->user?->username ?? '',
            'date_formatted'     => $dateFormatted,
            'title'              => $title,
            'sub_meta'           => $subMeta,
            'brief_desc'         => $row->brief_desc ?? $row->ref_of_art_disc ?? $row->full_ref ?? '',
            'alt_procedure'      => $row->alt_procedure ?? '',
            'under_sup_name'     => $row->under_sup_name ?? '',
            'level_name'         => $levelName,
            'outcome_name'       => $outcomeName,
            'program'            => $row->program ?? '',
            'com_ids'            => $row->com_ids ?? null,
            'com_detail_ids'     => $row->com_detail_ids ?? null,
            'rot_ids'            => $row->rot_ids ?? null,
            'rot_detail_ids'     => $row->rot_detail_ids ?? null,
            'entry_status'       => $row->entry_status ?? 'Awaiting Approval',
            'supervisor_remarks' => $row->supervisor_remarks ?? '',
            'approved_at'        => $row->approved_at ? $row->approved_at->format('d-m-Y H:i') : null,
            'approved_by_name'   => $row->approvedBy?->profile?->full_name ?: ($row->approvedBy?->username ?? ''),
            'created_at'         => $row->created_at,
            'created_formatted'  => $row->created_at ? $row->created_at->format('d-m-Y H:i') : '—',
        ];
    }
}
