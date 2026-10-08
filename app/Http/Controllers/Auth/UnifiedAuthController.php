<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnifiedAuthController extends Controller
{
    /**
     * ================= TAMPILKAN FORM LOGIN =================
     */
    public function showLogin()
    {
        return view('pages.Auth.Login');
    }

    /**
     * ================= PROSES LOGIN =================
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'login' => 'Username atau password salah.'
            ])->onlyInput('username');
        }

        $user = Auth::user();

        // Cek status aktif
        if (!$user->status) {
            Auth::logout();

            return back()->withErrors([
                'login' => 'Akun ini tidak aktif.'
            ])->onlyInput('username');
        }

        $request->session()->regenerate();

        // Redirect sesuai role
        return match ($user->role) {
            'super_admin'      => redirect()->route('superadmin.dashboard'),
            'admin_laundry'    => redirect()->route('admin.laundry.dashboard'),
            'pengawas_laundry' => redirect()->route('pengawas.dashboard'),
            default            => $this->rejectUnknownRole($request),
        };
    }

    /**
     * Fallback kalau role tidak dikenali sama sekali
     * (jaga-jaga, seharusnya tidak pernah terjadi)
     */
    protected function rejectUnknownRole(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'login' => 'Role akun tidak dikenali. Hubungi administrator.'
        ]);
    }

    /**
     * ================= LOGOUT (UNIVERSAL) =================
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
