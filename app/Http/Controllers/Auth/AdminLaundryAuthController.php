<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller; // <-- Sudah benar
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminLaundryAuthController extends Controller
{
    // ================= LOGIN =================
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Pastikan role admin laundry
            if ($user->role !== 'admin_laundry') {
                Auth::logout();
                return back()->withErrors(['login' => 'Bukan akun Admin Laundry']);
            }

            // Pastikan akun aktif
            if (!$user->status) {
                Auth::logout();
                return back()->withErrors(['login' => 'Akun belum aktif']);
            }

            $request->session()->regenerate();
            return redirect()->route('admin.laundry.dashboard');
        }

        return back()->withErrors(['login' => 'Username atau password salah']);
    }

    // ================= REGISTER =================
    public function register(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'username'     => 'required|string|unique:users,username',
            'password'     => 'required|min:8|confirmed', // tambah konfirmasi password
        ]);

        try {
            User::create([
                'nama_layanan' => $request->nama_layanan,
                'email'        => $request->email,
                'username'     => $request->username,
                'password'     => Hash::make($request->password),
                'role'         => 'admin_laundry',
                'status'       => 0, // default belum aktif
            ]);

            return redirect()
                ->route('admin.laundry.login')
                ->with('success', 'Registrasi berhasil, silakan login');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Registrasi gagal, data sudah terdaftar');
        }
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.laundry.login');
    }

    // ================= PROFIL =================
    // Menampilkan data profil admin laundry
    public function showProfile()
    {
        $admin = Auth::user(); // ambil data admin yang login
        return view('pages.AdminLaundry.profileuser', compact('admin'));
    }

    // Update profil admin laundry
    public function updateProfile(Request $request)
    {
        /** 
         * @var \App\Models\User $admin
         * Mengambil data admin laundry yang sedang login
         */
        $admin = Auth::user();

        // Validasi input
        $request->validate([
            'username'      => 'required|string|min:4|max:50|alpha_dash|unique:users,username,' . $admin->id,
            'nama_layanan'  => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $admin->id,
            'password'      => 'nullable|string|min:6|confirmed',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // ================= Update Username =================
        $admin->username = strtolower($request->username); // opsional tapi direkomendasikan
        $admin->nama_layanan = $request->nama_layanan;
        $admin->email = $request->email;

        // ================= Update Password =================
        // Hanya update jika field password diisi
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        // ================= Update Foto Profil =================
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $destination = public_path('uploads/admins');

            // Pastikan folder tujuan ada
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            // Pindahkan file baru
            $file->move($destination, $filename);

            // Hapus file lama jika ada dan file benar-benar ada
            if ($admin->foto && file_exists(public_path($admin->foto))) {
                @unlink(public_path($admin->foto)); // gunakan @ untuk menekan warning
            }

            // Simpan path foto baru ke database
            $admin->foto = 'uploads/admins/' . $filename;
        }

        // ================= Simpan Perubahan ke Database =================
        $admin->save();

        // ================= Redirect dengan Flash Message =================
        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
