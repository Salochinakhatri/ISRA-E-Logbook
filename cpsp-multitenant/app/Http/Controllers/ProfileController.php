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
        $stats = [
            'trainees_count' => User::whereHas('userType', fn($q) => $q->where('name', 'Trainee'))->count(),
            'approved_count' => TrainingEntry::where('entry_status', 'Approved')->count(),
            'pending_count'  => TrainingEntry::where('entry_status', 'Awaiting Approval')->count(),
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
