@extends('layouts.SuperAdmin.app')

@section('title', 'Daftar Laundry')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

@section('content')

    @include('partials.SuperAdmin.Navbar')
    @include('partials.SuperAdmin.Sidebar')

    <main class="pt-[72px] pl-64 min-h-screen bg-[#F9FAFB]">
        <div class="p-10 space-y-10">

            {{-- ===================== HEADER ===================== --}}
            <div class="flex items-center justify-between">
                <h1 class="text-4xl font-extrabold text-[#14B8A6]">
                    Daftar Laundry Terdaftar
                </h1>
            </div>

            {{-- ===================== LIST LAUNDRY ===================== --}}
            @forelse ($units as $unit)
                <section class="bg-white rounded-2xl shadow-md p-8 space-y-6">

                    {{-- NAMA --}}
                    <h2 class="text-2xl font-bold text-[#111827]">
                        {{ $unit->nama_laundry }}
                    </h2>

                    {{-- ===================== RATING ===================== --}}
                    <div>
                        <p class="font-semibold">Rating</p>

                        @if (!is_null($unit->rating))
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-yellow-400 text-lg">
                                    {!! str_repeat('★', floor($unit->rating)) !!}
                                    {!! str_repeat('☆', 5 - floor($unit->rating)) !!}
                                </span>
                                <span class="text-gray-600 text-sm">
                                    {{ number_format($unit->rating, 1) }} / 5
                                </span>
                            </div>
                        @else
                            <p class="italic text-gray-500 mt-1">
                                Menunggu rating yang kamu isi
                            </p>
                        @endif
                    </div>

                    <div class="grid md:grid-cols-2 gap-8">

                        {{-- ===================== KIRI ===================== --}}
                        <div class="space-y-4 text-sm">

                            <div>
                                <p class="font-semibold">Alamat</p>
                                <p>{{ $unit->nama_jalan }}</p>
                            </div>

                            <div>
                                <p class="font-semibold mb-2">
                                    Daftar Layanan
                                </p>

                                @if ($unit->layanan->count())
                                    <div class="overflow-hidden rounded-xl shadow-sm">

                                        <table class="w-full text-sm">

                                            <thead class="bg-[#14B8A6] text-white">

                                                <tr>

                                                    <th class="text-left px-4 py-2">
                                                        Layanan
                                                    </th>

                                                    <th class="text-left px-4 py-2">
                                                        Harga
                                                    </th>

                                                    <th class="text-left px-4 py-2">
                                                        Estimasi
                                                    </th>

                                                    <th class="text-center px-4 py-2">
                                                        Rating
                                                    </th>

                                                </tr>

                                            </thead>

                                            <tbody class="bg-white">

                                                @foreach ($unit->layanan as $layanan)
                                                    <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">

                                                        <td class="px-4 py-2">
                                                            {{ $layanan->nama_layanan }}
                                                        </td>

                                                        <td class="px-4 py-2">
                                                            Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                                        </td>

                                                        <td class="px-4 py-2">
                                                            {{ $layanan->estimasi ?? '-' }}
                                                        </td>

                                                        <td class="px-4 py-2 text-center">

                                                            <span
                                                                class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 px-3 py-1rounded-full font-medium">

                                                                ⭐ {{ number_format($layanan->rating, 1) }}

                                                            </span>

                                                        </td>

                                                    </tr>
                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>
                                @else
                                    <p class="italic text-gray-500">
                                        Belum memiliki layanan.
                                    </p>
                                @endif

                            </div>

                            <div>
                                <p class="font-semibold">Jam Operasional</p>
                                <p>
                                    Senin – Jumat :
                                    {{ $unit->weekday_open }} – {{ $unit->weekday_close }}
                                </p>
                                <p>
                                    Sabtu – Minggu :
                                    {{ $unit->weekend_open }} – {{ $unit->weekend_close }}
                                </p>
                            </div>

                            <div>
                                <p class="font-semibold">Keterangan</p>
                                <p>{{ $unit->keterangan ?? '-' }}</p>
                            </div>

                            {{-- ===================== ACTION ===================== --}}
                            <div class="pt-4 flex gap-4">

                                <a href="{{ route('superadmin.laundry.detail', $unit->id) }}"
                                    class="px-5 py-2 rounded-lg bg-[#14B8A6] text-white font-semibold">
                                    Detail
                                </a>
                            </div>

                        </div>

                        {{-- ===================== MAP ===================== --}}
                        <div class="w-full h-[350px] rounded-xl overflow-hidden shadow-md">
                            <div id="unitMap{{ $unit->id }}" class="w-full h-full"></div>
                        </div>

                    </div>
                </section>
            @empty
                <div class="bg-white rounded-xl p-10 text-center text-gray-500">
                    Belum ada unit laundry terdaftar.
                </div>
            @endforelse

        </div>
    </main>

@endsection

{{-- ===================== LEAFLET JS ===================== --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@if ($units->count())
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            @foreach ($units as $unit)
                L.map('unitMap{{ $unit->id }}', {
                        zoomControl: false,
                        scrollWheelZoom: false,
                        touchZoom: false
                    })
                    .setView(
                        [{{ $unit->latitude }}, {{ $unit->longitude }}],
                        16
                    )
                    .addLayer(
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors'
                        })
                    )
                    .addLayer(
                        L.marker([{{ $unit->latitude }}, {{ $unit->longitude }}])
                        .bindPopup('<b>{{ $unit->nama_laundry }}</b>')
                    );
            @endforeach

        });
    </script>
@endif
