@extends('layouts.AdminLaundry.app')

@section('title', 'Login')

@section('content')

    <section class="relative bg-white min-h-screen flex items-center">

        ```
        <div class="max-w-7xl mx-auto px-6 w-full flex flex-col lg:flex-row items-center lg:items-start gap-12">

            {{-- Kiri: Keterangan Login --}}
            <div class="lg:w-1/2 text-center lg:text-left">

                <h2 class="text-5xl lg:text-6xl font-extrabold text-[#14B8A6] mb-6 font-manrope leading-tight">
                    Login
                </h2>

                <p class="text-[#111827] text-lg lg:text-xl max-w-lg mb-8">
                    Silakan masuk menggunakan username dan password
                    untuk mengakses sistem.
                </p>

                <p class="text-gray-600 text-base max-w-lg">
                    Jika Anda merupakan Admin Laundry dan belum memiliki
                    akun, silakan melakukan pendaftaran terlebih dahulu
                    melalui halaman registrasi.
                </p>

            </div>


            {{-- Kanan: Form Login --}}
            <div class="lg:w-1/2 w-full max-w-md">

                <div class="bg-white shadow-lg rounded-2xl p-8">

                    <form method="POST" action="{{ route('login.process') }}" class="space-y-6">

                        @csrf

                        {{-- Error Global --}}
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                                {{ $errors->first() }}
                            </div>
                        @endif


                        {{-- Username --}}
                        <div>

                            <label for="username" class="block text-sm font-semibold text-[#111827] mb-2">
                                Username
                            </label>

                            <input type="text" name="username" id="username" value="{{ old('username') }}" required
                                autofocus autocomplete="username"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300
                               focus:outline-none focus:ring-2 focus:ring-[#14B8A6]
                               focus:border-transparent transition"
                                placeholder="Masukkan username">

                        </div>


                        {{-- Password --}}
                        <div>

                            <label for="password" class="block text-sm font-semibold text-[#111827] mb-2">
                                Password
                            </label>

                            <input type="password" name="password" id="password" required autocomplete="current-password"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300
                               focus:outline-none focus:ring-2 focus:ring-[#14B8A6]
                               focus:border-transparent transition"
                                placeholder="Masukkan password">

                        </div>


                        {{-- Tombol Login --}}
                        <button type="submit"
                            class="w-full bg-[#0EA5E9] text-white py-3 rounded-lg
                           font-semibold text-lg hover:bg-[#FACC15]
                           hover:text-[#1F2937] transition duration-300 shadow-md">
                            Login
                        </button>

                    </form>


                    <div class="mt-6 text-center space-y-3">

                        {{-- Lupa Password --}}
                        <div>
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-[#0EA5E9] font-semibold hover:underline">
                                Lupa Password?
                            </a>
                        </div>

                        {{-- Register --}}
                        <div>
                            <p class="text-sm text-gray-600">
                                Belum punya akun Admin Laundry?

                                <a href="{{ route('admin.laundry.register') }}"
                                    class="text-[#14B8A6] font-semibold hover:underline">
                                    Daftar di sini
                                </a>
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>
        ```

    </section>

@endsection
