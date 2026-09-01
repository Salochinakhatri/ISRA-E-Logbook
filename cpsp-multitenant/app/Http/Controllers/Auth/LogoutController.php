<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request): RedirectResponse
    {
        $userId = session('user_id');

        if ($userId) {
            User::find($userId)?->update(['remember_token' => null]);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        cookie()->queue(cookie()->forget('cpsp_remember'));

        return redirect()->route('login');
    }
}
