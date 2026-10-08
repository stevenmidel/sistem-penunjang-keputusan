@extends('layouts.SuperAdmin.app')

@section('title', 'Dashboard Pengawas Laundry')

@section('content')

    @include('partials.Pengawas.Navbar')
    @include('partials.Pengawas.Sidebar')

    <main class="pt-[72px] pl-64 min-h-screen bg-[#F9FAFB]">

        <div class="p-10 space-y-8">

            {{-- HEADER --}}
            <div>
                <h1 class="text-4xl font-extrabold text-[#14B8A6]">
                    Dashboard Pengawas
                </h1>

                <p class="text-gray-600 mt-1">
                    Monitoring aktivitas admin laundry yang berada dalam pengawasan Anda.
                </p>
            </div>


            {{-- STATISTIK --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500">
                        Admin Diawasi
                    </p>

                    <h2 class="text-4xl font-bold text-gray-900 mt-2">
                        {{ $totalAdmin }}
                    </h2>
                </div>


                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500">
                        Total Aktivitas
                    </p>

                    <h2 class="text-4xl font-bold text-gray-900 mt-2">
                        {{ $totalAktivitas }}
                    </h2>
                </div>


                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500">
                        Penambahan
                    </p>

                    <h2 class="text-4xl font-bold text-emerald-600 mt-2">
                        {{ $totalCreate }}
                    </h2>
                </div>


                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500">
                        Perubahan / Penghapusan
                    </p>

                    <h2 class="text-4xl font-bold text-red-500 mt-2">
                        {{ $totalUpdate + $totalDelete }}
                    </h2>
                </div>

            </div>


            {{-- ADMIN YANG DIAWASI --}}
            <section class="bg-white rounded-2xl shadow-md overflow-hidden">

                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800">
                        Admin Laundry yang Diawasi
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Daftar admin laundry yang berada di bawah pengawasan Anda.
                    </p>
                </div>


                <table class="w-full text-sm">

                    <thead class="bg-[#E0F2FE] text-[#0F766E]">

                        <tr>
                            <th class="px-6 py-4 text-left">
                                No
                            </th>

                            <th class="px-6 py-4 text-left">
                                Nama Laundry
                            </th>

                            <th class="px-6 py-4 text-left">
                                Username
                            </th>

                            <th class="px-6 py-4 text-center">
                                Status
                            </th>
                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @forelse ($admins as $index => $admin)
                            <tr>

                                <td class="px-6 py-4">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-6 py-4 font-semibold">
                                    {{ $admin->nama_layanan }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $admin->username }}
                                </td>

                                <td class="px-6 py-4 text-center">

                                    @if ($admin->status)
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            Nonaktif
                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">

                                    Belum ada admin laundry yang ditugaskan.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </section>


            {{-- AKTIVITAS --}}
            <section class="bg-white rounded-2xl shadow-md overflow-hidden">

                <div class="p-6 border-b">

                    <h2 class="text-xl font-bold text-gray-800">
                        Aktivitas Admin Laundry
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Riwayat aktivitas admin laundry yang Anda awasi.
                    </p>

                </div>


                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-4 text-left">
                                Waktu
                            </th>

                            <th class="px-6 py-4 text-left">
                                Admin
                            </th>

                            <th class="px-6 py-4 text-left">
                                Aksi
                            </th>

                            <th class="px-6 py-4 text-left">
                                Modul
                            </th>

                            <th class="px-6 py-4 text-left">
                                Deskripsi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @forelse ($activities as $activity)
                            <tr>

                                <td class="px-6 py-4">
                                    {{ $activity->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-6 py-4 font-semibold">
                                    {{ $activity->user->nama_layanan ?? ($activity->user->username ?? '-') }}
                                </td>

                                <td class="px-6 py-4">

                                    @if ($activity->aksi === 'CREATE')
                                        <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                            CREATE
                                        </span>
                                    @elseif ($activity->aksi === 'UPDATE')
                                        <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                                            UPDATE
                                        </span>
                                    @elseif ($activity->aksi === 'DELETE')
                                        <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                            DELETE
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                            {{ $activity->aksi }}
                                        </span>
                                    @endif

                                </td>

                                <td class="px-6 py-4">
                                    {{ $activity->modul }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $activity->deskripsi }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">

                                    Belum ada aktivitas.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </section>

        </div>

    </main>

@endsection
