@extends('layouts.AdminLaundry.app')

@section('title', 'Register Admin')

@section('content')

    <section class="relative bg-white min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto px-6 flex flex-col lg:flex-row items-center lg:items-start gap-12">

            {{-- Kiri --}}
            <div class="lg:w-1/2 text-center lg:text-left">
                <h2 class="text-5xl lg:text-6xl font-extrabold text-[#14B8A6] mb-6 font-manrope">
                    Registrasi
                </h2>

                <p class="text-[#111827] text-lg lg:text-xl max-w-lg mb-8">
                    Daftarkan layanan laundry Anda untuk mulai mengelola sistem secara mandiri.
                </p>
            </div>

            {{-- Kanan --}}
            <div class="lg:w-1/2 w-full max-w-md">
                <div class="bg-white shadow-lg rounded-2xl p-8">

                    {{-- ALERT SUKSES --}}
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded-lg text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- ALERT GAGAL --}}
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-lg text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- ERROR VALIDASI --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-lg text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.laundry.register.process') }}" class="space-y-5">
                        @csrf

                        {{-- Nama Laundry --}}
                        <div>
                            <label for="nama_layanan" class="block text-sm font-semibold mb-2">
                                Nama Layanan Laundry
                            </label>
                            <input type="text" id="nama_layanan" name="nama_layanan" value="{{ old('nama_layanan') }}"
                                required class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-[#14B8A6]">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold mb-2">
                                Email
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-[#14B8A6]">
                        </div>

                        {{-- Username --}}
                        <div>
                            <label for="username" class="block text-sm font-semibold mb-2">
                                Username
                            </label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" required
                                class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-[#14B8A6]">
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold mb-2">
                                Password
                            </label>
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-[#14B8A6]">
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold mb-2">
                                Konfirmasi Password
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-[#14B8A6]">
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="w-full bg-[#0EA5E9] text-white py-3 rounded-lg font-semibold
               hover:bg-[#FACC15] hover:text-gray-800 transition">
                            Daftar
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Sudah punya akun?
                            <a href="{{ route('admin.laundry.login') }}" class="text-[#14B8A6] font-semibold">
                                Login di sini
                            </a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
