<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\ActivityLog;

class SuperAdminAuthController extends Controller
{
    /**
     * ================= LOGIN SUPER ADMIN =================
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // Validasi role
            if ($user->role !== 'super_admin') {
                Auth::logout();

                return back()->withErrors([
                    'login' => 'Akun ini bukan Super Admin'
                ]);
            }

            // Validasi status
            if ($user->status !== true) {
                Auth::logout();

                return back()->withErrors([
                    'login' => 'Akun Super Admin tidak aktif'
                ]);
            }

            $request->session()->regenerate();

            return redirect()->route('superadmin.dashboard');
        }

        return back()->withErrors([
            'login' => 'Username atau password salah'
        ]);
    }


    /**
     * ================= LOGOUT SUPER ADMIN =================
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login');
    }


    /**
     * ================= PROFILE SUPER ADMIN =================
     */

    public function showProfile()
    {
        $user = Auth::user();

        return view(
            'pages.SuperAdmin.profileuser',
            compact('user')
        );
    }


    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nama_layanan' => 'nullable|string|max:255',
            'password' => 'nullable|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->nama_layanan = $request->nama_layanan;

        // Update password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update foto
        if ($request->hasFile('photo')) {

            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }

            if (
                $user->foto &&
                Storage::disk('public')->exists($user->foto)
            ) {
                Storage::disk('public')->delete($user->foto);
            }

            $path = $request->file('photo')
                ->store('profile', 'public');

            $user->foto = $path;
        }

        $user->save();

        return back()->with(
            'success',
            'Profil Super Admin berhasil diperbarui'
        );
    }


    /**
     * ================= ADMIN LAUNDRY MANAGEMENT =================
     */

    /**
     * Tampilkan semua Admin Laundry
     * sekaligus daftar Pengawas Laundry.
     */

    /**
     * ================= ADMIN LAUNDRY MANAGEMENT =================
     */

    // Tampilkan semua admin laundry + daftar pengawas
    public function index()
    {
        $admins = User::where('role', 'admin_laundry')
            ->with('pengawas')
            ->get();

        $pengawas = User::where('role', 'pengawas_laundry')
            ->get();

        return view(
            'pages.SuperAdmin.AdminLaundry',
            compact('admins', 'pengawas')
        );
    }




    /**
     * Update status Admin Laundry.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $admin = User::where('role', 'admin_laundry')
            ->findOrFail($id);

        $admin->status = $request->status === 'aktif';

        $admin->save();

        return back()->with(
            'success',
            'Status berhasil diperbarui'
        );
    }


    /**
     * ================= PENGAWAS LAUNDRY =================
     */

    /**
     * Membuat akun Pengawas Laundry.
     */
    public function indexPengawas()
    {
        $pengawas = User::where('role', 'pengawas_laundry')
            ->withCount([
                'adminLaundryDiawasi as jumlah_admin'
            ])
            ->get();

        return view(
            'pages.SuperAdmin.PengawasLaundry',
            compact('pengawas')
        );
    }


    public function storePengawas(Request $request)
    {
        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'username'     => 'required|string|max:255|unique:users,username',
            'password'     => 'required|string|min:6|confirmed',
        ]);

        $pengawas = User::create([
            'nama_layanan' => $validated['nama_layanan'],
            'email'        => $validated['email'],
            'username'     => $validated['username'],
            'password'     => Hash::make($validated['password']),
            'role'         => 'pengawas_laundry',
            'status'       => true,
        ]);

        ActivityLog::create([
            'user_id'   => Auth::id(),
            'aksi'      => 'CREATE',
            'modul'     => 'Pengawas Laundry',
            'data_id'   => $pengawas->id,
            'deskripsi' => 'Menambahkan pengawas laundry "' .
                $pengawas->nama_layanan . '"',
        ]);

        return back()->with(
            'success',
            'Pengawas Laundry berhasil ditambahkan.'
        );
    }

    /**
     * Menetapkan Pengawas Laundry
     * kepada Admin Laundry.
     */
    public function assignPengawas(Request $request, $id)
    {
        $validated = $request->validate([
            'pengawas_id' => 'required|exists:users,id',
        ]);

        $admin = User::where('role', 'admin_laundry')
            ->findOrFail($id);

        $pengawas = User::where('role', 'pengawas_laundry')
            ->where('status', true)
            ->findOrFail($validated['pengawas_id']);

        $admin->pengawas_id = $pengawas->id;

        $admin->save();

        return back()->with(
            'success',
            'Pengawas Laundry berhasil ditetapkan.'
        );
    }

    public function updatePengawasStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $pengawas = User::where('id', $id)
            ->where('role', 'pengawas_laundry')
            ->firstOrFail();

        $pengawas->status = $request->status === 'aktif';
        $pengawas->save();

        return back()->with(
            'success',
            'Status pengawas berhasil diperbarui.'
        );
    }


    /**
     * Melepas Pengawas Laundry
     * dari Admin Laundry.
     */
    public function removePengawas($id)
    {
        $admin = User::where('role', 'admin_laundry')
            ->findOrFail($id);

        $admin->pengawas_id = null;

        $admin->save();

        return back()->with(
            'success',
            'Pengawas Laundry berhasil dilepas dari Admin Laundry.'
        );
    }
}
