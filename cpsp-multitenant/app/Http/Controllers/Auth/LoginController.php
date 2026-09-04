<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserType;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(private TenantManager $tenantManager) {}

    public function showForm(): View|\Illuminate\Http\RedirectResponse
    {
        $tenant = $this->tenantManager->get();
        $userId = session('user_id');
        $sessionTenantId = session('tenant_id');

        if ($userId && $tenant && (int) $sessionTenantId === (int) $tenant->id && User::find($userId)) {
            return redirect()->route('dashboard');
        }

        if ($sessionTenantId && $tenant && (int) $sessionTenantId !== (int) $tenant->id) {
            session()->forget(['user_id', 'user_type_id', 'username', 'email', 'user_type', 'tenant_id']);
        }

        $userTypes = UserType::orderBy('id')->get();

        return view('auth.login', compact('userTypes', 'tenant'));
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'user_type_id' => ['required', 'integer', 'min:1'],
            'username'     => ['required', 'string', 'max:100'],
            'password'     => ['required', 'string'],
        ]);

        $userTypeId = (int) $request->input('user_type_id');
        $username   = trim((string) $request->input('username'));
        $password   = (string) $request->input('password');
        $remember   = (bool) $request->input('remember_me');

        $user = User::with('userType')
            ->where('username', $username)
            ->where('user_type_id', $userTypeId)
            ->first();

        if (! $user || ! $user->verifyPassword($password)) {
            return redirect()->route('login')
                ->with('login_error', 'Invalid username, password, or user type.');
        }

        // Regenerate session on login
        $request->session()->regenerate();

        session([
            'user_id'      => $user->id,
            'user_type_id' => $user->user_type_id,
            'username'     => $user->username,
            'email'        => $user->email,
            'user_type'    => $user->userType?->name ?? '',
            'tenant_id'    => $user->tenant_id,
        ]);

        // Handle "Remember me" token
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $user->update(['remember_token' => $token]);
            cookie()->queue(
                cookie('cpsp_remember', $token, 60 * 24 * 30, '/', null, false, true)
            );
        } else {
            $user->update(['remember_token' => null]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Auto-login via "Remember me" cookie.
     * Called from the showForm method if session is empty.
     */
    public function attemptRememberLogin(Request $request): ?RedirectResponse
    {
        $token = $request->cookie('cpsp_remember');

        if (! is_string($token) || strlen($token) !== 64) {
            return null;
        }

        $user = User::with('userType')
            ->where('remember_token', $token)
            ->first();

        if (! $user) {
            cookie()->queue(cookie()->forget('cpsp_remember'));

            return null;
        }

        $request->session()->regenerate();
        session([
            'user_id'      => $user->id,
            'user_type_id' => $user->user_type_id,
            'username'     => $user->username,
            'email'        => $user->email,
            'user_type'    => $user->userType?->name ?? '',
        ]);

        return redirect()->route('dashboard');
    }
}
