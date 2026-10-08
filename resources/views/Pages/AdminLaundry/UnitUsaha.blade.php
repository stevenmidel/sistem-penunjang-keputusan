@extends('layouts.AdminLaundry.app')

@section('title', 'Unit Usaha Saya')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

@section('content')

    @include('partials.AdminLaundry.Navbar')
    @include('partials.AdminLaundry.Sidebar')

    <main class="pt-[72px] pl-64 min-h-screen bg-[#F9FAFB]">
        <div class="p-10 space-y-10">

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
                <div id="successAlert"
                    class="bg-green-100 border border-green-300 text-green-800 px-6 py-4 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold">{{ session('success') }}</span>
                        <button onclick="document.getElementById('successAlert').remove()"
                            class="text-green-700 font-bold text-lg">✕</button>
                    </div>
                </div>
            @endif

            {{-- HEADER --}}
            <div class="flex items-center justify-between">
                <h1 class="text-4xl font-extrabold text-[#14B8A6]">
                    Unit Usaha Saya
                </h1>

                <a href="{{ route('admin.laundry.unit.tambah') }}"
                    class="px-6 py-3 rounded-lg bg-[#0EA5E9] text-white font-semibold hover:bg-[#0284C7] shadow-md">
                    + Tambah Unit
                </a>
            </div>

            {{-- LIST UNIT --}}
            @foreach ($units as $unit)
                <section class="bg-white rounded-2xl shadow-md p-8">

                    <h2 class="text-2xl font-bold mb-6">
                        {{ $unit->nama_laundry }}
                    </h2>

                    <div class="grid md:grid-cols-2 gap-8">

                        {{-- DETAIL --}}
                        <div class="space-y-4 text-sm">

                            <div>
                                <p class="font-semibold">Alamat</p>
                                <p>{{ $unit->nama_jalan }}</p>
                            </div>

                            <div>
                                <p class="font-semibold mb-2">Daftar Layanan</p>

                                @if ($unit->layanan->count())
                                    <div class="overflow-hidden border rounded-lg">

                                        <table class="w-full text-sm">

                                            <thead class="bg-gray-100">

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

                                            <tbody>

                                                @foreach ($unit->layanan as $layanan)
                                                    <tr class="border-t">

                                                        <td class="px-4 py-2 font-medium">
                                                            {{ $layanan->nama_layanan }}
                                                        </td>

                                                        <td class="px-4 py-2">
                                                            Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                                        </td>

                                                        <td class="px-4 py-2">
                                                            {{ $layanan->estimasi ?? '-' }}
                                                        </td>

                                                        <td class="px-4 py-2 text-center">

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
                                    <p class="text-gray-500 italic">
                                        Belum memiliki layanan.
                                    </p>
                                @endif

                            </div>

                            {{-- JAM OPERASIONAL --}}
                            <div>
                                <p class="font-semibold">Jam Operasional</p>
                                <p>Senin – Jumat : {{ $unit->weekday_open }} – {{ $unit->weekday_close }}</p>
                                <p>Sabtu – Minggu : {{ $unit->weekend_open }} – {{ $unit->weekend_close }}</p>
                            </div>

                            <div>
                                <p class="font-semibold">Keterangan</p>
                                <p>{{ $unit->keterangan ?? '-' }}</p>
                            </div>

                            {{-- ACTION --}}
                            <div class="pt-4 flex gap-4">
                                <form action="{{ route('admin.laundry.unit.destroy', $unit->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus unit ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-5 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                        Hapus
                                    </button>
                                </form>

                                <a href="{{ route('admin.laundry.unit.edit', $unit->id) }}"
                                    class="px-5 py-2 bg-[#14B8A6] text-white rounded-lg hover:bg-[#0F766E]">
                                    Edit
                                </a>
                            </div>

                        </div>

                        {{-- MAP --}}
                        <div class="w-full h-[350px] rounded-xl overflow-hidden shadow-md">
                            <div id="map{{ $unit->id }}" class="w-full h-full"></div>
                        </div>

                    </div>
                </section>
            @endforeach

        </div>
    </main>

@endsection

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        @foreach ($units as $unit)
            const map{{ $unit->id }} = L.map('map{{ $unit->id }}', {
                zoomControl: false,
                scrollWheelZoom: false
            }).setView([{{ $unit->latitude }}, {{ $unit->longitude }}], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
                .addTo(map{{ $unit->id }});

            L.marker([{{ $unit->latitude }}, {{ $unit->longitude }}])
                .addTo(map{{ $unit->id }});
        @endforeach
    });
</script>
