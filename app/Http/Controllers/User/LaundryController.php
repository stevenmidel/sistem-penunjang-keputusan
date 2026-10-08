<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UnitUsaha;

class LaundryController extends Controller
{
    public function show(UnitUsaha $unitUsaha)
    {
        // Ambil laundry + ulasan + user (anti N+1)
        $unitUsaha->load([
            'ulasans' => function ($q) {
                $q->latest('tanggal_ulasan');
            },
            'ulasans.user'
        ]);

        return view('pages.user.detail.DetailLaundry', [
            'laundry' => $unitUsaha,
            'ulasans' => $unitUsaha->ulasans
        ]);
    }
}
