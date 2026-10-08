<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class PengawasController extends Controller
{
    /**
     * Dashboard Pengawas
     */
    public function index()
    {
        $pengawas = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Ambil admin laundry yang diawasi
        |--------------------------------------------------------------------------
        */

        $admins = User::where('role', 'admin_laundry')
            ->where('pengawas_id', $pengawas->id)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ambil aktivitas dari seluruh admin yang diawasi
        |--------------------------------------------------------------------------
        */

        $adminIds = $admins->pluck('id');

        $activities = ActivityLog::with('user')
            ->whereIn('user_id', $adminIds)
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $totalAdmin = $admins->count();

        $totalAktivitas = $activities->count();

        $totalCreate = $activities
            ->where('aksi', 'CREATE')
            ->count();

        $totalUpdate = $activities
            ->where('aksi', 'UPDATE')
            ->count();

        $totalDelete = $activities
            ->where('aksi', 'DELETE')
            ->count();

        return view('pages.Pengawas.Dashboard', compact(
            'pengawas',
            'admins',
            'activities',
            'totalAdmin',
            'totalAktivitas',
            'totalCreate',
            'totalUpdate',
            'totalDelete'
        ));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('pengawas.login');
    }
}
