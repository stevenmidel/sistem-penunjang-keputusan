<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\UnitUsaha;
use App\Models\LayananLaundry;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class AdminLaundryUnitController extends Controller
{
    /**
     * Tampilkan semua unit usaha milik admin.
     */
    public function index()
    {
        $adminId = Auth::id();

        $units = UnitUsaha::with('layanan')
            ->where('admin_id', $adminId)
            ->get();

        return view('pages.AdminLaundry.UnitUsaha', compact('units'));
    }

    /**
     * Simpan unit usaha baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_laundry'   => 'required|string|max:255',
            'foto_laundry'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nama_jalan'     => 'required|string|max:255',
            'latitude'       => 'required|numeric',
            'longitude'      => 'required|numeric',

            'nama_layanan'   => 'required|array|min:1',
            'nama_layanan.*' => 'required|string|max:255',

            'harga'          => 'required|array|min:1',
            'harga.*'        => 'required|numeric|min:0',

            'estimasi'       => 'nullable|array',
            'estimasi.*'     => 'nullable|string|max:255',

            'weekday_open'   => 'required|date_format:H:i',
            'weekday_close'  => 'required|date_format:H:i|after:weekday_open',
            'weekend_open'   => 'required|date_format:H:i',
            'weekend_close'  => 'required|date_format:H:i|after:weekend_open',

            'keterangan'     => 'nullable|string',
        ]);

        try {

            DB::transaction(function () use ($request, $validated) {

                // =====================================================
                // UPLOAD FOTO
                // Disimpan langsung ke public/uploads/laundry
                // =====================================================

                $fotoPath = null;

                if ($request->hasFile('foto_laundry')) {

                    $file = $request->file('foto_laundry');

                    $filename = time() . '_' . uniqid() . '.' .
                        $file->getClientOriginalExtension();

                    $file->move(
                        public_path('uploads/laundry'),
                        $filename
                    );

                    // Path yang disimpan ke database
                    $fotoPath = 'uploads/laundry/' . $filename;
                }

                // =====================================================
                // SIMPAN UNIT USAHA
                // =====================================================

                $unitUsaha = UnitUsaha::create([
                    'admin_id'       => Auth::id(),
                    'nama_laundry'   => $validated['nama_laundry'],
                    'foto_laundry'   => $fotoPath,
                    'nama_jalan'     => $validated['nama_jalan'],
                    'latitude'       => $validated['latitude'],
                    'longitude'      => $validated['longitude'],
                    'weekday_open'   => $validated['weekday_open'],
                    'weekday_close'  => $validated['weekday_close'],
                    'weekend_open'   => $validated['weekend_open'],
                    'weekend_close'  => $validated['weekend_close'],
                    'keterangan'     => $validated['keterangan'] ?? null,
                    'rating'         => null,
                ]);

                ActivityLog::create([
                    'user_id'   => Auth::id(),
                    'aksi'      => 'CREATE',
                    'modul'     => 'Unit Usaha',
                    'data_id'   => $unitUsaha->id,
                    'deskripsi' => 'Menambahkan unit usaha "' . $unitUsaha->nama_laundry . '"',
                ]);

                // =====================================================
                // SIMPAN SEMUA LAYANAN
                // =====================================================


                foreach ($validated['nama_layanan'] as $index => $namaLayanan) {

                    $layanan = LayananLaundry::create([
                        'unit_usaha_id' => $unitUsaha->id,
                        'nama_layanan'  => $namaLayanan,
                        'harga'         => $validated['harga'][$index],
                        'estimasi'      => $validated['estimasi'][$index] ?? null,
                        'deskripsi'     => null,
                    ]);

                    ActivityLog::create([
                        'user_id'   => Auth::id(),
                        'aksi'      => 'CREATE',
                        'modul'     => 'Layanan',
                        'data_id'   => $layanan->id,
                        'deskripsi' => 'Menambahkan layanan "' . $layanan->nama_layanan .
                            '" pada unit "' . $unitUsaha->nama_laundry . '"',
                    ]);
                }
            });

            return redirect()
                ->route('admin.laundry.unit.index')
                ->with('success', 'Unit usaha berhasil ditambahkan.');
        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan : ' . $e->getMessage());
        }
    }

    /**
     * Form edit.
     */
    public function edit($id)
    {
        $unit = UnitUsaha::with('layanan')->findOrFail($id);

        if ($unit->admin_id !== Auth::id()) {
            abort(403);
        }

        return view('pages.AdminLaundry.Edit', compact('unit'));
    }

    /**
     * Update unit usaha.
     */
    public function update(Request $request, $id)
    {
        $unit = UnitUsaha::findOrFail($id);

        if ($unit->admin_id !== Auth::id()) {
            abort(403);
        }

        // =========================================================
        // NORMALISASI JAM
        // =========================================================

        $request->merge([
            'weekday_open'  => Carbon::parse($request->weekday_open)->format('H:i'),
            'weekday_close' => Carbon::parse($request->weekday_close)->format('H:i'),
            'weekend_open'  => Carbon::parse($request->weekend_open)->format('H:i'),
            'weekend_close' => Carbon::parse($request->weekend_close)->format('H:i'),
        ]);

        $validated = $request->validate([
            'nama_laundry'   => 'required|string|max:255',
            'foto_laundry'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_jalan'     => 'required|string|max:255',

            'latitude'       => 'required|numeric',
            'longitude'      => 'required|numeric',

            'nama_layanan'   => 'required|array|min:1',
            'nama_layanan.*' => 'required|string|max:255',

            'harga'          => 'required|array|min:1',
            'harga.*'        => 'required|numeric|min:0',

            'estimasi'       => 'nullable|array',
            'estimasi.*'     => 'nullable|string|max:255',

            'weekday_open'   => 'required|date_format:H:i',
            'weekday_close'  => 'required|date_format:H:i|after:weekday_open',

            'weekend_open'   => 'required|date_format:H:i',
            'weekend_close'  => 'required|date_format:H:i|after:weekend_open',

            'keterangan'     => 'nullable|string',
        ]);

        try {

            DB::transaction(function () use ($request, $validated, $unit) {

                // =====================================================
                // UPLOAD FOTO BARU
                // =====================================================

                if ($request->hasFile('foto_laundry')) {

                    // -------------------------------------------------
                    // Hapus foto lama
                    // -------------------------------------------------

                    if (
                        $unit->foto_laundry &&
                        file_exists(public_path($unit->foto_laundry))
                    ) {
                        unlink(public_path($unit->foto_laundry));
                    }

                    // -------------------------------------------------
                    // Upload foto baru
                    // -------------------------------------------------

                    $file = $request->file('foto_laundry');

                    $filename = time() . '_' . uniqid() . '.' .
                        $file->getClientOriginalExtension();

                    $file->move(
                        public_path('uploads/laundry'),
                        $filename
                    );

                    // Simpan path baru ke database
                    $unit->foto_laundry = 'uploads/laundry/' . $filename;
                }

                // =====================================================
                // UPDATE UNIT USAHA
                // =====================================================

                $unit->update([
                    'nama_laundry'  => $validated['nama_laundry'],
                    'nama_jalan'    => $validated['nama_jalan'],
                    'latitude'      => $validated['latitude'],
                    'longitude'     => $validated['longitude'],

                    'weekday_open'  => $validated['weekday_open'],
                    'weekday_close' => $validated['weekday_close'],

                    'weekend_open'  => $validated['weekend_open'],
                    'weekend_close' => $validated['weekend_close'],

                    'keterangan'    => $validated['keterangan'] ?? null,

                    'foto_laundry'  => $unit->foto_laundry,
                ]);

                ActivityLog::create([
                    'user_id'   => Auth::id(),
                    'aksi'      => 'UPDATE',
                    'modul'     => 'Unit Usaha',
                    'data_id'   => $unit->id,
                    'deskripsi' => 'Memperbarui unit usaha "' . $unit->nama_laundry . '"',
                ]);

                /*
                |--------------------------------------------------------------------------
                | UPDATE / TAMBAH LAYANAN
                |--------------------------------------------------------------------------
                */

                $existingIds = [];

                foreach ($validated['nama_layanan'] as $i => $nama) {

                    $layananId = $request->layanan_id[$i] ?? null;

                    // =================================================
                    // Jika layanan lama
                    // =================================================

                    if ($layananId) {

                        $layanan = LayananLaundry::where('id', $layananId)
                            ->where('unit_usaha_id', $unit->id)
                            ->first();

                        if ($layanan) {

                            $layanan->update([
                                'nama_layanan' => $nama,
                                'harga'        => $validated['harga'][$i],
                                'estimasi'     => $validated['estimasi'][$i] ?? null,
                            ]);

                            ActivityLog::create([
                                'user_id'   => Auth::id(),
                                'aksi'      => 'UPDATE',
                                'modul'     => 'Layanan',
                                'data_id'   => $layanan->id,
                                'deskripsi' => 'Memperbarui layanan "' . $layanan->nama_layanan .
                                    '" pada unit "' . $unit->nama_laundry . '"',
                            ]);

                            $existingIds[] = $layanan->id;
                        }
                    }

                    // =================================================
                    // Jika layanan baru
                    // =================================================

                    else {

                        $layanan = LayananLaundry::create([
                            'unit_usaha_id' => $unit->id,
                            'nama_layanan'  => $nama,
                            'harga'         => $validated['harga'][$i],
                            'estimasi'      => $validated['estimasi'][$i] ?? null,
                            'deskripsi'     => null,
                            'rating'        => null,
                        ]);

                        ActivityLog::create([
                            'user_id'   => Auth::id(),
                            'aksi'      => 'CREATE',
                            'modul'     => 'Layanan',
                            'data_id'   => $layanan->id,
                            'deskripsi' => 'Menambahkan layanan "' . $layanan->nama_layanan .
                                '" pada unit "' . $unit->nama_laundry . '"',
                        ]);

                        $existingIds[] = $layanan->id;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Hapus layanan yang dihapus dari form
                |--------------------------------------------------------------------------
                */

                $layananYangDihapus = LayananLaundry::where('unit_usaha_id', $unit->id)
                    ->whereNotIn('id', $existingIds)
                    ->get();

                foreach ($layananYangDihapus as $layanan) {

                    ActivityLog::create([
                        'user_id'   => Auth::id(),
                        'aksi'      => 'DELETE',
                        'modul'     => 'Layanan',
                        'data_id'   => $layanan->id,
                        'deskripsi' => 'Menghapus layanan "' . $layanan->nama_layanan .
                            '" dari unit "' . $unit->nama_laundry . '"',
                    ]);

                    $layanan->delete();
                }
            });

            return redirect()
                ->route('admin.laundry.unit.index')
                ->with('success', 'Unit usaha berhasil diperbarui.');
        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Hapus unit usaha.
     */
    public function destroy($id)
    {
        $unit = UnitUsaha::findOrFail($id);

        if ($unit->admin_id !== Auth::id()) {
            abort(403);
        }

        try {

            // =========================================================
            // HAPUS FOTO
            // =========================================================

            if (
                $unit->foto_laundry &&
                file_exists(public_path($unit->foto_laundry))
            ) {
                unlink(public_path($unit->foto_laundry));
            }

            // =========================================================
            // HAPUS DATA UNIT USAHA
            // =========================================================

            ActivityLog::create([
                'user_id'   => Auth::id(),
                'aksi'      => 'DELETE',
                'modul'     => 'Unit Usaha',
                'data_id'   => $unit->id,
                'deskripsi' => 'Menghapus unit usaha "' . $unit->nama_laundry . '"',
            ]);

            $unit->delete();

            return redirect()
                ->route('admin.laundry.unit.index')
                ->with('success', 'Unit usaha berhasil dihapus.');
        } catch (QueryException $e) {

            return back()
                ->with('error', 'Unit usaha gagal dihapus karena masih digunakan.');
        }
    }
}
