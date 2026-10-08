<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PengawasLaundryController extends Controller
{
    /**
     * Menampilkan seluruh pengawas laundry.
     */
    public function index()
    {
        $pengawas = User::where('role', 'pengawas_laundry')
            ->withCount('adminDiawasi')
            ->orderBy('nama_layanan')
            ->get();

        return view(
            'pages.SuperAdmin.PengawasLaundry',
            compact('pengawas')
        );
    }

    /**
     * Form tambah pengawas.
     */
    public function create()
    {
        return view('pages.SuperAdmin.TambahPengawas');
    }

    /**
     * Simpan pengawas baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'username'     => 'required|string|max:255|unique:users,username',
            'password'     => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'nama_layanan' => $validated['nama_layanan'],
            'email'        => $validated['email'],
            'username'     => $validated['username'],
            'password'     => Hash::make($validated['password']),
            'foto'         => null,
            'role'         => 'pengawas_laundry',
            'status'       => true,
        ]);

        return redirect()
            ->route('superadmin.pengawas.index')
            ->with('success', 'Pengawas Laundry berhasil ditambahkan.');
    }

    /**
     * Nonaktifkan / aktifkan pengawas.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $pengawas = User::where('role', 'pengawas_laundry')
            ->findOrFail($id);

        $pengawas->status = $request->status === 'aktif';
        $pengawas->save();

        return back()->with(
            'success',
            'Status Pengawas Laundry berhasil diperbarui.'
        );
    }
}
