<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class VisitorController extends Controller
{
    /**
     * Menyimpan visitor baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        // Cek apakah browser sudah memiliki cookie
        if (Cookie::has('visitor_token')) {
            return redirect()->back();
        }

        // Generate token unik
        $visitorToken = Str::uuid()->toString();

        // Simpan ke database
        Visitor::create([
            'nama'           => trim($request->nama),
            'visitor_token'  => $visitorToken,
            'visit_count'    => 1,
            'first_visit'    => now(),
            'last_visit'     => now(),
        ]);

        // Simpan cookie selama 1 tahun
        Cookie::queue(
            'visitor_token',
            $visitorToken,
            60 * 24 * 365
        );

        return redirect()->back()->with('success', 'Selamat datang di LaundryFinder!');
    }
}
