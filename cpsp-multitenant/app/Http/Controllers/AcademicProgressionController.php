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
use Illuminate\View\View;

class AcademicProgressionController extends Controller
{
    public function __construct(private TenantManager $tenantManager) {}

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

        $workshops = TraineeWorkshop::where('user_id', $user->id)->orderBy('id', 'asc')->get();
        $synopses = TraineeSynopsis::where('user_id', $user->id)->orderBy('id', 'asc')->get();
        $dissertations = TraineeDissertation::where('user_id', $user->id)->orderBy('id', 'asc')->get();
        $tenant = $user->tenant ?? $this->tenantManager->get();

        return view('academic.trainee-index', compact('user', 'workshops', 'synopses', 'dissertations', 'tenant'));
    }

    /**
     * Add or update a workshop dynamically.
     */
    public function storeWorkshop(Request $request, int $traineeId): JsonResponse|RedirectResponse
    {
        $supervisor = $this->authorizeSupervisorForTrainee($traineeId);
        if ($supervisor instanceof JsonResponse || $supervisor instanceof RedirectResponse) {
            return $supervisor;
        }

        $validated = $request->validate([
            'id'            => 'nullable|integer',
            'workshop_name' => 'required|string|max:255',
            'time_duration' => 'required|string|max:100',
            'status'        => 'required|string|max:100',
            'status_date'   => 'nullable|date',
        ]);

        $trainee = User::findOrFail($traineeId);

        if (!empty($validated['id'])) {
            $workshop = TraineeWorkshop::where('user_id', $traineeId)->findOrFail($validated['id']);
            $workshop->update([
                'workshop_name' => $validated['workshop_name'],
                'time_duration' => $validated['time_duration'],
                'status'        => $validated['status'],
                'status_date'   => $validated['status_date'] ?? $workshop->status_date,
            ]);
        } else {
            $workshop = TraineeWorkshop::create([
                'tenant_id'     => $trainee->tenant_id,
                'user_id'       => $trainee->id,
                'workshop_name' => $validated['workshop_name'],
                'time_duration' => $validated['time_duration'],
                'status'        => $validated['status'],
                'status_date'   => $validated['status_date'] ?? now(),
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Workshop saved successfully.',
                'item'    => $workshop,
            ]);
        }

        return back()->with('success', 'Workshop updated successfully.');
    }

    /**
     * Delete a workshop dynamically.
     */
    public function deleteWorkshop(Request $request, int $traineeId, int $workshopId): JsonResponse|RedirectResponse
    {
        $auth = $this->authorizeSupervisorForTrainee($traineeId);
        if ($auth instanceof JsonResponse || $auth instanceof RedirectResponse) {
            return $auth;
        }

        $workshop = TraineeWorkshop::where('user_id', $traineeId)->findOrFail($workshopId);
        $workshop->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Workshop deleted.']);
        }

        return back()->with('success', 'Workshop deleted successfully.');
    }

    /**
     * Add or update a synopsis dynamically.
     */
    public function storeSynopsis(Request $request, int $traineeId): JsonResponse|RedirectResponse
    {
        $auth = $this->authorizeSupervisorForTrainee($traineeId);
        if ($auth instanceof JsonResponse || $auth instanceof RedirectResponse) {
            return $auth;
        }

        $validated = $request->validate([
            'id'            => 'nullable|integer',
            'synopsis_name' => 'required|string|max:500',
            'status'        => 'required|string|max:100',
            'status_date'   => 'nullable|date',
            'remarks'       => 'nullable|string',
        ]);

        $trainee = User::findOrFail($traineeId);

        if (!empty($validated['id'])) {
            $synopsis = TraineeSynopsis::where('user_id', $traineeId)->findOrFail($validated['id']);
            $synopsis->update([
                'synopsis_name' => $validated['synopsis_name'],
                'status'        => $validated['status'],
                'status_date'   => $validated['status_date'] ?? $synopsis->status_date,
                'remarks'       => $validated['remarks'] ?? $synopsis->remarks,
            ]);
        } else {
            $synopsis = TraineeSynopsis::create([
                'tenant_id'     => $trainee->tenant_id,
                'user_id'       => $trainee->id,
                'synopsis_name' => $validated['synopsis_name'],
                'status'        => $validated['status'],
                'status_date'   => $validated['status_date'] ?? now(),
                'remarks'       => $validated['remarks'] ?? null,
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Synopsis saved successfully.',
                'item'    => $synopsis,
            ]);
        }

        return back()->with('success', 'Synopsis updated successfully.');
    }

    /**
     * Delete a synopsis dynamically.
     */
    public function deleteSynopsis(Request $request, int $traineeId, int $synopsisId): JsonResponse|RedirectResponse
    {
        $auth = $this->authorizeSupervisorForTrainee($traineeId);
        if ($auth instanceof JsonResponse || $auth instanceof RedirectResponse) {
            return $auth;
        }

        $synopsis = TraineeSynopsis::where('user_id', $traineeId)->findOrFail($synopsisId);
        $synopsis->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Synopsis deleted.']);
        }

        return back()->with('success', 'Synopsis deleted.');
    }

    /**
     * Add or update a dissertation dynamically.
     */
    public function storeDissertation(Request $request, int $traineeId): JsonResponse|RedirectResponse
    {
        $auth = $this->authorizeSupervisorForTrainee($traineeId);
        if ($auth instanceof JsonResponse || $auth instanceof RedirectResponse) {
            return $auth;
        }

        $validated = $request->validate([
            'id'                 => 'nullable|integer',
            'application_date'   => 'required|date',
            'status'             => 'required|string|max:100',
            'status_date'        => 'nullable|date',
            'dissertation_title' => 'nullable|string|max:500',
            'remarks'            => 'nullable|string',
        ]);

        $trainee = User::findOrFail($traineeId);

        if (!empty($validated['id'])) {
            $dissertation = TraineeDissertation::where('user_id', $traineeId)->findOrFail($validated['id']);
            $dissertation->update([
                'application_date'   => $validated['application_date'],
                'status'             => $validated['status'],
                'status_date'        => $validated['status_date'] ?? $dissertation->status_date,
                'dissertation_title' => $validated['dissertation_title'] ?? $dissertation->dissertation_title,
                'remarks'            => $validated['remarks'] ?? $dissertation->remarks,
            ]);
        } else {
            $dissertation = TraineeDissertation::create([
                'tenant_id'          => $trainee->tenant_id,
                'user_id'            => $trainee->id,
                'application_date'   => $validated['application_date'],
                'status'             => $validated['status'],
                'status_date'        => $validated['status_date'] ?? now(),
                'dissertation_title' => $validated['dissertation_title'] ?? null,
                'remarks'            => $validated['remarks'] ?? null,
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Dissertation saved successfully.',
                'item'    => $dissertation,
            ]);
        }

        return back()->with('success', 'Dissertation updated successfully.');
    }

    /**
     * Delete a dissertation dynamically.
     */
    public function deleteDissertation(Request $request, int $traineeId, int $dissertationId): JsonResponse|RedirectResponse
    {
        $auth = $this->authorizeSupervisorForTrainee($traineeId);
        if ($auth instanceof JsonResponse || $auth instanceof RedirectResponse) {
            return $auth;
        }

        $dissertation = TraineeDissertation::where('user_id', $traineeId)->findOrFail($dissertationId);
        $dissertation->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Dissertation deleted.']);
        }

        return back()->with('success', 'Dissertation deleted.');
    }

    /**
     * Check supervisor or trainee self authorization.
     */
    private function authorizeSupervisorForTrainee(int $traineeId): ?User
    {
        $userId = (int) session('user_id');
        $user = User::with('userType')->find($userId);

        if (! $user) {
            abort(401, 'Unauthorized');
        }

        // Trainee can update their own
        if ($user->id === $traineeId) {
            return $user;
        }

        // Supervisor / Admin
        if ($user->isTrainee()) {
            abort(403, 'Forbidden');
        }

        $isAdmin = $user->isAdmin();
        $assignedTraineeIds = $user->getAssignedTraineeIds();

        if (! $isAdmin && ! in_array($traineeId, $assignedTraineeIds, true)) {
            abort(403, 'You do not have access to manage this trainee.');
        }

        return $user;
    }
}
