@extends('layouts.SuperAdmin.app')

@section('title', 'Pengawas Laundry')

@section('content')

    @include('partials.SuperAdmin.Navbar')
    @include('partials.SuperAdmin.Sidebar')

    <main class="pt-[72px] pl-64 min-h-screen bg-[#F9FAFB]">

        <div class="p-10 space-y-8">

            {{-- HEADER --}}
            <div class="flex items-center justify-between">

                <div>
                    <h1 class="text-4xl font-extrabold text-[#14B8A6]">
                        Pengawas Laundry
                    </h1>

                    <p class="text-gray-600 mt-1">
                        Kelola akun pengawas dan pantau jumlah admin laundry yang diawasi.
                    </p>
                </div>

                <a href="{{ route('superadmin.pengawas.create') }}"
                    class="px-5 py-3 rounded-lg bg-[#14B8A6] text-white
                       font-semibold hover:bg-[#0F766E] transition">
                    + Tambah Pengawas
                </a>

            </div>


            {{-- SUCCESS MESSAGE --}}
            @if (session('success'))
                <div
                    class="bg-green-100 border border-green-200
                        text-green-700 px-5 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif


            {{-- ERROR MESSAGE --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-200
                        text-red-700 px-5 py-4 rounded-xl">

                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif


            {{-- TABLE --}}
            <section class="bg-white rounded-2xl shadow-md overflow-hidden">

                <table class="w-full text-sm">

                    <thead class="bg-[#E0F2FE] text-[#0F766E]">

                        <tr>

                            <th class="px-6 py-4 text-left">
                                No
                            </th>

                            <th class="px-6 py-4 text-left">
                                Nama Pengawas
                            </th>

                            <th class="px-6 py-4 text-left">
                                Username
                            </th>

                            <th class="px-6 py-4 text-left">
                                Email
                            </th>

                            <th class="px-6 py-4 text-center">
                                Admin Diawasi
                            </th>

                            <th class="px-6 py-4 text-center">
                                Status
                            </th>

                            <th class="px-6 py-4 text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @forelse ($pengawas as $index => $item)
                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-6 py-4 font-semibold">
                                    {{ $item->nama_layanan }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $item->username }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $item->email }}
                                </td>

                                <td class="px-6 py-4 text-center">

                                    <span
                                        class="px-3 py-1 rounded-full
                                           bg-blue-100 text-blue-700
                                           font-semibold">

                                        {{ $item->admin_diawasi_count }}

                                    </span>

                                </td>

                                <td class="px-6 py-4 text-center">

                                    @if ($item->status)
                                        <span
                                            class="px-3 py-1 rounded-full
                                               text-xs font-semibold
                                               bg-green-100 text-green-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full
                                               text-xs font-semibold
                                               bg-red-100 text-red-700">
                                            Nonaktif
                                        </span>
                                    @endif

                                </td>

                                <td class="px-6 py-4">

                                    <form method="POST"
                                        action="{{ route('superadmin.pengawas.update-status', $item->id) }}"
                                        class="flex justify-center items-center gap-3">

                                        @csrf
                                        @method('PUT')

                                        <select name="status" class="border rounded-lg px-3 py-2">

                                            <option value="aktif" {{ $item->status ? 'selected' : '' }}>
                                                Aktif
                                            </option>

                                            <option value="nonaktif" {{ !$item->status ? 'selected' : '' }}>
                                                Nonaktif
                                            </option>

                                        </select>

                                        <button type="submit"
                                            class="px-4 py-2 rounded-lg
                                               bg-[#14B8A6] text-white
                                               font-semibold
                                               hover:bg-[#0F766E]">

                                            Simpan

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="px-6 py-10 text-center text-gray-500">

                                    Belum ada Pengawas Laundry.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </section>

        </div>

    </main>

@endsection
