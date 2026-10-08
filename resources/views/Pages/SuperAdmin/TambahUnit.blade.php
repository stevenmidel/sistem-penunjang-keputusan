@extends('layouts.SuperAdmin.app')

@section('title', 'Tambah Unit Usaha')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    .leaflet-pane,
    .leaflet-top,
    .leaflet-bottom {
        z-index: 10 !important;
    }

    /* Pastikan kedua card sama tinggi secara alami */
    .card-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        align-items: start;
    }

    @media (max-width: 1024px) {
        .card-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Textarea mengisi sisa ruang di dalam form */
    .form-grow {
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 1.25rem;
    }

    .textarea-grow {
        flex: 1;
        min-height: 140px;
        resize: none;
    }
</style>

@section('content')

    @include('partials.SuperAdmin.Navbar')
    @include('partials.SuperAdmin.Sidebar')

    <main class="pt-[72px] pl-64 min-h-screen bg-[#F9FAFB]">
        <div class="p-10 max-w-7xl w-full mx-auto">

            {{-- HEADER --}}
            <h1 class="text-4xl font-extrabold text-[#14B8A6] font-manrope mb-8">
                Tambah Unit Usaha
            </h1>

            {{-- ALERT --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 text-green-800 px-6 py-4 rounded-xl font-semibold shadow">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 text-red-800 px-6 py-4 rounded-xl font-semibold shadow">
                    ❌ {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-100 text-red-800 px-6 py-4 rounded-xl shadow">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ================= GRID DUA CARD ================= --}}
            <div class="card-grid">

                {{-- ===== KIRI : INFORMASI UNIT (READ ONLY) ===== --}}
                <div class="bg-white rounded-2xl shadow-md p-8 flex flex-col">

                    <h2 class="text-xl font-bold text-gray-800 mb-1">
                        Informasi Unit Usaha
                    </h2>
                    <p class="text-xs text-gray-400 italic mb-6">
                        Data ini bersifat <span class="font-semibold text-[#14B8A6]">read-only</span> dan tidak dapat diubah
                        oleh Super Admin
                    </p>

                    <div class="space-y-5 text-sm text-gray-700 flex-1">

                        {{-- Nama Laundry --}}
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Nama Laundry</span>
                            <div
                                class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 font-medium text-gray-700 min-h-[40px]">
                                {{ $laundry->nama ?? '-' }}
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Alamat</span>
                            <div
                                class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 font-medium text-gray-700 min-h-[40px]">
                                {{ $laundry->alamat ?? '-' }}
                            </div>
                        </div>

                        {{-- Harga --}}
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Harga</span>
                            <div
                                class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 font-medium text-gray-700 min-h-[40px]">
                                {{ $laundry->harga ?? '-' }}
                            </div>
                        </div>

                        {{-- Jenis Layanan --}}
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Jenis Layanan</span>
                            <div
                                class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 font-medium text-gray-700 min-h-[40px]">
                                {{ $laundry->jenis_layanan ?? '-' }}
                            </div>
                        </div>

                        {{-- Jam Operasional --}}
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Jam Operasional</span>
                            <div
                                class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 font-medium text-gray-700 min-h-[40px]">
                                {{ $laundry->jam_operasional ?? '-' }}
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Keterangan</span>
                            <div
                                class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 font-medium text-gray-700 min-h-[56px]">
                                {{ $laundry->keterangan ?? '-' }}
                            </div>
                        </div>

                    </div>

                </div>

                {{-- ===== KANAN : INPUT RATING & ULASAN ===== --}}
                <div class="bg-white rounded-2xl shadow-md p-8 flex flex-col">

                    <h2 class="text-xl font-bold text-gray-800 mb-1">
                        Rating & Ulasan
                    </h2>
                    <p class="text-xs text-gray-400 italic mb-6">
                        Berikan penilaian dan ulasan untuk unit usaha ini
                    </p>

                    <form method="POST" action="{{ route('superadmin.laundry.store') }}" class="form-grow flex-1">
                        @csrf

                        {{-- RATING --}}
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-semibold uppercase tracking-wide text-gray-400" for="rating">
                                Rating
                            </label>
                            <select id="rating" name="rating"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#14B8A6] focus:border-transparent transition">
                                <option value="">Pilih Rating</option>
                                <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>⭐ 1 – Sangat Buruk
                                </option>
                                <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>⭐⭐ 2 – Buruk</option>
                                <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>⭐⭐⭐ 3 – Cukup</option>
                                <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ 4 – Baik</option>
                                <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 – Sangat Baik
                                </option>
                            </select>
                        </div>

                        {{-- ULASAN --}}
                        <div class="flex flex-col gap-1 flex-1">
                            <label class="text-xs font-semibold uppercase tracking-wide text-gray-400" for="ulasan">
                                Ulasan
                            </label>
                            <textarea id="ulasan" name="ulasan"
                                class="textarea-grow w-full px-4 py-3 rounded-lg border border-gray-200 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#14B8A6] focus:border-transparent transition"
                                placeholder="Tulis ulasan Anda...">{{ old('ulasan') }}</textarea>
                        </div>

                        {{-- SUBMIT --}}
                        <button type="submit"
                            class="w-full py-3 bg-[#14B8A6] hover:bg-[#0f9b8e] text-white font-semibold rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            Simpan Rating & Ulasan
                        </button>

                    </form>

                </div>

            </div>{{-- end card-grid --}}

        </div>
    </main>
@endsection

{{-- ===================== LEAFLET JS ===================== --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Hanya inisialisasi map jika elemen #addMap ada di halaman
        const mapEl = document.getElementById('addMap');
        if (!mapEl) return;

        let lat = -5.1195369;
        let lng = 119.5033523;

        const map = L.map('addMap').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        updateLatLng(lat, lng);

        marker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            updateLatLng(pos.lat, pos.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateLatLng(e.latlng.lat, e.latlng.lng);
        });

        function updateLatLng(lat, lng) {
            const latEl = document.getElementById('latitude');
            const lngEl = document.getElementById('longitude');
            if (latEl) latEl.value = lat;
            if (lngEl) lngEl.value = lng;
        }
    });
</script>
