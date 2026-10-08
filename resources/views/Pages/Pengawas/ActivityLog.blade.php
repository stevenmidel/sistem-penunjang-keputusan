@extends('layouts.Pengawas.app')

@section('title', 'Activity Log')

@section('content')

    @include('partials.Pengawas.Navbar')
    @include('partials.Pengawas.Sidebar')

    <main class="pt-[72px] pl-64 min-h-screen bg-[#F9FAFB]">

        <div class="p-10 space-y-8">

            {{-- HEADER --}}
            <div>
                <h1 class="text-4xl font-extrabold text-[#14B8A6]">
                    Activity Log
                </h1>

                <p class="text-gray-600 mt-1">
                    Riwayat aktivitas admin laundry yang berada dalam pengawasan Anda.
                </p>
            </div>

            {{-- TABLE --}}
            <section class="bg-white rounded-2xl shadow-md overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-[#E0F2FE] text-[#0F766E]">

                            <tr>

                                <th class="px-6 py-4 text-left">
                                    No
                                </th>

                                <th class="px-6 py-4 text-left">
                                    Waktu
                                </th>

                                <th class="px-6 py-4 text-left">
                                    Admin Laundry
                                </th>

                                <th class="px-6 py-4 text-center">
                                    Aksi
                                </th>

                                <th class="px-6 py-4 text-left">
                                    Modul
                                </th>

                                <th class="px-6 py-4 text-left">
                                    Aktivitas
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y">

                            @forelse ($logs as $index => $log)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4">
                                        {{ $logs->firstItem() + $index }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600 whitespace-nowrap">

                                        {{ $log->created_at->format('d/m/Y H:i:s') }}

                                    </td>

                                    <td class="px-6 py-4 font-semibold">

                                        {{ $log->user->nama_layanan ?? $log->user->username }}

                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        @if ($log->aksi === 'CREATE')
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                CREATE
                                            </span>
                                        @elseif ($log->aksi === 'UPDATE')
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                                UPDATE
                                            </span>
                                        @elseif ($log->aksi === 'DELETE')
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                DELETE
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                                {{ $log->aksi }}
                                            </span>
                                        @endif

                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $log->modul }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $log->deskripsi }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        Belum ada aktivitas dari admin laundry yang Anda awasi.
                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- PAGINATION --}}
                @if ($logs->hasPages())
                    <div class="px-6 py-4 border-t">
                        {{ $logs->links() }}
                    </div>
                @endif

            </section>

        </div>

    </main>

@endsection
