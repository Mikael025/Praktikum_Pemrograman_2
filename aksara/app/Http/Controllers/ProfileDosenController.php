<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileDosenController extends Controller
{

    /**
     * Display the dosen's profile form.
     */
    public function edit(Request $request): View
    {
        if ($request->user()->role !== 'dosen') {
            abort(403);
        }
        
        return view('profile.dosen.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the dosen's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        if ($request->user()->role !== 'dosen') {
            abort(403);
        }
        
        // Validate both basic profile and dosen-specific fields
        $validated = $request->validateWithBag('default', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
        ]);

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.dosen.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->user()->role !== 'dosen') {
            abort(403);
        }
        
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
