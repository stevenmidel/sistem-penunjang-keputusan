@extends('layouts.AdminLaundry.app')

@section('title', 'Dashboard Admin Laundry')

@section('content')

    {{-- Navbar --}}
    @include('partials.AdminLaundry.Navbar')

    {{-- Sidebar --}}
    @include('partials.AdminLaundry.Sidebar')

    {{-- Profile Modal --}}
    @include('Pages.AdminLaundry.ProfileUser')

    <main class="pt-[72px] pl-64 min-h-screen bg-gray-50">
        <div class="p-10 space-y-10">

            {{-- HEADER --}}
            <div>
                <h1 class="text-4xl font-extrabold text-[#14B8A6] mb-2">
                    Dashboard Admin
                </h1>
                <p class="text-gray-600 max-w-xl">
                    Ringkasan performa unit usaha laundry yang Anda kelola.
                </p>
            </div>

            {{-- KPI CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500">Total Unit Usaha</p>
                    <h2 class="text-4xl font-bold text-gray-900 mt-2">
                        {{ $totalUnit }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500">Rata-rata Rating</p>
                    <h2 class="text-4xl font-bold text-yellow-500 mt-2">
                        ⭐ {{ number_format($avgRating, 1) }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500">Total Ulasan</p>
                    <h2 class="text-4xl font-bold text-gray-900 mt-2">
                        {{ $totalReview }}
                    </h2>
                </div>
            </div>

            {{-- LIST RATING PER USAHA --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">
                    Rating Tiap Unit Usaha
                </h3>

                @if ($units->count())
                    <div class="space-y-3">
                        @foreach ($units as $usaha)
                            <div class="flex justify-between items-center border-b pb-2">
                                <span class="font-medium text-gray-800">
                                    {{ $usaha->nama_laundry }}
                                </span>

                                <span class="text-yellow-500 font-semibold">
                                    ⭐ {{ number_format($usaha->rating ?? 0, 1) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">
                        Belum ada data unit usaha.
                    </p>
                @endif
            </div>

        </div>
    </main>
@endsection
