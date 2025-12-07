<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  array<int, string>  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, $roles, true)) {
            // Redirect to appropriate dashboard based on user role
            if ($user && $user->role === 'admin') {
                return redirect()->route('dashboard.admin')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            } elseif ($user && $user->role === 'dosen') {
                return redirect()->route('dashboard.dosen')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            abort(403, 'Unauthorized access');
        }
        return $next($request);
    }
}


