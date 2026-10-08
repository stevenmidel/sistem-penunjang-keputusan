@extends('layouts.user.app')

@section('title', 'Detail Laundry')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        #detailMap {
            height: 400px !important;
            width: 100% !important;
            z-index: 10;
            position: relative;
            background: #e5e7eb;
        }
    </style>

    <section class="max-w-7xl mx-auto px-6 py-20">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('user.home') }}" class="text-[#14B8A6] hover:text-[#0D9488] transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-4xl font-extrabold text-[#14B8A6]">
                {{ $laundry->nama_laundry }}
            </h1>
        </div>

        {{-- Konten --}}
        <div class="grid md:grid-cols-2 gap-10 items-stretch">


            {{-- Gambar Laundry --}}
            <div class="h-full">
                <img src="{{ $laundry->foto_laundry ? asset($laundry->foto_laundry) : asset('images/TempatLaundry.jpeg') }}"
                    class="w-full h-full object-cover rounded-xl shadow-md" alt="{{ $laundry->nama_laundry }}" />
            </div>



            {{-- Informasi Laundry --}}
            <div class="flex flex-col h-full bg-white rounded-2xl shadow-md p-6">

                {{-- Info Atas --}}
                <div class="space-y-5">

                    {{-- Rating --}}
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1">Rating Toko</p>
                        <div class="text-[#F97316] text-lg">
                            @php $star = floor($laundry->rating ?? 0); @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                {{ $i <= $star ? '★' : '☆' }}
                            @endfor
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1">📍 Alamat</p>
                        <p class="text-sm text-gray-700">
                            {{ $laundry->nama_jalan }}
                        </p>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1">Tentang Laundry</p>
                        <p class="text-sm text-gray-700 leading-relaxed">
                            {{ $laundry->keterangan ?? 'Belum ada deskripsi laundry.' }}
                        </p>
                    </div>

                </div>

                {{-- Jam Operasional (SELALU DI BAWAH) --}}
                <div class="mt-auto pt-6">
                    <div class="bg-gray-50 rounded-2xl shadow-sm p-5 space-y-3">
                        <p class="text-sm font-semibold text-gray-600">🕒 Jam Operasional</p>

                        <div class="flex items-center justify-between text-sm text-gray-700">
                            <span class="font-medium">Senin – Jumat</span>
                            <span>
                                {{ $laundry->weekday_open && $laundry->weekday_close
                                    ? $laundry->weekday_open . ' - ' . $laundry->weekday_close
                                    : 'Tutup' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-sm text-gray-700">
                            <span class="font-medium">Sabtu – Minggu</span>
                            <span>
                                {{ $laundry->weekend_open && $laundry->weekend_close
                                    ? $laundry->weekend_open . ' - ' . $laundry->weekend_close
                                    : 'Tutup' }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Layanan Laundry --}}
        <div>
            <p class="text-sm font-semibold text-gray-500 mb-3">

            </p>

            @if ($laundry->layanan->count())

                <div class="overflow-hidden rounded-2xl shadow-sm">

                    <table class="w-full text-sm">

                        <thead class="bg-[#14B8A6] text-white">

                            <tr>
                                <th class="px-4 py-3 text-left">
                                    Layanan
                                </th>

                                <th class="px-4 py-3 text-left">
                                    Harga
                                </th>

                                <th class="px-4 py-3 text-left">
                                    Estimasi
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Rating
                                </th>
                            </tr>

                        </thead>

                        <tbody class="bg-white">

                            @foreach ($laundry->layanan as $layanan)
                                <tr class="border-b last:border-b-0">

                                    <td class="px-4 py-3">
                                        {{ $layanan->nama_layanan }}
                                    </td>

                                    <td class="px-4 py-3 font-semibold text-[#14B8A6]">
                                        Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $layanan->estimasi ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-center">

                                        @if (!is_null($layanan->rating))
                                            <span class="font-semibold text-amber-500">
                                                ⭐ {{ number_format($layanan->rating, 1) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">
                                                Belum Dinilai
                                            </span>
                                        @endif

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>
            @else
                <p class="text-sm text-gray-500 italic">
                    Belum memiliki layanan.
                </p>

            @endif

        </div>

        {{-- Maps & Ulasan --}}
        <div class="mt-20 grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">

            {{-- Maps --}}
            <div class="space-y-6">
                <h2 class="text-xl font-semibold">Lokasi & Rute</h2>

                <div id="detailMap" class="rounded-xl shadow-md"></div>


            </div>

            {{-- Ulasan --}}
            <div class="flex flex-col h-full">
                <h2 class="text-xl font-semibold mb-6">Ulasan Pengguna</h2>

                <div class="space-y-4 overflow-y-auto max-h-[520px] pr-2">

                    @forelse ($ulasans as $ulasan)
                        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                            <div class="flex items-center gap-3 mb-2">
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">
                                        {{ $ulasan->user->name ?? ($ulasan->nama_pemberi ?? 'Pengguna') }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ $ulasan->tanggal_ulasan->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <p class="text-gray-600 text-sm leading-relaxed">
                                {{ $ulasan->ulasan }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">
                            Belum ada ulasan untuk laundry ini.
                        </p>
                    @endforelse

                </div>
            </div>

        </div>
    </section>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        window.laundryMapData = {
            lat: {{ $laundry->latitude ?? 'null' }},
            lng: {{ $laundry->longitude ?? 'null' }},
            name: @json($laundry->nama_laundry ?? 'Laundry'),
            address: @json($laundry->nama_jalan ?? '')
        };
    </script>

    <script>
        (function() {

            function initMap() {

                const mapEl = document.getElementById('detailMap');
                if (!mapEl || typeof L === 'undefined') return;

                const laundry = window.laundryMapData;
                if (!laundry?.lat || !laundry?.lng) return;

                const titikLaundry = L.latLng(laundry.lat, laundry.lng);

                const map = L.map('detailMap').setView(titikLaundry, 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                }).addTo(map);

                let routeLayer = null;
                let titikUser = null;

                function buatIcon(warna, emoji) {
                    return L.divIcon({
                        html: `<div style="
                    background:${warna};
                    color:#fff;
                    border-radius:50%;
                    width:36px;height:36px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:18px;
                    border:2px solid #fff;
                ">${emoji}</div>`,
                        className: '',
                        iconSize: [36, 36],
                        iconAnchor: [18, 18]
                    });
                }

                L.marker(titikLaundry, {
                        icon: buatIcon('#F97316', '👕')
                    })
                    .addTo(map)
                    .bindPopup(`<b>${laundry.name}</b><br>${laundry.address}`)
                    .openPopup();

                function loadRoute() {

                    if (!titikUser) return;

                    if (routeLayer) {
                        map.removeLayer(routeLayer);
                        routeLayer = null;
                    }

                    // Default: Jalan Kaki
                    const osrmProfile = 'foot';

                    const osrmUrl =
                        `https://router.project-osrm.org/route/v1/${osrmProfile}/` +
                        `${titikUser.lng},${titikUser.lat};` +
                        `${titikLaundry.lng},${titikLaundry.lat}` +
                        `?overview=full&geometries=geojson`;

                    fetch(osrmUrl)
                        .then(r => r.json())
                        .then(data => {

                            if (!data.routes?.length) return;

                            const route = data.routes[0];

                            const latLngs = route.geometry.coordinates.map(
                                c => [c[1], c[0]]
                            );

                            routeLayer = L.polyline(latLngs, {
                                color: '#14B8A6',
                                weight: 5
                            }).addTo(map);

                            map.fitBounds(routeLayer.getBounds(), {
                                padding: [50, 50]
                            });

                            const distanceKm = (route.distance / 1000).toFixed(2);
                            document.getElementById('routeInfo').innerHTML =
                                `<span class="font-semibold">Jarak:</span> ${distanceKm} km`;

                            document.getElementById('googleMapsBtn').href =
                                `https://www.google.com/maps/dir/?api=1` +
                                `&origin=${titikUser.lat},${titikUser.lng}` +
                                `&destination=${titikLaundry.lat},${titikLaundry.lng}` +
                                `&travelmode=walking`;
                        })
                        .catch(console.warn);
                }

                setTimeout(() => map.invalidateSize(), 500);
            }

            document.readyState === 'loading' ?
                document.addEventListener('DOMContentLoaded', initMap) :
                initMap();

        })();
    </script>

@endsection
