<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UnitUsaha;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        // ================= ADMIN LAUNDRY =================
        $totalAdminLaundry = User::where('role', 'admin_laundry')
            ->where('status', true)
            ->count();

        // ================= UNIT USAHA =================
        $totalUsaha = UnitUsaha::count();

        // ================= USER ACTIVE / INACTIVE =================
        $userActive = User::where('status', true)->count();
        $userInactive = User::where('status', false)->count();

        // ================= SINGLE RETURN =================
        return view('pages.SuperAdmin.Dashboard', compact(
            'totalAdminLaundry',
            'totalUsaha',
            'userActive',
            'userInactive'
        ));
    }
}
