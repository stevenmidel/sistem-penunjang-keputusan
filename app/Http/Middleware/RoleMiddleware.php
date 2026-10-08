<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        /*
        |--------------------------------------------------------------------------
        | Pastikan user sudah login
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {
            abort(401);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil user yang sedang login
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Pastikan user memiliki role yang diperbolehkan
        |--------------------------------------------------------------------------
        */

        if (!$user || !in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
