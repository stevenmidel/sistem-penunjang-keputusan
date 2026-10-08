@extends('layouts.AdminLaundry.app')

@section('title', 'Tambah Unit Usaha')

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
                Tambah Unit Usaha
            </h1>

            <section class="bg-white rounded-2xl shadow-md p-8">

                @if (session('success'))
                    <div class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <div id="clientError" class="hidden mb-6 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                    Mohon lengkapi semua field wajib sebelum menyimpan data.
                </div>

                <form id="unitForm" method="POST" action="{{ route('admin.laundry.unit.store') }}"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- NAMA --}}
                    <input type="text" name="nama_laundry" value="{{ old('nama_laundry') }}"
                        class="required w-full px-4 py-3 rounded-lg border" placeholder="Nama Laundry">

                    {{-- FOTO --}}
                    <input type="file" name="foto_laundry" class="required block w-full border rounded-lg px-4 py-3">

                    {{-- ALAMAT --}}
                    <input type="text" name="nama_jalan" value="{{ old('nama_jalan') }}"
                        class="required w-full px-4 py-3 rounded-lg border" placeholder="Nama Jalan">

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
                            <div id="addMap" class="w-full h-full"></div>
                        </div>

                        {{-- MODE MANUAL --}}
                        <div id="manualWrapper" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                            <input type="number" step="any" id="latitudeManual"
                                class="w-full px-4 py-3 rounded-lg border" placeholder="Latitude (Contoh: -5.1195369)">
                            <input type="number" step="any" id="longitudeManual"
                                class="w-full px-4 py-3 rounded-lg border" placeholder="Longitude (Contoh: 119.5033523)">
                        </div>

                        <input type="hidden" name="latitude" id="latitude" class="required">
                        <input type="hidden" name="longitude" id="longitude" class="required">
                    </div>

                    {{-- DAFTAR LAYANAN --}}
                    <div>

                        <div class="flex justify-between items-center mb-3">
                            <label class="font-semibold text-gray-700">
                                Daftar Layanan
                            </label>

                            <button type="button" id="btnTambahLayanan"
                                class="bg-[#14B8A6] text-white px-4 py-2 rounded-lg">
                                + Tambah Layanan
                            </button>
                        </div>

                        <div id="layananContainer">

                            <div class="layanan-item border rounded-lg p-4 mb-4">

                                <input type="text" name="nama_layanan[]"
                                    class="required w-full border rounded-lg px-4 py-3 mb-3" placeholder="Nama Layanan">

                                <input type="number" name="harga[]"
                                    class="required w-full border rounded-lg px-4 py-3 mb-3" placeholder="Harga Kiloan">

                                <input type="text" name="estimasi[]" class="w-full border rounded-lg px-4 py-3"
                                    placeholder="Estimasi (Contoh: 3 Jam / 1 Hari)">

                            </div>

                        </div>

                    </div>

                    {{-- JAM --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="time" name="weekday_open" value="{{ old('weekday_open') }}"
                            class="required px-4 py-3 border rounded-lg">
                        <input type="time" name="weekday_close" value="{{ old('weekday_close') }}"
                            class="required px-4 py-3 border rounded-lg">
                        <input type="time" name="weekend_open" value="{{ old('weekend_open') }}"
                            class="required px-4 py-3 border rounded-lg">
                        <input type="time" name="weekend_close" value="{{ old('weekend_close') }}"
                            class="required px-4 py-3 border rounded-lg">
                    </div>

                    {{-- KETERANGAN --}}
                    <textarea name="keterangan" class="w-full px-4 py-3 border rounded-lg" placeholder="Keterangan tambahan">{{ old('keterangan') }}</textarea>

                    {{-- ACTION --}}
                    <div class="pt-6 flex justify-between">
                        <a href="{{ route('admin.laundry.unit.index') }}" class="px-6 py-3 bg-gray-300 rounded-lg">
                            Batal
                        </a>

                        <button type="submit" class="px-6 py-3 bg-[#14B8A6] text-white rounded-lg">
                            Simpan Unit
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

        let lat = -5.1195369;
        let lng = 119.5033523;

        const map = L.map('addMap').setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        const marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);
        updateLatLng(lat, lng);

        marker.on('dragend', e => updateLatLng(e.target.getLatLng().lat, e.target.getLatLng().lng));
        map.on('click', e => {
            marker.setLatLng(e.latlng);
            updateLatLng(e.latlng.lat, e.latlng.lng);
        });

        function updateLatLng(lat, lng) {
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        }

        // TOGGLE MODE LOKASI (PETA / MANUAL)
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
                updateLatLng(pos.lat, pos.lng);

                map.invalidateSize(); // fix render peta saat wrapper sebelumnya hidden
            } else {
                mapWrapper.classList.add('hidden');
                manualWrapper.classList.remove('hidden');

                // hidden field ikut kosong dulu sampai user isi manual
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
            }
        }

        modePeta.addEventListener('change', () => setMode('peta'));
        modeManual.addEventListener('change', () => setMode('manual'));

        // sinkronkan input manual ke hidden field
        [latitudeManual, longitudeManual].forEach(input => {
            input.addEventListener('input', () => {
                document.getElementById('latitude').value = latitudeManual.value;
                document.getElementById('longitude').value = longitudeManual.value;
            });
        });

        const container = document.getElementById('layananContainer');
        const btnTambah = document.getElementById('btnTambahLayanan');

        btnTambah.addEventListener('click', function() {

            const item = document.createElement('div');

            item.className = "layanan-item border rounded-lg p-4 mb-4";

            item.innerHTML = `
        <input
            type="text"
            name="nama_layanan[]"
            class="required w-full border rounded-lg px-4 py-3 mb-3"
            placeholder="Nama Layanan">

        <input
            type="number"
            name="harga[]"
            class="required w-full border rounded-lg px-4 py-3 mb-3"
            placeholder="Harga">

        <input
            type="text"
            name="estimasi[]"
            class="w-full border rounded-lg px-4 py-3 mb-3"
            placeholder="Estimasi">

        <button
            type="button"
            class="hapusLayanan bg-red-500 text-white px-4 py-2 rounded">
            Hapus
        </button>
    `;

            container.appendChild(item);

        });

        document.addEventListener('click', function(e) {

            if (e.target.classList.contains('hapusLayanan')) {

                e.target.closest('.layanan-item').remove();

            }

        });

        // VALIDASI
        document.getElementById('unitForm').addEventListener('submit', function(e) {

            let valid = true;

            document.querySelectorAll('.required').forEach(function(field) {

                if (field.value.trim() === '') {

                    valid = false;

                }

            });

            document.querySelectorAll('input[name="nama_layanan[]"]').forEach(function(field) {

                if (field.value.trim() === '') {

                    valid = false;

                }

            });

            document.querySelectorAll('input[name="harga[]"]').forEach(function(field) {

                if (field.value.trim() === '') {

                    valid = false;

                }

            });

            if (modeManual.checked) {
                const la = parseFloat(latitudeManual.value);
                const lo = parseFloat(longitudeManual.value);

                if (isNaN(la) || isNaN(lo) || la < -90 || la > 90 || lo < -180 || lo > 180) {
                    valid = false;
                }
            }

            if (!valid) {

                e.preventDefault();

                document.getElementById('clientError').classList.remove('hidden');

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });

            }

        });
    });
</script>
