{{-- ===================================================== --}}
{{-- Partner Laundry --}}
{{-- ===================================================== --}}
<section class="bg-gray-50 py-20">

    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-14">

            <span class="inline-block px-4 py-2 bg-[#14B8A6]/10 text-[#14B8A6] rounded-full text-sm font-semibold mb-4">

                🤝 Partner Terpercaya

            </span>

            <h2 class="text-3xl lg:text-4xl font-extrabold text-[#14B8A6] font-manrope mb-5">
                Laundry Pilihan yang Telah Bergabung
            </h2>

            <p class="max-w-3xl mx-auto text-gray-600 leading-relaxed">
                LaundryFinder bekerja sama dengan berbagai usaha laundry terpercaya
                untuk membantu Anda menemukan layanan terbaik berdasarkan lokasi,
                kualitas pelayanan, dan kebutuhan Anda.
            </p>

        </div>

        {{-- Preview Partner --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse ($laundries->take(3) as $laundry)

                <div
                    class="group bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-2xl hover:-translate-y-2 transition duration-300">

                    {{-- Foto --}}
                    <div class="overflow-hidden">

                        <img src="{{ $laundry->foto_laundry ? asset($laundry->foto_laundry) : asset('images/TempatLaundry.jpeg') }}"
                            alt="{{ $laundry->nama_laundry }}"
                            class="w-full h-64 object-cover group-hover:scale-110 transition duration-500">

                    </div>

                    {{-- Isi --}}
                    <div class="p-6">

                        <div class="flex items-center justify-between mb-3">

                            <div class="text-[#F97316]">

                                @php
                                    $fullStar = floor($laundry->rating);
                                @endphp

                                @for ($i = 1; $i <= 5; $i++)
                                    {{ $i <= $fullStar ? '★' : '☆' }}
                                @endfor

                            </div>

                            <span class="text-sm bg-[#14B8A6]/10 text-[#14B8A6] px-3 py-1 rounded-full">

                                {{ number_format($laundry->rating, 1) }}

                            </span>

                        </div>

                        <h3 class="text-2xl font-bold text-[#14B8A6] mb-2">
                            {{ $laundry->nama_laundry }}
                        </h3>

                        <p class="text-gray-600 text-sm mb-6">
                            {{ $laundry->nama_jalan }}
                        </p>

                        <a href="{{ route('user.laundry.detail', $laundry->id) }}"
                            class="inline-flex items-center font-semibold text-[#14B8A6] group-hover:text-[#F97316] transition">

                            Lihat Detail

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 ml-2 group-hover:translate-x-1 transition" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />

                            </svg>

                        </a>

                    </div>

                </div>

            @empty

                <div class="col-span-3">

                    <div class="bg-white rounded-2xl py-16 text-center border">

                        <h3 class="text-2xl font-bold text-gray-500">
                            Belum Ada Partner Laundry
                        </h3>

                        <p class="text-gray-400 mt-3">
                            Saat ini belum terdapat partner laundry yang terdaftar.
                        </p>

                    </div>

                </div>

            @endforelse

        </div>

        {{-- ===================================================== --}}
        {{-- Pengunjung Aktif --}}
        {{-- ===================================================== 
        <section class="bg-white py-20">

            <div class="max-w-7xl mx-auto px-6">

                {{-- Header -
                <div class="text-center mb-14">

                    <span
                        class="inline-block px-4 py-2 bg-[#14B8A6]/10 text-[#14B8A6] rounded-full text-sm font-semibold mb-4">

                        👥 Pengunjung Aktif

                    </span>

                    <h2 class="text-3xl lg:text-4xl font-extrabold text-[#14B8A6] font-manrope mb-5">
                        Pengunjung Paling Aktif LaundryFinder
                    </h2>

                    <p class="max-w-3xl mx-auto text-gray-600 leading-relaxed">
                        Terima kasih kepada seluruh pengguna yang telah mempercayai
                        <strong>LaundryFinder</strong> sebagai solusi untuk menemukan
                        layanan laundry terbaik. Berikut adalah daftar pengunjung yang
                        paling sering mengakses website kami.
                    </p>

                </div>

                {{-- Daftar Pengunjung 
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 divide-y">

                    @forelse($topVisitors as $visitor)
                        <div class="flex items-center justify-between p-6">

                            <div>

                                <h3 class="font-semibold text-lg text-gray-800">
                                    {{ $visitor->nama }}
                                </h3>

                                <p class="text-sm text-gray-500">
                                    Berkunjung sejak
                                    {{ $visitor->first_visit->format('d M Y') }}
                                </p>

                            </div>

                            <div class="text-right">

                                <div class="text-2xl font-bold text-[#14B8A6]">
                                    {{ $visitor->visit_count }}
                                </div>

                                <div class="text-sm text-gray-500">
                                    Jumlah Kunjungan
                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="py-16 text-center">

                            <h3 class="text-2xl font-bold text-gray-500">
                                Belum Ada Pengunjung
                            </h3>

                            <p class="text-gray-400 mt-3">
                                Jadilah pengunjung pertama yang menggunakan LaundryFinder.
                            </p>

                        </div>
                    @endforelse

                </div>

            </div>

        </section>

        --}}
        {{-- CTA --}}
        <div class="mt-16">

            <div class="bg-gradient-to-r from-[#14B8A6] to-[#0F766E] rounded-3xl px-8 lg:px-14 py-12 text-white">

                <div class="flex flex-col lg:flex-row items-center justify-between gap-8">

                    <div>

                        <h3 class="text-3xl font-bold mb-3">
                            Jelajahi Seluruh Partner Laundry
                        </h3>

                        <p class="text-teal-100 max-w-2xl leading-relaxed">
                            Masih banyak partner LaundryFinder lainnya yang siap
                            melayani kebutuhan Anda. Temukan laundry terbaik
                            berdasarkan lokasi, layanan, dan rekomendasi sistem.
                        </p>

                    </div>

                    <div>

                        <a href="{{ route('user.partner') }}"
                            class="inline-flex items-center px-8 py-4 bg-[#F97316] rounded-xl font-semibold text-white hover:bg-orange-600 transition shadow-lg">

                            Lihat Semua Partner

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />

                            </svg>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
