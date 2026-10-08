@extends('layouts.user.app')

@section('title', 'Keunggulan LaundryFinder')

@section('content')

    <section class="bg-white min-h-screen pt-[90px] pb-20">

        <div class="max-w-7xl mx-auto px-6">

            {{-- Header --}}
            <div class="text-center mb-16">

                <span class="inline-block px-4 py-2 bg-[#14B8A6]/10 text-[#14B8A6] rounded-full text-sm font-semibold mb-4">
                    LaundryFinder
                </span>

                <h1 class="text-4xl lg:text-5xl font-extrabold text-[#14B8A6] font-manrope mb-5">
                    Keunggulan Jasa Kami
                </h1>

                <p class="max-w-3xl mx-auto text-gray-600 text-lg leading-relaxed">
                    LaundryFinder membantu Anda menemukan layanan laundry terbaik
                    berdasarkan lokasi, jarak, serta rekomendasi yang sesuai dengan
                    kebutuhan Anda.
                </p>

            </div>

            {{-- Features --}}
            <div class="grid md:grid-cols-3 gap-10">

                {{-- Feature 1 --}}
                <div
                    class="bg-white border border-gray-200 rounded-2xl p-8 text-center hover:shadow-xl transition duration-300">

                    <div
                        class="w-20 h-20 mx-auto rounded-full bg-[#F97316] flex items-center justify-center text-white mb-6">

                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5V10H2v10h5m10 0V4a1 1 0 00-1-1H8a1 1 0 00-1 1v16m10 0H7" />
                        </svg>

                    </div>

                    <h3 class="text-2xl font-bold text-[#14B8A6] mb-4">
                        Berbasis Lokasi
                    </h3>

                    <p class="text-gray-600 leading-relaxed">
                        Sistem menggunakan lokasi pengguna untuk menampilkan laundry
                        yang berada di sekitar sehingga pencarian menjadi lebih
                        cepat dan efisien.
                    </p>

                </div>

                {{-- Feature 2 --}}
                <div
                    class="bg-white border border-gray-200 rounded-2xl p-8 text-center hover:shadow-xl transition duration-300">

                    <div
                        class="w-20 h-20 mx-auto rounded-full bg-[#F97316] flex items-center justify-center text-white mb-6">

                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m0-4h.01M12 18h.01M12 6h.01" />
                        </svg>

                    </div>

                    <h3 class="text-2xl font-bold text-[#14B8A6] mb-4">
                        Cepat & Akurat
                    </h3>

                    <p class="text-gray-600 leading-relaxed">
                        Perhitungan jarak menggunakan algoritma Haversine sehingga
                        mampu memberikan estimasi jarak yang akurat dari posisi
                        pengguna menuju laundry.
                    </p>

                </div>

                {{-- Feature 3 --}}
                <div
                    class="bg-white border border-gray-200 rounded-2xl p-8 text-center hover:shadow-xl transition duration-300">

                    <div
                        class="w-20 h-20 mx-auto rounded-full bg-[#F97316] flex items-center justify-center text-white mb-6">

                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 7v4a2 2 0 002 2h4m10-6v4a2 2 0 01-2 2h-4m0 4v4a2 2 0 002 2h4m-10-6v4a2 2 0 01-2 2H3" />
                        </svg>

                    </div>

                    <h3 class="text-2xl font-bold text-[#14B8A6] mb-4">
                        Berbasis Rekomendasi
                    </h3>

                    <p class="text-gray-600 leading-relaxed">
                        Laundry ditampilkan berdasarkan hasil rekomendasi sesuai
                        preferensi pengguna seperti radius, harga, rating, dan jenis
                        layanan yang dipilih.
                    </p>

                </div>

            </div>

            {{-- Button --}}
            <div class="text-center mt-16">

                <a href="{{ route('user.temukan') }}"
                    class="inline-flex items-center px-8 py-4 bg-[#14B8A6] text-white font-semibold rounded-xl hover:bg-[#0D9488] transition">

                    Temukan Laundry Sekarang

                </a>

            </div>

        </div>

    </section>

@endsection
