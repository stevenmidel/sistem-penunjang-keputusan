<?php

namespace App\Http\Controllers\AdminLaundry;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\UnitUsaha;
use App\Models\Ulasan;

class DashboardController extends Controller
{
    public function index()
    {
        $adminId = Auth::id();

        /* ================= TOTAL UNIT ================= */
        $totalUnit = UnitUsaha::where('admin_id', $adminId)->count();

        /* ================= AVG RATING ================= */
        $avgRating = UnitUsaha::where('admin_id', $adminId)
            ->whereNotNull('rating')
            ->avg('rating') ?? 0;

        /* ================= TOTAL ULASAN ================= */
        $totalReview = Ulasan::whereHas('unitUsaha', function ($q) use ($adminId) {
            $q->where('admin_id', $adminId);
        })->count();

        /* ================= LIST RATING PER USAHA ================= */
        $units = UnitUsaha::where('admin_id', $adminId)
            ->select('id', 'nama_laundry', 'rating')
            ->orderByDesc('rating')
            ->get();

        return view('pages.AdminLaundry.Dashboard', compact(
            'totalUnit',
            'avgRating',
            'totalReview',
            'units'
        ));
    }
}
