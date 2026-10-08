<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    public function store(Request $request, $unitId)
    {
        $request->validate([
            'reviews.*.nama'    => 'required|string|max:100',
            'reviews.*.tanggal' => 'required|date',
            'reviews.*.ulasan'  => 'required|string',
        ]);

        foreach ($request->reviews as $review) {

            // Lewati jika ulasan kosong
            if (empty($review['ulasan'])) {
                continue;
            }

            Ulasan::create([
                'user_id'        => Auth::id(),
                'unit_usaha_id'  => $unitId,
                'nama_pemberi'   => $review['nama'],
                'tanggal_ulasan' => $review['tanggal'],
                'ulasan'         => $review['ulasan'],
            ]);
        }

        return back()->with('success', 'Ulasan berhasil disimpan');
    }
}
