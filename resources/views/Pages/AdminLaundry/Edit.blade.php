@extends('layouts.AdminLaundry.app')

@section('title', 'Edit Unit Usaha')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    .leaflet-pane,
    .leaflet-top,
    .leaflet-bottom {
        z-index: 10 !important;
    }
</style>

@section('content')

    @include('partials.AdminLaundry.Navbar')
    @include('partials.AdminLaundry.Sidebar')

    <main class="pt-[72px] pl-64 min-h-screen bg-[#F9FAFB]">
        <div class="p-10 space-y-10 max-w-4xl">

            <h1 class="text-4xl font-extrabold text-[#14B8A6]">
                Edit Unit Usaha
            </h1>

            <section class="bg-white rounded-2xl shadow-md p-8">

                {{-- ERROR VALIDATION --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.laundry.unit.update', $unit->id) }}"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- NAMA --}}
                    <input type="text" name="nama_laundry" value="{{ old('nama_laundry', $unit->nama_laundry) }}"
                        class="w-full px-4 py-3 border rounded-lg" placeholder="Nama Laundry">

                    {{-- FOTO --}}
                    <div>
                        <input type="file" name="foto_laundry" class="w-full px-4 py-3 border rounded-lg">

                        @if ($unit->foto_laundry)
                            <img src="{{ asset($unit->foto_laundry) }}" class="mt-3 w-full h-48 object-cover rounded-lg"
                                alt="Foto {{ $unit->nama_laundry }}">
                        @endif
                    </div>

                    {{-- ALAMAT --}}
                    <input type="text" name="nama_jalan" value="{{ old('nama_jalan', $unit->nama_jalan) }}"
                        class="w-full px-4 py-3 border rounded-lg" placeholder="Nama Jalan">

                    {{-- LOKASI --}}
                    <div>
                        <label class="font-semibold text-gray-700 block mb-3">Lokasi</label>

                        {{-- PILIHAN MODE --}}
                        <div class="flex gap-6 mb-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="lokasi_mode" id="modePeta" value="peta" checked>
                                <span>Pilih di Peta</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="lokasi_mode" id="modeManual" value="manual">
                                <span>Input Manual</span>
                            </label>
                        </div>

                        {{-- MODE PETA --}}
                        <div id="mapWrapper" class="w-full h-[350px] rounded-xl overflow-hidden shadow-md mb-3">
                            <div id="editMap" class="w-full h-full"></div>
                        </div>

                        {{-- MODE MANUAL --}}
                        <div id="manualWrapper" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                            <input type="number" step="any" id="latitudeManual"
                                value="{{ old('latitude', $unit->latitude) }}" class="w-full px-4 py-3 rounded-lg border"
                                placeholder="Latitude (Contoh: -5.1195369)">
                            <input type="number" step="any" id="longitudeManual"
                                value="{{ old('longitude', $unit->longitude) }}" class="w-full px-4 py-3 rounded-lg border"
                                placeholder="Longitude (Contoh: 119.5033523)">
                        </div>

                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $unit->latitude) }}">
                        <input type="hidden" id="longitude" name="longitude"
                            value="{{ old('longitude', $unit->longitude) }}">
                    </div>

                    {{-- LAYANAN LAUNDRY --}}
                    <div>

                        <div class="flex justify-between items-center mb-4">

                            <label class="text-sm font-semibold">
                                Daftar Layanan
                            </label>

                            <button type="button" id="addLayanan" class="bg-[#14B8A6] text-white px-4 py-2 rounded">

                                + Tambah Layanan

                            </button>

                        </div>

                        <div id="layananContainer">

                            @foreach ($unit->layanan as $layanan)
                                <div class="layanan-item border rounded-lg p-4 mb-4">

                                    <input type="hidden" name="layanan_id[]" value="{{ $layanan->id }}">

                                    <div class="grid grid-cols-3 gap-3">

                                        <input type="text" name="nama_layanan[]"
                                            value="{{ old('nama_layanan.' . $loop->index, $layanan->nama_layanan) }}"
                                            class="border rounded px-3 py-2" placeholder="Nama Layanan">

                                        <input type="number" name="harga[]"
                                            value="{{ old('harga.' . $loop->index, $layanan->harga) }}"
                                            class="border rounded px-3 py-2" placeholder="Harga">

                                        <input type="text" name="estimasi[]"
                                            value="{{ old('estimasi.' . $loop->index, $layanan->estimasi) }}"
                                            class="border rounded px-3 py-2" placeholder="Estimasi">

                                    </div>

                                    <button type="button" class="hapusLayanan mt-3 text-red-600">

                                        Hapus

                                    </button>

                                </div>
                            @endforeach

                        </div>

                    </div>

                    {{-- JAM --}}
                    <div class="grid grid-cols-2 gap-4">
                        <input type="time" name="weekday_open" value="{{ old('weekday_open', $unit->weekday_open) }}"
                            class="px-4 py-3 border rounded-lg">
                        <input type="time" name="weekday_close"
                            value="{{ old('weekday_close', $unit->weekday_close) }}" class="px-4 py-3 border rounded-lg">
                        <input type="time" name="weekend_open" value="{{ old('weekend_open', $unit->weekend_open) }}"
                            class="px-4 py-3 border rounded-lg">
                        <input type="time" name="weekend_close"
                            value="{{ old('weekend_close', $unit->weekend_close) }}" class="px-4 py-3 border rounded-lg">
                    </div>

                    {{-- KETERANGAN --}}
                    <textarea name="keterangan" class="w-full px-4 py-3 border rounded-lg" rows="3">{{ old('keterangan', $unit->keterangan) }}</textarea>

                    {{-- ACTION --}}
                    <div class="flex justify-between pt-4">
                        <a href="{{ route('admin.laundry.unit.index') }}" class="px-6 py-3 bg-gray-300 rounded-lg">
                            Batal
                        </a>
                        <button class="px-6 py-3 bg-[#14B8A6] text-white rounded-lg">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </section>
        </div>
    </main>
@endsection

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        /*
        |--------------------------------------------------------------------------
        | MAP LEAFLET
        |--------------------------------------------------------------------------
        */

        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');

        let lat = parseFloat(latitudeInput.value) || -5.147665;
        let lng = parseFloat(longitudeInput.value) || 119.432732;

        const map = L.map('editMap').setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        function updateLocation(lat, lng) {
            latitudeInput.value = lat;
            longitudeInput.value = lng;
        }

        marker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            updateLocation(pos.lat, pos.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateLocation(e.latlng.lat, e.latlng.lng);
        });

        // Memperbaiki map yang kadang kosong saat halaman selesai dirender
        setTimeout(function() {
            map.invalidateSize();
        }, 200);

        /*
        |--------------------------------------------------------------------------
        | TOGGLE MODE LOKASI (PETA / MANUAL)
        |--------------------------------------------------------------------------
        */

        const modePeta = document.getElementById('modePeta');
        const modeManual = document.getElementById('modeManual');
        const mapWrapper = document.getElementById('mapWrapper');
        const manualWrapper = document.getElementById('manualWrapper');
        const latitudeManual = document.getElementById('latitudeManual');
        const longitudeManual = document.getElementById('longitudeManual');

        function setMode(mode) {
            if (mode === 'peta') {
                mapWrapper.classList.remove('hidden');
                manualWrapper.classList.add('hidden');

                // kosongkan input manual agar tidak dianggap terisi
                latitudeManual.value = '';
                longitudeManual.value = '';

                // kembalikan lat/lng dari posisi marker saat ini
                const pos = marker.getLatLng();
                updateLocation(pos.lat, pos.lng);

                map.invalidateSize();
            } else {
                mapWrapper.classList.add('hidden');
                manualWrapper.classList.remove('hidden');

                latitudeInput.value = '';
                longitudeInput.value = '';
            }
        }

        modePeta.addEventListener('change', () => setMode('peta'));
        modeManual.addEventListener('change', () => setMode('manual'));

        [latitudeManual, longitudeManual].forEach(input => {
            input.addEventListener('input', () => {
                latitudeInput.value = latitudeManual.value;
                longitudeInput.value = longitudeManual.value;
            });
        });

        /*
|--------------------------------------------------------------------------
| VALIDASI LOKASI SEBELUM SUBMIT
|--------------------------------------------------------------------------
*/

        document.querySelector('form').addEventListener('submit', function(e) {

            let valid = true;

            if (modeManual.checked) {
                const la = parseFloat(latitudeManual.value);
                const lo = parseFloat(longitudeManual.value);

                if (isNaN(la) || isNaN(lo) || la < -90 || la > 90 || lo < -180 || lo > 180) {
                    valid = false;
                }
            } else {
                if (latitudeInput.value.trim() === '' || longitudeInput.value.trim() === '') {
                    valid = false;
                }
            }

            if (!valid) {
                e.preventDefault();
                alert('Mohon pilih lokasi di peta atau isi latitude & longitude manual dengan benar.');
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

        });


        /*
        |--------------------------------------------------------------------------
        | TAMBAH LAYANAN
        |--------------------------------------------------------------------------
        */

        const container = document.getElementById('layananContainer');
        const btnTambah = document.getElementById('addLayanan');

        btnTambah.addEventListener('click', function() {

            container.insertAdjacentHTML('beforeend', `
<div class="layanan-item border rounded-lg p-4 mb-4">

    <input
        type="hidden"
        name="layanan_id[]"
        value="">

    <div class="grid grid-cols-3 gap-3">

        <input
            type="text"
            name="nama_layanan[]"
            class="border rounded px-3 py-2"
            placeholder="Nama Layanan"
            required>

        <input
            type="number"
            name="harga[]"
            class="border rounded px-3 py-2"
            placeholder="Harga"
            required>

        <input
            type="text"
            name="estimasi[]"
            class="border rounded px-3 py-2"
            placeholder="Estimasi">

    </div>

    <button
        type="button"
        class="hapusLayanan mt-3 text-red-600 hover:text-red-700">

        Hapus

    </button>

</div>
`);

        });


        /*
        |--------------------------------------------------------------------------
        | HAPUS LAYANAN
        |--------------------------------------------------------------------------
        */

        container.addEventListener('click', function(e) {

            if (e.target.classList.contains('hapusLayanan')) {

                const items = container.querySelectorAll('.layanan-item');

                if (items.length > 1) {
                    e.target.closest('.layanan-item').remove();
                } else {
                    alert('Minimal harus memiliki satu layanan.');
                }

            }

        });

    });
</script>
