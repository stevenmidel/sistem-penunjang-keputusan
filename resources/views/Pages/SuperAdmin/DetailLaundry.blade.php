@extends('layouts.SuperAdmin.app')

@section('title', 'Detail Laundry')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    .leaflet-pane,
    .leaflet-top,
    .leaflet-bottom {
        z-index: 10 !important;
    }
</style>

@section('content')

    @include('partials.SuperAdmin.Navbar')
    @include('partials.SuperAdmin.Sidebar')

    <main class="pt-[72px] pl-64 min-h-screen bg-[#F9FAFB]">
        <div class="p-8 max-w-7xl w-full mx-auto">

            {{-- HEADER --}}
            <div class="mb-6">
                <h1 class="text-4xl font-extrabold text-[#14B8A6]">Detail Laundry</h1>
                <p class="text-gray-500 mt-1 text-sm">Informasi unit laundry (Super Admin)</p>
            </div>

            {{-- ALERT --}}
            @if (session('success'))
                <div class="mb-5 bg-green-100 text-green-800 px-5 py-3 rounded-xl font-semibold shadow text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-5 bg-red-100 text-red-800 px-5 py-3 rounded-xl font-semibold shadow text-sm">
                    ❌ {{ session('error') }}
                </div>
            @endif

            {{-- ================= GRID DUA KOLOM ================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

                {{-- ============================= --}}
                {{-- KIRI : INFO UNIT (READ-ONLY)  --}}
                {{-- ============================= --}}
                <div class="bg-white rounded-2xl shadow-md p-7 flex flex-col gap-4">

                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Informasi Unit Usaha</h2>
                        <p class="text-xs text-gray-400 italic mt-0.5">
                            Data ini bersifat <span class="text-[#14B8A6] font-semibold">read-only</span>
                        </p>
                    </div>

                    {{-- FIELD READ-ONLY --}}
                    <div class="flex flex-col gap-3 text-sm flex-1">

                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Nama Laundry</span>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-gray-700 font-medium">
                                {{ $unit->nama_laundry }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Alamat</span>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-gray-700 font-medium">
                                {{ $unit->nama_jalan }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                Daftar Layanan
                            </span>

                            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                                <table class="w-full text-sm">

                                    <thead class="bg-[#14B8A6] text-white">

                                        <tr>

                                            <th class="text-left px-4 py-3">
                                                Layanan
                                            </th>

                                            <th class="text-left px-4 py-3">
                                                Harga
                                            </th>

                                            <th class="text-left px-4 py-3">
                                                Estimasi
                                            </th>

                                            <th class="text-center px-4 py-3">
                                                Rating
                                            </th>

                                            <th class="text-center px-4 py-3">
                                                Aksi
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody class="bg-white">

                                        @forelse ($unit->layanan as $layanan)
                                            <tr class="border-b last:border-0">

                                                <td class="px-4 py-3 font-medium text-gray-700">
                                                    {{ $layanan->nama_layanan }}
                                                </td>

                                                <td class="px-4 py-3">
                                                    Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                                </td>

                                                <td class="px-4 py-3">
                                                    {{ $layanan->estimasi ?? '-' }}
                                                </td>

                                                <td class="px-4 py-3 text-center">

                                                    @if ($layanan->rating)
                                                        <span class="font-semibold text-amber-500">
                                                            {{ number_format($layanan->rating, 1) }} ⭐
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">
                                                            Belum Dinilai
                                                        </span>
                                                    @endif

                                                </td>

                                                <td class="px-4 py-3 text-center">

                                                    <button type="button"
                                                        class="btn-edit-rating
                                   bg-[#14B8A6]
                                   hover:bg-[#0F766E]
                                   text-white
                                   text-xs
                                   px-3
                                   py-2
                                   rounded-lg"
                                                        data-id="{{ $layanan->id }}"
                                                        data-nama="{{ $layanan->nama_layanan }}"
                                                        data-rating="{{ $layanan->rating }}">

                                                        Edit Rating

                                                    </button>

                                                </td>

                                            </tr>

                                        @empty

                                            <tr>

                                                <td colspan="5" class="text-center py-6 text-gray-400">

                                                    Belum memiliki layanan.

                                                </td>

                                            </tr>
                                        @endforelse

                                    </tbody>

                                </table>

                            </div>
                        </div>

                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Jam Operasional</span>
                            <div class="bg-gray-50 rounded-lg shadow-sm px-4 py-2.5 text-gray-700 font-medium">
                                {{ $unit->jam_operasional }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Keterangan</span>
                            <div
                                class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-gray-700 font-medium min-h-[44px]">
                                {{ $unit->keterangan ?? '-' }}
                            </div>
                        </div>

                        {{-- MAP mengisi sisa ruang --}}
                        <div class="flex flex-col gap-1 flex-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Lokasi</span>
                            <div class="flex-1 min-h-[160px] rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                                <div id="detailMap" class="w-full h-full"></div>
                            </div>
                        </div>

                    </div>

                    {{-- TOMBOL KEMBALI --}}
                    <div class="pt-1">
                        <a href="{{ route('superadmin.laundry') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-gray-100
                                   text-gray-700 font-semibold hover:bg-gray-200 transition text-sm">
                            ← Kembali ke Daftar
                        </a>
                    </div>

                </div>

                {{-- ================================ --}}
                {{-- KANAN : RATING & ULASAN (FORM)  --}}
                {{-- ================================ --}}
                <div class="bg-white rounded-2xl shadow-md p-7 flex flex-col gap-5">

                    {{-- ---- RATING ---- --}}
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Rating Laundry</h2>
                        <p class="text-xs text-gray-400 italic mt-0.5 mb-4">
                            Berikan rating untuk unit ini (1.0 – 5.0)
                        </p>

                        <form method="POST" action="{{ route('superadmin.laundry.rating', $unit->id) }}"
                            class="flex gap-3 items-start">
                            @csrf

                            <div class="flex-1 flex flex-col gap-1">
                                <input type="number" name="rating" step="0.1" min="1" max="5"
                                    value="{{ old('rating', $unit->rating) }}" placeholder="Contoh: 4.8" required
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-700
                       focus:outline-none focus:ring-2 focus:ring-[#14B8A6] focus:border-transparent
                       transition text-sm">

                                @error('rating')
                                    <p class="text-red-500 text-xs">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="px-5 py-2.5 rounded-lg bg-[#14B8A6] text-white font-semibold
                   hover:bg-[#0F766E] transition text-sm whitespace-nowrap shadow-sm">
                                Simpan Rating
                            </button>
                        </form>
                    </div>

                    {{-- ---- ULASAN ---- --}}
                    <div class="flex flex-col gap-4 flex-1">
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Ulasan Pengguna</h2>
                            <p class="text-xs text-gray-400 italic mt-0.5">Maksimal 3 ulasan yang diinput oleh Super Admin
                            </p>
                        </div>

                        <form method="POST" action="{{ route('superadmin.laundry.ulasan', $unit->id) }}"
                            class="flex flex-col gap-4 flex-1">
                            @csrf

                            @foreach ([0, 1, 2] as $i)
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex flex-col gap-2">

                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="w-5 h-5 rounded-full bg-[#14B8A6] text-white text-xs
                                                     flex items-center justify-center font-bold flex-shrink-0">
                                            {{ $i + 1 }}
                                        </span>
                                        <span class="text-sm font-bold text-gray-600">Ulasan {{ $i + 1 }}</span>
                                        <span class="text-xs {{ $i === 0 ? 'text-red-400' : 'text-red-400' }}">
                                            {{ $i === 0 ? '(wajib)' : '(wajib)' }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="text" name="reviews[{{ $i }}][nama]"
                                            placeholder="Nama Pemberi Ulasan" {{ $i === 0 ? 'required' : '' }}
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-700
                                                   focus:outline-none focus:ring-2 focus:ring-[#14B8A6] focus:border-transparent transition">

                                        <input type="date" name="reviews[{{ $i }}][tanggal]"
                                            {{ $i === 0 ? 'required' : '' }}
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-700
                                                   focus:outline-none focus:ring-2 focus:ring-[#14B8A6] focus:border-transparent transition">
                                    </div>

                                    <textarea name="reviews[{{ $i }}][ulasan]" rows="2" placeholder="Isi ulasan..."
                                        {{ $i === 0 ? 'required' : '' }}
                                        class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-700
                                               resize-none focus:outline-none focus:ring-2 focus:ring-[#14B8A6]
                                               focus:border-transparent transition"></textarea>
                                </div>
                            @endforeach

                            <div class="flex justify-end pt-1">
                                <button type="submit"
                                    class="px-6 py-2.5 rounded-lg bg-[#14B8A6] text-white font-semibold
                                           hover:bg-[#0F766E] transition text-sm shadow-sm">
                                    Simpan Ulasan
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
                {{-- end KANAN --}}

            </div>
            {{-- end grid --}}

        </div>
    </main>



    {{-- ========================================================= --}}
    {{-- MODAL EDIT RATING LAYANAN --}}
    {{-- ========================================================= --}}
    <div id="ratingModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

            <div class="flex justify-between items-center mb-5">

                <h2 class="text-xl font-bold text-gray-800">
                    Edit Rating Layanan
                </h2>

                <button id="closeModal" class="text-gray-400 hover:text-red-500 text-2xl">

                    &times;

                </button>

            </div>

            <form id="ratingForm" method="POST">

                @csrf
                @method('PUT')

                <input type="hidden" id="layananId">

                <div class="mb-4">

                    <label class="block text-sm font-semibold text-gray-600 mb-2">
                        Nama Layanan
                    </label>

                    <input id="namaLayanan" type="text" readonly
                        class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border border-gray-200 text-gray-700">

                </div>

                <div class="mb-5">

                    <label class="block text-sm font-semibold text-gray-600 mb-2">
                        Rating
                    </label>

                    <input id="ratingInput" name="rating" type="number" step="0.1" min="1" max="5"
                        required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200
                           focus:ring-2 focus:ring-[#14B8A6]
                           focus:border-transparent">

                </div>

                <div class="flex justify-end gap-3">

                    <button type="button" id="btnBatal" class="px-5 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">

                        Batal

                    </button>

                    <button type="submit" class="px-5 py-2 rounded-lg bg-[#14B8A6] text-white hover:bg-[#0F766E]">

                        Simpan Rating

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        console.log("SCRIPT DETAIL LAUNDRY BERJALAN");
        document.addEventListener('DOMContentLoaded', function() {
            const latitude = {{ $unit->latitude }};
            const longitude = {{ $unit->longitude }};

            const map = L.map('detailMap', {
                dragging: false,
                zoomControl: false,
                scrollWheelZoom: false,
                doubleClickZoom: false
            }).setView([latitude, longitude], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            L.marker([latitude, longitude]).addTo(map);
        });


        /* ===========================================================
         * MODAL EDIT RATING LAYANAN
         * =========================================================== */

        const modal = document.getElementById('ratingModal');

        const form = document.getElementById('ratingForm');

        const namaInput = document.getElementById('namaLayanan');

        const ratingInput = document.getElementById('ratingInput');

        const closeModal = document.getElementById('closeModal');

        const btnBatal = document.getElementById('btnBatal');

        document.querySelectorAll('.btn-edit-rating').forEach(button => {

            button.addEventListener('click', function() {

                const id = this.dataset.id;

                const nama = this.dataset.nama;

                const rating = this.dataset.rating;

                namaInput.value = nama;

                ratingInput.value = rating;

                /*
                 * Route akan kita buat pada tahap berikutnya.
                 */

                const baseUrl = "{{ url('admin/layanan') }}";

                form.action = `${baseUrl}/${id}/rating`;

                modal.classList.remove('hidden');

                modal.classList.add('flex');

            });

        });

        function tutupModal() {

            modal.classList.remove('flex');

            modal.classList.add('hidden');

        }

        closeModal.addEventListener('click', tutupModal);

        btnBatal.addEventListener('click', tutupModal);

        modal.addEventListener('click', function(e) {

            if (e.target === modal) {

                tutupModal();

            }

        });
    </script>
@endpush
