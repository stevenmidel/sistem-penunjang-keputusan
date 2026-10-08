<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengawasAuthController extends Controller
{
    /**
     * Login Pengawas
     */

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            /*
        |--------------------------------------------------------------------------
        | Validasi Role
        |--------------------------------------------------------------------------
        */

            if ($user->role !== 'pengawas_laundry') {

                Auth::logout();

                return back()->withErrors([
                    'login' => 'Akun ini bukan Pengawas Laundry.'
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | Validasi Status
        |--------------------------------------------------------------------------
        */

            if (!$user->status) {

                Auth::logout();

                return back()->withErrors([
                    'login' => 'Akun Pengawas Laundry tidak aktif.'
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

            $request->session()->regenerate();

            return redirect()->route('pengawas.dashboard');
        }

        return back()->withErrors([
            'login' => 'Username atau password salah.'
        ]);
    }



    /**
     * Logout Pengawas
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('pengawas.login');
    }
}
