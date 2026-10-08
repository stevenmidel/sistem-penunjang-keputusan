@extends('layouts.SuperAdmin.app')

@section('title', 'Tambah Pengawas Laundry')

@section('content')

    @include('partials.SuperAdmin.Navbar')
    @include('partials.SuperAdmin.Sidebar')

    <main class="pt-[72px] pl-64 min-h-screen bg-[#F9FAFB]">

        <div class="p-10">

            <div class="max-w-3xl mx-auto">

                {{-- HEADER --}}
                <div class="mb-8">

                    <h1 class="text-4xl font-extrabold text-[#14B8A6]">
                        Tambah Pengawas Laundry
                    </h1>

                    <p class="text-gray-600 mt-1">
                        Buat akun baru untuk Pengawas Laundry.
                    </p>

                </div>


                {{-- FORM --}}
                <form method="POST" action="{{ route('superadmin.pengawas.store') }}"
                    class="bg-white rounded-2xl shadow-md p-8 space-y-6">

                    @csrf


                    {{-- NAMA --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Pengawas
                        </label>

                        <input type="text" name="nama_layanan" value="{{ old('nama_layanan') }}" required
                            class="w-full px-4 py-3 border rounded-lg
                               focus:ring-2 focus:ring-[#14B8A6]
                               focus:border-[#14B8A6]">

                        @error('nama_layanan')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- EMAIL --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>

                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border rounded-lg
                               focus:ring-2 focus:ring-[#14B8A6]
                               focus:border-[#14B8A6]">

                        @error('email')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- USERNAME --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Username
                        </label>

                        <input type="text" name="username" value="{{ old('username') }}" required
                            class="w-full px-4 py-3 border rounded-lg
                               focus:ring-2 focus:ring-[#14B8A6]
                               focus:border-[#14B8A6]">

                        @error('username')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- PASSWORD --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>

                        <input type="password" name="password" required
                            class="w-full px-4 py-3 border rounded-lg
                               focus:ring-2 focus:ring-[#14B8A6]
                               focus:border-[#14B8A6]">

                        @error('password')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- KONFIRMASI PASSWORD --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Konfirmasi Password
                        </label>

                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 border rounded-lg
                               focus:ring-2 focus:ring-[#14B8A6]
                               focus:border-[#14B8A6]">

                    </div>


                    {{-- BUTTON --}}
                    <div class="flex justify-end gap-3 pt-4">

                        <a href="{{ route('superadmin.pengawas.index') }}"
                            class="px-5 py-3 rounded-lg
                               bg-gray-200 text-gray-700
                               font-semibold hover:bg-gray-300">

                            Batal

                        </a>

                        <button type="submit"
                            class="px-5 py-3 rounded-lg
                               bg-[#14B8A6] text-white
                               font-semibold
                               hover:bg-[#0F766E]">

                            Simpan Pengawas

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>

@endsection
