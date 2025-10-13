<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            $role = $request->user()->role;
            $target = $role === 'admin' ? route('dashboard.admin', absolute: false) : route('dashboard.dosen', absolute: false);
            return redirect()->intended($target.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $role = $request->user()->role;
        $target = $role === 'admin' ? route('dashboard.admin', absolute: false) : route('dashboard.dosen', absolute: false);
        return redirect()->intended($target.'?verified=1');
    }
}
