@extends('layouts.user.app')

@section('title', 'Temukan Layanan Laundry')

@section('content')

    {{-- ================= LEAFLET CSS ================= --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        .leaflet-pane,
        .leaflet-top,
        .leaflet-bottom,
        .leaflet-control {
            z-index: 10 !important;
        }

        #mainMap {
            height: 420px;
            width: 100%;
            background: #e5e7eb;
        }

        input[type=range] {
            accent-color: #14B8A6;
        }

        .leaflet-container {
            z-index: 1 !important;
        }

        .badge-jarak {
            position: absolute;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.65);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 12px;
            border-radius: 999px;
            white-space: nowrap;
            z-index: 999;
            pointer-events: none;
        }

        .map-wrapper.is-fullscreen {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 9999;
            border-radius: 0 !important;
        }

        .map-wrapper.is-fullscreen>div[id^="rmap-"] {
            height: 100% !important;
        }

        .btn-fullscreen {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 999;
            background: white;
            border: 2px solid rgba(0, 0, 0, 0.2);
            border-radius: 6px;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
        }

        .btn-fullscreen:hover {
            background: #f4f4f4;
        }
    </style>

    <section class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-10 xl:px-14 py-16 pt-[72px]">

        {{-- HEADER --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#14B8A6]">
                    Temukan Layanan Laundry Terdekat
                </h1>

                <p class="text-gray-500 mt-2 max-w-2xl">
                    Sesuaikan filter pencarian dan temukan laundry terbaik di sekitar Anda
                </p>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            {{-- ================= FILTER ================= --}}
            <aside class="lg:col-span-3 bg-white rounded-2xl shadow-lg p-6 space-y-6 lg:sticky lg:top-28 h-fit">

                <h2 class="text-lg font-semibold">🔍 Filter Pencarian</h2>

                {{-- Rating --}}
                <div>
                    <label class="flex justify-between text-sm font-semibold mb-2">
                        <span>Rating</span>
                        <span class="text-[#F97316]">⭐ <span id="ratingValue">4.0</span></span>
                    </label>
                    <input id="ratingRange" type="range" min="1" max="5" step="0.5" value="4"
                        class="w-full">
                </div>

                {{-- Harga --}}
                <div>
                    <label class="text-sm font-semibold mb-2 block">Rentang Harga</label>
                    <div class="flex gap-2">
                        <input id="minHarga" type="number" max="30000" min="0"
                            class="w-full border rounded-lg px-3 py-2" placeholder="Min">
                        <input id="maxHarga" type="number" max="30000" min="0"
                            class="w-full border rounded-lg px-3 py-2" placeholder="Max">
                    </div>
                </div>

                {{-- Jenis Layanan --}}
                <div>

                    <label class="text-sm font-semibold mb-2 block">
                        Jenis Layanan
                    </label>

                    <div class="relative">

                        <input id="layananSearch" type="text" autocomplete="off" placeholder="Cari layanan laundry..."
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#14B8A6]">

                        <div id="autocompleteResult"
                            class="hidden absolute left-0 right-0 mt-1 bg-white border rounded-lg shadow-lg max-h-56 overflow-y-auto z-50">
                        </div>

                    </div>

                    {{-- Badge layanan terpilih --}}
                    <div id="selectedServices" class="flex flex-wrap gap-2 mt-3">
                    </div>

                </div>

                <div class="space-y-3">

                    <button id="btnFilter"
                        class="w-full bg-[#14B8A6] text-white py-3 rounded-xl font-semibold hover:bg-[#0D9488]">

                        Terapkan Filter

                    </button>

                    <button id="btnReset"
                        class="w-full border border-gray-300 bg-white text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-50">

                        🔄 Reset Filter

                    </button>

                </div>

            </aside>

            {{-- ================= KONTEN KANAN ================= --}}
            <div class="lg:col-span-9 space-y-10">

                {{-- MAP UTAMA --}}
                <div class="bg-white rounded-2xl shadow-lg p-5">
                    <h2 class="font-semibold mb-4">Peta Laundry</h2>
                    <div class="relative overflow-hidden rounded-xl">
                        <div id="mainMap"></div>
                    </div>
                </div>

                {{-- HASIL REKOMENDASI --}}
                <div id="recommendationResult" class="bg-white rounded-2xl shadow-lg p-10 text-center text-gray-400">
                    <div
                        class="animate-spin h-8 w-8 border-4 border-[#14B8A6] border-t-transparent rounded-full mx-auto mb-4">
                    </div>
                    Tunggu sebentar, kami sedang menyiapkannya untukmu...
                </div>

            </div>
        </div>
    </section>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* ============================================================
             * 0. REFERENSI ELEMEN DOM
             * ============================================================ */
            const ratingRange = document.getElementById('ratingRange');
            const ratingValue = document.getElementById('ratingValue');
            const minHarga = document.getElementById('minHarga');
            const maxHarga = document.getElementById('maxHarga');
            const layananInput = document.getElementById('layananSearch');
            const hasilAutocomplete = document.getElementById('autocompleteResult');
            const selectedContainer = document.getElementById('selectedServices');
            const btnFilter = document.getElementById('btnFilter');
            const btnReset = document.getElementById('btnReset');
            const resultContainer = document.getElementById('recommendationResult');

            /* ============================================================
             * 1. STATE (variabel yang berubah selama interaksi user)
             * ============================================================ */
            let userLat = null;
            let userLng = null;
            let daftarLayanan = []; // seluruh opsi layanan dari server
            let layananDipilih = []; // layanan yang sudah dipilih user

            /* ============================================================
             * 2. INISIALISASI MAP UTAMA
             * ============================================================ */
            const mainMap = L.map('mainMap').setView([-5.147665, 119.432732], 12);
            const markersLayer = L.layerGroup().addTo(mainMap);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(mainMap);

            /* ============================================================
             * 3. HELPER: BUAT ICON MARKER KUSTOM
             * ============================================================ */
            function buatIcon(bg, emoji) {
                return L.divIcon({
                    html: `
            <div style="
                background:${bg};
                color:#fff;
                border-radius:50%;
                width:32px;
                height:32px;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:15px;
                border:2px solid #fff;
                box-shadow:0 2px 6px rgba(0,0,0,.4);
            ">${emoji}</div>
        `,
                    className: '',
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });
            }

            /* ============================================================
             * 4. HELPER: TOGGLE FULLSCREEN PADA MINI MAP
             * ============================================================ */
            function toggleFullscreen(id, map) {
                const wrapper = document.getElementById('mapwrap-' + id);
                const btn = document.getElementById('btnfs-' + id);

                const isFullscreen = wrapper.classList.toggle('is-fullscreen');
                btn.textContent = isFullscreen ? '✕' : '⛶';

                setTimeout(() => map.invalidateSize(), 50);
            }

            /* ============================================================
             * 5. HELPER: BATASI INPUT HARGA MAKSIMAL
             * ============================================================ */
            function batasiHarga(input) {
                if (input.value > 30000) {
                    input.value = 30000;
                }
            }

            /* ============================================================
             * 6. HELPER: MUAT SEMUA LAUNDRY KE MARKER LAYER
             * ============================================================ */
            function muatSemuaLaundry() {
                fetch("{{ route('user.maps.laundry') }}")
                    .then(r => r.json())
                    .then(data => {
                        markersLayer.clearLayers();
                        data.forEach(u => {
                            L.marker([u.latitude, u.longitude], {
                                    icon: buatIcon('#F97316', '👕')
                                })
                                .addTo(markersLayer)
                                .bindTooltip(u.nama_laundry);
                        });
                    });
            }

            /* ============================================================
             * 7. HELPER: TAMBAH LAYANAN TERPILIH (BADGE)
             * ============================================================ */
            function tambahLayanan(nama) {
                layananDipilih.push(nama);

                const badge = document.createElement('div');
                badge.className =
                    'flex items-center gap-2 bg-[#14B8A6]/10 text-[#14B8A6] px-3 py-1 rounded-full';

                badge.innerHTML = `
        ${nama}
        <button type="button">&times;</button>
        <input type="hidden" name="jenis[]" value="${nama}">
    `;

                badge.querySelector('button').onclick = function() {
                    layananDipilih = layananDipilih.filter(l => l !== nama);
                    badge.remove();
                };

                selectedContainer.appendChild(badge);

                layananInput.value = '';
                hasilAutocomplete.innerHTML = '';
                hasilAutocomplete.classList.add('hidden');
            }

            /* ============================================================
             * 8. HELPER: RESET SEMUA FILTER KE KONDISI AWAL
             * ------------------------------------------------------------
             * PERUBAHAN: setelah direset, hasil rekomendasi tidak lagi
             * dikosongkan ke placeholder, tapi kembali memuat rekomendasi
             * berdasarkan jarak terdekat (mode awal) via muatRekomendasiAwal().
             * ============================================================ */
            function resetFilter() {
                // Reset filter rating

                ratingRange.value = 4;
                ratingValue.textContent = '4.0';

                // Reset filter harga
                minHarga.value = '';
                maxHarga.value = '';

                // Reset layanan terpilih
                layananDipilih = [];
                layananInput.value = '';
                hasilAutocomplete.innerHTML = '';
                hasilAutocomplete.classList.add('hidden');
                selectedContainer.innerHTML = '';

                // Muat ulang seluruh marker laundry
                muatSemuaLaundry();

                // Fokuskan kembali peta ke lokasi user & muat ulang rekomendasi terdekat
                if (userLat && userLng) {
                    mainMap.setView([userLat, userLng], 13);
                    muatRekomendasiAwal();
                } else {
                    resultContainer.innerHTML = `
            <div class="text-center text-gray-400 py-10">
                Silakan gunakan filter untuk mendapatkan rekomendasi laundry
            </div>
        `;
                }
            }

            /* ============================================================
             * 9. HELPER: INISIALISASI MINI MAP + RUTE (OSRM)
             * ============================================================ */
            function initMiniMap(uid, laundryLat, laundryLng, nameLaundry) {
                const userPoint = L.latLng(userLat, userLng);
                const laundryPoint = L.latLng(laundryLat, laundryLng);

                const map = L.map('rmap-' + uid, {
                    zoomControl: true,
                    scrollWheelZoom: false
                }).setView(userPoint, 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                L.marker(userPoint, {
                        icon: buatIcon('#14B8A6', '📍')
                    })
                    .addTo(map)
                    .bindPopup('Lokasi Anda');

                L.marker(laundryPoint, {
                        icon: buatIcon('#F97316', '👕')
                    })
                    .addTo(map)
                    .bindPopup(nameLaundry);

                fetch(
                        `https://router.project-osrm.org/route/v1/driving/${userLng},${userLat};${laundryLng},${laundryLat}?overview=full&geometries=geojson`
                    )
                    .then(r => r.json())
                    .then(d => {
                        const coords = d.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
                        const line = L.polyline(coords, {
                            color: '#14B8A6',
                            weight: 4
                        }).addTo(map);
                        map.fitBounds(line.getBounds(), {
                            padding: [30, 30]
                        });
                    })
                    .catch(() => {
                        const line = L.polyline([userPoint, laundryPoint], {
                            color: '#F97316',
                            dashArray: '6,6'
                        }).addTo(map);
                        map.fitBounds(line.getBounds(), {
                            padding: [30, 30]
                        });
                    });

                document.getElementById('btnfs-' + uid)
                    .addEventListener('click', () => toggleFullscreen(uid, map));

                setTimeout(() => map.invalidateSize(), 400);
            }

            /* ============================================================
             * 10. HELPER: RENDER SATU KARTU HASIL REKOMENDASI
             * ============================================================ */
            function renderKartuRekomendasi(item, idx) {
                const uid = 'r' + idx;

                const gmaps =
                    `https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLng}&destination=${item.latitude},${item.longitude}`;

                const bintangPenuh = '★'.repeat(Math.round(item.rating));
                const bintangKosong = '☆'.repeat(5 - Math.round(item.rating));

                const layananHtml = item.layanan.map(l => `
<div class="flex justify-between items-center py-2 border-b last:border-0">

    <div>

        <div class="font-medium text-gray-800">
            ${l.nama}
        </div>

        <div class="text-xs text-gray-500">
            ${l.estimasi ?? '-'}
        </div>

        <div class="text-xs text-amber-500 mt-1">
            ⭐ ${Number(l.rating).toFixed(1)}
        </div>

    </div>

    <div class="font-semibold text-[#14B8A6] whitespace-nowrap">
    Rp ${Number(l.harga).toLocaleString('id-ID')} <span class="text-xs font-normal text-gray-400">/kg</span>
</div>

</div>
`).join('');

                return `
        <div class="bg-white rounded-xl shadow-md p-5">

            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">
                        ${idx + 1}. ${item.nama_laundry}
                    </h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-amber-500">${bintangPenuh}${bintangKosong}</span>
                        <span class="text-sm text-gray-500">(${parseFloat(item.rating).toFixed(1)})</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-semibold text-[#14B8A6]">${item.jarak.toFixed(1)} km</div>
                    <div class="text-xs text-gray-400">dari lokasi Anda</div>
                </div>
            </div>

            <div id="mapwrap-${uid}" class="map-wrapper relative mt-4 rounded-xl overflow-hidden" style="height:220px">
                <div id="rmap-${uid}" style="height:100%"></div>
                <button id="btnfs-${uid}" class="btn-fullscreen">⛶</button>
            </div>

            <div class="mt-4 text-sm text-gray-600">
                <div class="flex gap-2">
                    <span>📍</span>
                    <span>${item.alamat}</span>
                </div>
            </div>

            <div class="mt-4">
                <h4 class="font-semibold text-gray-800 mb-2">Layanan yang sesuai filter</h4>
                <div class="space-y-2">${layananHtml}</div>
            </div>

            <div class="mt-5 flex gap-3">
                <a href="${gmaps}" target="_blank"
                    class="flex-1 text-center bg-[#14B8A6] text-white py-2.5 rounded-lg font-medium hover:bg-[#0D9488]">
                    Google Maps
                </a>
                <a href="${item.detail_url}" target="_blank"
                    class="flex-1 text-center border border-[#14B8A6] text-[#14B8A6] py-2.5 rounded-lg font-medium hover:bg-[#14B8A6] hover:text-white">
                    Lihat Detail
                </a>
            </div>

        </div>
    `;
            }

            /* ============================================================
             * 11. HANDLER: SUBMIT FILTER & AMBIL REKOMENDASI (MODE SAW)
             * ============================================================ */
            async function submitFilter() {
                if (!userLat || !userLng) {
                    alert('Lokasi belum tersedia');
                    return;
                }

                const jenis = [...layananDipilih];

                if (jenis.length === 0) {
                    alert('Silakan pilih minimal 1 jenis layanan');
                    return;
                }

                resultContainer.innerHTML =
                    '<div class="text-center py-10">🔄 Mencari rekomendasi...</div>';

                try {
                    const response = await fetch("{{ route('user.temukan.rekomendasi') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            latitude: userLat,
                            longitude: userLng,
                            rating: ratingRange.value,
                            harga_min: minHarga.value || null,
                            harga_max: maxHarga.value || null,
                            jenis: jenis
                        })
                    });

                    if (!response.ok) {
                        throw new Error(await response.text());
                    }

                    const data = await response.json();

                    if (!data.length) {
                        resultContainer.innerHTML = `
                <p class="text-center text-gray-400 italic py-10">
                    Tidak ada laundry yang sesuai dengan filter Anda.
                </p>
            `;
                        return;
                    }

                    resultContainer.innerHTML =
                        '<div class="space-y-6">' +
                        data.map((item, idx) => renderKartuRekomendasi(item, idx)).join('') +
                        '</div>';

                    requestAnimationFrame(() => {
                        data.forEach((item, idx) => {
                            initMiniMap('r' + idx, item.latitude, item.longitude, item
                                .nama_laundry);
                        });
                    });

                } catch (error) {
                    console.error(error);
                    resultContainer.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                <h3 class="text-red-600 font-semibold mb-2">Terjadi kesalahan</h3>
                <p class="text-sm text-gray-500">Gagal mengambil data rekomendasi laundry.</p>
            </div>
        `;
                }
            }

            /* ============================================================
             * 11B. HANDLER: MUAT REKOMENDASI AWAL (MODE JARAK TERDEKAT)
             * ------------------------------------------------------------
             * BARU: dipanggil sekali saat geolocation berhasil didapat,
             * sebelum user menerapkan filter apa pun. Menampilkan loading
             * state, lalu mengambil data dari endpoint 'user.laundry.terdekat'
             * yang mengurutkan murni berdasarkan jarak (Haversine), TANPA SAW.
             * ============================================================ */
            async function muatRekomendasiAwal() {
                resultContainer.innerHTML = `
        <div class="text-center py-10 text-gray-400">
            <div class="animate-spin h-8 w-8 border-4 border-[#14B8A6] border-t-transparent rounded-full mx-auto mb-4"></div>
            Tunggu sebentar, kami sedang menyiapkannya untukmu...
        </div>
    `;

                try {
                    const url =
                        `{{ route('user.laundry.terdekat') }}?latitude=${userLat}&longitude=${userLng}`;
                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(await response.text());
                    }

                    const data = await response.json();

                    if (!data.length) {
                        resultContainer.innerHTML = `
                <p class="text-center text-gray-400 italic py-10">
                    Belum ada laundry di sekitar Anda.
                </p>
            `;
                        return;
                    }

                    resultContainer.innerHTML =
                        '<div class="space-y-6">' +
                        data.map((item, idx) => renderKartuRekomendasi(item, idx)).join('') +
                        '</div>';

                    requestAnimationFrame(() => {
                        data.forEach((item, idx) => {
                            initMiniMap('r' + idx, item.latitude, item.longitude, item
                                .nama_laundry);
                        });
                    });

                } catch (error) {
                    console.error(error);
                    resultContainer.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                <h3 class="text-red-600 font-semibold mb-2">Terjadi kesalahan</h3>
                <p class="text-sm text-gray-500">Gagal memuat laundry terdekat.</p>
            </div>
        `;
                }
            }

            /* ============================================================
             * 12. SETUP: SLIDER RADIUS & RATING
             * ============================================================ */

            if (ratingRange) {
                ratingValue.textContent = parseFloat(ratingRange.value).toFixed(1);
                ratingRange.addEventListener('input', () => {
                    ratingValue.textContent = parseFloat(ratingRange.value).toFixed(1);
                });
            }

            /* ============================================================
             * 13. SETUP: INPUT HARGA
             * ============================================================ */
            minHarga.addEventListener('input', () => batasiHarga(minHarga));
            maxHarga.addEventListener('input', () => batasiHarga(maxHarga));

            /* ============================================================
             * 14. SETUP: AUTOCOMPLETE LAYANAN
             * ============================================================ */
            fetch("{{ route('user.layanan.list') }}")
                .then(r => r.json())
                .then(data => {
                    daftarLayanan = data;
                });

            layananInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase().trim();
                hasilAutocomplete.innerHTML = '';

                if (keyword.length === 0) {
                    hasilAutocomplete.classList.add('hidden');
                    return;
                }

                const hasil = daftarLayanan.filter(nama =>
                    nama.toLowerCase().includes(keyword) &&
                    !layananDipilih.includes(nama)
                );

                if (!hasil.length) {
                    hasilAutocomplete.classList.add('hidden');
                    return;
                }

                hasilAutocomplete.classList.remove('hidden');

                hasil.forEach(nama => {
                    const item = document.createElement('div');
                    item.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer';
                    item.textContent = nama;
                    item.onclick = () => tambahLayanan(nama);
                    hasilAutocomplete.appendChild(item);
                });
            });

            /* ============================================================
             * 15. SETUP: GEOLOKASI USER
             * ------------------------------------------------------------
             * PERUBAHAN: setelah lokasi user didapat, langsung panggil
             * muatRekomendasiAwal() supaya user langsung melihat rekomendasi
             * berdasarkan jarak terdekat, tanpa perlu menerapkan filter dulu.
             * ============================================================ */
            navigator.geolocation.getCurrentPosition(
                pos => {
                    userLat = pos.coords.latitude;
                    userLng = pos.coords.longitude;

                    mainMap.setView([userLat, userLng], 13);

                    L.marker([userLat, userLng], {
                            icon: buatIcon('#14B8A6', '📍')
                        })
                        .addTo(mainMap)
                        .bindPopup('<b>Lokasi Anda</b>')
                        .openPopup();

                    // Muat rekomendasi awal berdasarkan jarak terdekat
                    muatRekomendasiAwal();
                },
                () => alert('Izin lokasi diperlukan')
            );

            /* ============================================================
             * 16. SETUP: MUAT DATA LAUNDRY AWAL
             * ============================================================ */
            muatSemuaLaundry();

            /* ============================================================
             * 17. SETUP: TOMBOL FILTER & RESET
             * ============================================================ */
            btnFilter.addEventListener('click', submitFilter);
            btnReset.addEventListener('click', resetFilter);

        }); // end DOMContentLoaded
    </script>

@endsection
