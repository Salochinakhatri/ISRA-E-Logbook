<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TrainingEntry;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show supervisor (or active user) profile page.
     */
    public function show(): View|RedirectResponse
    {
        $userId = (int) session('user_id');
        $user = User::with(['userType', 'profile'])->find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        // Supervisor specific stats
        $isAdmin = $user->isAdmin();
        $assignedTraineeIds = $user->getAssignedTraineeIds();

        $entryScope = function ($q) use ($isAdmin, $userId, $assignedTraineeIds) {
            if (! $isAdmin) {
                $q->where(function ($sq) use ($userId, $assignedTraineeIds) {
                    $sq->where('supervisor_id', $userId)
                       ->orWhereIn('user_id', !empty($assignedTraineeIds) ? $assignedTraineeIds : [-1]);
                });
            }
        };

        $stats = [
            'trainees_count' => $isAdmin
                ? User::where(fn($q) => $q->whereHas('userType', fn($sub) => $sub->where('name', 'Trainee'))->orWhereHas('roles', fn($sub) => $sub->where('slug', 'trainee')))->count()
                : count($assignedTraineeIds),
            'approved_count' => TrainingEntry::where('entry_status', 'Approved')->tap($entryScope)->count(),
            'pending_count'  => TrainingEntry::where('entry_status', 'Awaiting Approval')->tap($entryScope)->count(),
        ];

        return view('profile.show', compact('user', 'stats'));
    }

    /**
     * Update user profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $userId = (int) session('user_id');
        $user = User::findOrFail($userId);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'phone'     => ['nullable', 'string', 'max:30'],
            'email'     => ['required', 'email', 'max:255', "unique:users,email,{$userId}"],
            'bio'       => ['nullable', 'string', 'max:500'],
        ]);

        $user->update([
            'email' => $validated['email'],
        ]);

        UserProfile::updateOrCreate(
            ['user_id' => $userId],
            [
                'full_name' => $validated['full_name'],
                'phone'     => $validated['phone'] ?? '',
                'bio'       => $validated['bio'] ?? '',
            ]
        );

        return redirect()->route('profile.show')->with('flash_ok', 'Profile details updated successfully.');
    }

    /**
     * Change user account password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $userId = (int) session('user_id');
        $user = User::findOrFail($userId);

        $validated = $request->validate([
            'current_password'      => ['required', 'string'],
            'password'              => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ]);

        if (! $user->verifyPassword($validated['current_password'])) {
            return redirect()->route('profile.show')->withErrors(['current_password' => 'The current password you entered is incorrect.']);
        }

        $user->update([
            'password' => password_hash($validated['password'], PASSWORD_BCRYPT),
        ]);

        return redirect()->route('profile.show')->with('flash_ok', 'Your password has been changed successfully.');
    }
}
