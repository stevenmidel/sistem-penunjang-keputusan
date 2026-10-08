<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UnitUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\LayananLaundry;

class SuperAdminLaundryController extends Controller
{
    /**
     * ================== LIST LAUNDRY ==================
     */
    public function index()
    {
        try {
            $units = UnitUsaha::with('layanan')->get();

            return view('pages.SuperAdmin.Laundry', compact('units'));
        } catch (\Exception $e) {

            Log::error('Gagal memuat data laundry: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal memuat data laundry');
        }
    }

    /**
     * ================== DETAIL LAUNDRY ==================
     */
    public function show($id)
    {
        try {
            $unit = UnitUsaha::with('admin')->findOrFail($id);

            return view('pages.SuperAdmin.DetailLaundry', compact('unit'));
        } catch (\Exception $e) {

            Log::error('Gagal membuka detail laundry: ' . $e->getMessage());

            return redirect()
                ->route('superadmin.laundry')
                ->with('error', 'Data laundry tidak ditemukan');
        }
    }

    /**
     * ================== SIMPAN RATING ==================
     */
    public function storeRating(Request $request, $id)
    {
        try {
            $request->validate([
                'rating' => 'required|numeric|min:1|max:5',
            ]);

            $unit = UnitUsaha::with('layanan')->findOrFail($id);

            $unit->rating = $request->rating;
            $unit->save();

            return redirect()
                ->route('superadmin.laundry.detail', $id)
                ->with('success', 'Rating berhasil disimpan');
        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()->back()
                ->withErrors($e->validator)
                ->with('error', 'Validasi gagal, periksa input Anda');
        } catch (\Exception $e) {

            Log::error('Gagal menyimpan rating: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan rating');
        }
    }

    public function updateRatingLayanan(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        $layanan = LayananLaundry::findOrFail($id);

        $layanan->rating = $request->rating;
        $layanan->save();

        return redirect()
            ->back()
            ->with('success', 'Rating layanan berhasil diperbarui.');
    }
}
