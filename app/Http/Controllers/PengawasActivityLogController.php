<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class PengawasActivityLogController extends Controller
{
    /**
     * Tampilkan activity log admin laundry
     * yang berada di bawah pengawas yang sedang login.
     */
    public function index()
    {
        $pengawasId = Auth::id();

        $logs = ActivityLog::with('user')
            ->whereHas('user', function ($query) use ($pengawasId) {
                $query->where('pengawas_id', $pengawasId)
                    ->where('role', 'admin_laundry');
            })
            ->latest()
            ->paginate(20);

        return view(
            'pages.Pengawas.ActivityLog',
            compact('logs')
        );
    }
}
