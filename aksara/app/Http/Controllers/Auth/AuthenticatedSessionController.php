<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $intendedRole = $request->input('intended_role');
        $user = $request->user();

        // Jika user memiliki role di DB, prioritaskan role DB untuk keamanan
        $effectiveRole = $user->role ?? $intendedRole;

        if ($effectiveRole === 'admin') {
            return redirect()->intended(route('dashboard.admin', absolute: false));
        }

        // default ke dosen
        return redirect()->intended(route('dashboard.dosen', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
