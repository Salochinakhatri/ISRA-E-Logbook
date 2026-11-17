<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TraineeDissertation;
use App\Models\TraineeSynopsis;
use App\Models\TraineeWorkshop;
use App\Models\User;
use App\Services\TenantManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AcademicProgressionController extends Controller
{
    public function __construct(private TenantManager $tenantManager)
    {
        $this->ensureWorkshopRemarksColumn();
    }

    private function ensureWorkshopRemarksColumn(): void
    {
        try {
            if (Schema::hasTable('trainee_workshops') && ! Schema::hasColumn('trainee_workshops', 'remarks')) {
                Schema::table('trainee_workshops', function ($table) {
                    $table->text('remarks')->nullable()->after('status_date');
                });
            }
        } catch (\Throwable) {
            // Ignore if schema check fails
        }
    }

    /**
     * Trainee's view to manage their workshops, synopsis, and dissertation dynamically.
     */
    public function traineeIndex(): View|RedirectResponse
    {
        $userId = (int) session('user_id');
        $user = User::with(['profile', 'tenant'])->find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        $workshops = TraineeWorkshop::where('user_id', $user->id)->orderByDesc('created_at')->orderByDesc('id')->get();
        $synopses = TraineeSynopsis::where('user_id', $user->id)->orderByDesc('created_at')->orderByDesc('id')->get();
        $dissertations = TraineeDissertation::where('user_id', $user->id)->orderByDesc('created_at')->orderByDesc('id')->get();
        $tenant = $user->tenant ?? $this->tenantManager->get();

        return view('academic.trainee-index', compact('user', 'workshops', 'synopses', 'dissertations', 'tenant'));
    }

    /**
     * Add or resubmit a workshop (Trainee only).
     */
    public function storeWorkshop(Request $request, int $traineeId): JsonResponse|RedirectResponse
    {
        $trainee = $this->authorizeTraineeOnly($traineeId);

        $validated = $request->validate([
            'id'            => 'nullable|integer',
            'workshop_name' => 'required|string|max:255',
            'time_duration' => 'required|string|max:100',
            'status'        => 'required|string|max:100',
            'status_date'   => 'nullable|date',
        ]);

        if (!empty($validated['id'])) {
            $workshop = TraineeWorkshop::where('user_id', $traineeId)->findOrFail($validated['id']);
            if (strtolower(trim((string) $workshop->status)) === 'approved') {
                return back()->with('error', 'This workshop has already been approved and cannot be modified.');
            }
            if (! str_contains(strtolower((string)$workshop->status), 'discuss') && ! str_contains(strtolower((string)$workshop->status), 'resubmit')) {
                return back()->with('error', 'This workshop cannot be edited. It can only be updated if your supervisor sets it to "Discuss and Resubmit".');
            }
            $isResubmit = str_contains(strtolower((string)$workshop->status), 'discuss');
            $workshop->update([
                'workshop_name' => $validated['workshop_name'],
                'time_duration' => $validated['time_duration'],
                'status'        => $validated['status'],
                'status_date'   => $validated['status_date'] ?? $workshop->status_date ?? now(),
            ]);
            $msg = 'Workshop updated and resubmitted successfully.';
        } else {
            $workshop = TraineeWorkshop::create([
                'tenant_id'     => $trainee->tenant_id,
                'user_id'       => $trainee->id,
                'workshop_name' => $validated['workshop_name'],
                'time_duration' => $validated['time_duration'],
                'status'        => $validated['status'],
                'status_date'   => $validated['status_date'] ?? now(),
            ]);
            $msg = 'Workshop saved successfully.';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'item'    => $workshop,
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Delete a workshop (Trainee only, before approval).
     */
    public function deleteWorkshop(Request $request, int $traineeId, int $workshopId): JsonResponse|RedirectResponse
    {
        $this->authorizeTraineeOnly($traineeId);

        $workshop = TraineeWorkshop::where('user_id', $traineeId)->findOrFail($workshopId);
        if (strtolower(trim((string) $workshop->status)) === 'approved') {
            return back()->with('error', 'Approved workshops cannot be deleted.');
        }
        $workshop->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Workshop deleted.']);
        }

        return back()->with('success', 'Workshop deleted successfully.');
    }

    /**
     * Add or resubmit a synopsis (Trainee only).
     */
    public function storeSynopsis(Request $request, int $traineeId): JsonResponse|RedirectResponse
    {
        $trainee = $this->authorizeTraineeOnly($traineeId);

        $validated = $request->validate([
            'id'            => 'nullable|integer',
            'synopsis_name' => 'required|string|max:500',
            'status'        => 'required|string|max:100',
            'status_date'   => 'nullable|date',
        ]);

        if (!empty($validated['id'])) {
            $synopsis = TraineeSynopsis::where('user_id', $traineeId)->findOrFail($validated['id']);
            if (strtolower(trim((string) $synopsis->status)) === 'approved') {
                return back()->with('error', 'This synopsis has already been approved and cannot be modified.');
            }
            if (! str_contains(strtolower((string)$synopsis->status), 'discuss') && ! str_contains(strtolower((string)$synopsis->status), 'resubmit')) {
                return back()->with('error', 'This synopsis cannot be edited. It can only be updated if your supervisor sets it to "Discuss and Resubmit".');
            }
            $isResubmit = str_contains(strtolower((string)$synopsis->status), 'discuss');
            $synopsis->update([
                'synopsis_name' => $validated['synopsis_name'],
                'status'        => $validated['status'],
                'status_date'   => $validated['status_date'] ?? $synopsis->status_date ?? now(),
            ]);
            $msg = 'Synopsis updated and resubmitted successfully.';
        } else {
            $synopsis = TraineeSynopsis::create([
                'tenant_id'     => $trainee->tenant_id,
                'user_id'       => $trainee->id,
                'synopsis_name' => $validated['synopsis_name'],
                'status'        => $validated['status'],
                'status_date'   => $validated['status_date'] ?? now(),
            ]);
            $msg = 'Synopsis saved successfully.';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'item'    => $synopsis,
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Delete a synopsis (Trainee only, before approval).
     */
    public function deleteSynopsis(Request $request, int $traineeId, int $synopsisId): JsonResponse|RedirectResponse
    {
        $this->authorizeTraineeOnly($traineeId);

        $synopsis = TraineeSynopsis::where('user_id', $traineeId)->findOrFail($synopsisId);
        if (strtolower(trim((string) $synopsis->status)) === 'approved') {
            return back()->with('error', 'Approved synopsis records cannot be deleted.');
        }
        $synopsis->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Synopsis deleted.']);
        }

        return back()->with('success', 'Synopsis deleted.');
    }

    /**
     * Add or resubmit a dissertation (Trainee only).
     */
    public function storeDissertation(Request $request, int $traineeId): JsonResponse|RedirectResponse
    {
        $trainee = $this->authorizeTraineeOnly($traineeId);

        $validated = $request->validate([
            'id'                 => 'nullable|integer',
            'application_date'   => 'required|date',
            'dissertation_title' => 'nullable|string|max:500',
            'status'             => 'required|string|max:100',
            'status_date'        => 'nullable|date',
        ]);

        if (!empty($validated['id'])) {
            $dissertation = TraineeDissertation::where('user_id', $traineeId)->findOrFail($validated['id']);
            if (strtolower(trim((string) $dissertation->status)) === 'approved') {
                return back()->with('error', 'This dissertation has already been approved and cannot be modified.');
            }
            if (! str_contains(strtolower((string)$dissertation->status), 'discuss') && ! str_contains(strtolower((string)$dissertation->status), 'resubmit')) {
                return back()->with('error', 'This dissertation cannot be edited. It can only be updated if your supervisor sets it to "Discuss and Resubmit".');
            }
            $isResubmit = str_contains(strtolower((string)$dissertation->status), 'discuss');
            $dissertation->update([
                'application_date'   => $validated['application_date'],
                'dissertation_title' => $validated['dissertation_title'] ?? $dissertation->dissertation_title,
                'status'             => $validated['status'],
                'status_date'        => $validated['status_date'] ?? $dissertation->status_date ?? now(),
            ]);
            $msg = $isResubmit ? 'Dissertation updated and resubmitted successfully.' : 'Dissertation updated successfully.';
        } else {
            $dissertation = TraineeDissertation::create([
                'tenant_id'          => $trainee->tenant_id,
                'user_id'            => $trainee->id,
                'application_date'   => $validated['application_date'],
                'dissertation_title' => $validated['dissertation_title'] ?? null,
                'status'             => $validated['status'],
                'status_date'        => $validated['status_date'] ?? now(),
            ]);
            $msg = 'Dissertation saved successfully.';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'item'    => $dissertation,
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Delete a dissertation (Trainee only, before approval).
     */
    public function deleteDissertation(Request $request, int $traineeId, int $dissertationId): JsonResponse|RedirectResponse
    {
        $this->authorizeTraineeOnly($traineeId);

        $dissertation = TraineeDissertation::where('user_id', $traineeId)->findOrFail($dissertationId);
        if (strtolower(trim((string) $dissertation->status)) === 'approved') {
            return back()->with('error', 'Approved dissertation records cannot be deleted.');
        }
        $dissertation->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Dissertation deleted.']);
        }

        return back()->with('success', 'Dissertation deleted.');
    }

    /**
     * Supervisor updates the approval status and remarks for a trainee's Workshop, Synopsis, or Dissertation.
     */
    public function updateStatus(Request $request, int $traineeId, string $type, int $itemId): JsonResponse|RedirectResponse
    {
        $this->authorizeSupervisorOnly($traineeId);

        $validated = $request->validate([
            'status'  => 'required|in:Approved,Discuss and Resubmit,Awaiting Approval',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $model = match (strtolower($type)) {
            'workshop'     => TraineeWorkshop::where('user_id', $traineeId)->findOrFail($itemId),
            'synopsis'     => TraineeSynopsis::where('user_id', $traineeId)->findOrFail($itemId),
            'dissertation' => TraineeDissertation::where('user_id', $traineeId)->findOrFail($itemId),
            default        => abort(404, 'Invalid academic record type.'),
        };

        // Once Approved by supervisor, status is locked and cannot be changed
        if (strtolower(trim((string) $model->status)) === 'approved') {
            $lockMsg = 'This record has already been approved and is locked. Its status can no longer be changed.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $lockMsg], 422);
            }
            return back()->with('error', $lockMsg);
        }

        $newRemarks = !empty(trim((string)($validated['remarks'] ?? ''))) ? trim((string)$validated['remarks']) : null;

        $updateData = [
            'status'      => $validated['status'],
            'status_date' => now(),
            'remarks'     => $newRemarks,
        ];

        $model->update($updateData);

        $label = ucfirst(strtolower($type));
        $msg = "{$label} status updated to '{$validated['status']}' successfully.";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'     => true,
                'message'     => $msg,
                'status'      => $model->status,
                'status_date' => $model->status_date ? $model->status_date->format('d/m/Y') : now()->format('d/m/Y'),
                'remarks'     => $model->remarks ?? '',
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Ensure only the trainee themselves can add/edit their own academic records.
     */
    private function authorizeTraineeOnly(int $traineeId): User
    {
        $userId = (int) session('user_id');
        $user = User::with('userType')->find($userId);

        if (! $user) {
            abort(401, 'Unauthorized');
        }

        if (! $user->isTrainee() || (int) $user->id !== (int) $traineeId) {
            abort(403, 'Only the trainee can add or resubmit Workshops, Synopsis, and Dissertation records.');
        }

        return $user;
    }

    /**
     * Ensure only an assigned Supervisor or Admin can update the status of a trainee's academic records.
     */
    private function authorizeSupervisorOnly(int $traineeId): User
    {
        $userId = (int) session('user_id');
        $user = User::with('userType')->find($userId);

        if (! $user) {
            abort(401, 'Unauthorized');
        }

        if ($user->isTrainee()) {
            abort(403, 'Trainees cannot approve or change the status of academic records.');
        }

        $isAdmin = $user->isAdmin();
        $assignedTraineeIds = $user->getAssignedTraineeIds();

        if (! $isAdmin && ! in_array($traineeId, $assignedTraineeIds, true)) {
            abort(403, 'You do not have access to manage this trainee.');
        }

        return $user;
    }
}
