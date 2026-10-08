@extends('layouts.app')

@section('title', 'Peta Laundry')

@section('content')
<div id="map" class="h-[500px] rounded shadow"></div>

<script>
    // Tunggu seluruh DOM siap
    document.addEventListener('DOMContentLoaded', function () {
        // Buat map
        const map = L.map('map').setView([-0.8917, 119.8707], 13);

        // Tile OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Contoh marker
        L.marker([-0.8917, 119.8707])
            .addTo(map)
            .bindPopup('Lokasi Contoh Laundry')
            .openPopup();
    });
</script>
@endsection