<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            // Admin Laundry
            if ($request->is('admin-laundry/*')) {
                return route('admin.laundry.login');
            }

            // Super Admin
            if ($request->is('admin/*')) {
                return route('superadmin.login');
            }

            // Pengawas Laundry
            if ($request->is('pengawas/*')) {
                return route('pengawas.login');
            }

            // User biasa
            return route('user.home');
        }
    }
}
