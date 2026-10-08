<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    /**
     * Halaman input email
     */
    public function showRequestForm()
    {
        return view('pages.auth.forgot-password');
    }


    /**
     * Kirim OTP / Password Transaksi
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'Email tidak ditemukan.',
                ])
                ->withInput();
        }

        // Hapus OTP lama
        PasswordReset::where('email', $user->email)->delete();

        // Generate OTP 6 digit
        $otp = random_int(100000, 999999);

        PasswordReset::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'token' => Hash::make($otp),
            'expires_at' => now()->addMinutes(5),
            'created_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | KIRIM OTP
        |--------------------------------------------------------------------------
        */

        Mail::raw(
            "Password Transaksi / OTP Anda adalah: {$otp}\n\n" .
                "Kode ini berlaku selama 5 menit.\n" .
                "Jangan berikan kode ini kepada siapa pun.",
            function ($message) use ($user) {
                $message
                    ->to($user->email)
                    ->subject('Password Transaksi - LaundryFinder');
            }
        );

        // Simpan email sementara di session
        session([
            'password_reset_email' => $user->email,
        ]);

        return redirect()
            ->route('password.verify')
            ->with('success', 'Password transaksi telah dikirim ke email Anda.');
    }


    /**
     * Halaman verifikasi OTP
     */
    public function showVerifyForm()
    {
        if (!session('password_reset_email')) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Silakan masukkan email terlebih dahulu.',
                ]);
        }

        return view('pages.auth.verify-password');
    }


    /**
     * Verifikasi OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => [
                'required',
                'digits:6',
            ],
        ], [
            'otp.required' => 'Password transaksi wajib diisi.',
            'otp.digits' => 'Password transaksi harus 6 digit.',
        ]);

        $email = session('password_reset_email');

        if (!$email) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Sesi reset password telah berakhir.',
                ]);
        }

        $reset = PasswordReset::where('email', $email)
            ->latest('created_at')
            ->first();

        if (!$reset) {
            return back()->withErrors([
                'otp' => 'Password transaksi tidak ditemukan.',
            ]);
        }

        // Cek masa berlaku
        if (now()->greaterThan($reset->expires_at)) {

            $reset->delete();

            return back()->withErrors([
                'otp' => 'Password transaksi sudah kadaluarsa.',
            ]);
        }

        // Cek OTP
        if (!Hash::check($request->otp, $reset->token)) {
            return back()->withErrors([
                'otp' => 'Password transaksi salah.',
            ]);
        }

        // Tandai OTP sudah diverifikasi
        session([
            'password_reset_verified' => true,
        ]);

        return redirect()
            ->route('password.reset');
    }


    /**
     * Halaman password baru
     */
    public function showResetForm()
    {
        if (
            !session('password_reset_email') ||
            !session('password_reset_verified')
        ) {
            return redirect()->route('password.request');
        }

        return view('pages.auth.reset-password');
    }

    /**
     * Simpan password baru
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $email = session('password_reset_email');

        if (
            !$email ||
            !session('password_reset_verified')
        ) {
            return redirect()
                ->route('password.request');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'User tidak ditemukan.',
                ]);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token
        PasswordReset::where('email', $email)->delete();

        // Hapus session reset
        session()->forget([
            'password_reset_email',
            'password_reset_verified',
        ]);

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Password berhasil diubah. Silakan login menggunakan password baru.'
            );
    }
}
