@extends('layouts.user.app')

@section('title', 'Partner Laundry')

@section('content')

    <section class="bg-white min-h-screen pt-[90px] pb-20">

        <div class="max-w-7xl mx-auto px-6" x-data="{ open: false }">

            {{-- Header --}}
            <div class="text-center mb-16">

                <span class="inline-block px-4 py-2 bg-[#14B8A6]/10 text-[#14B8A6] rounded-full text-sm font-semibold mb-4">
                    LaundryFinder Partner
                </span>

                <h1 class="text-4xl lg:text-5xl font-extrabold text-[#14B8A6] font-manrope mb-5">
                    Laundry yang Telah Bergabung
                </h1>

                <p class="max-w-3xl mx-auto text-gray-600 text-lg leading-relaxed">
                    Seluruh laundry yang tergabung dalam LaundryFinder telah melalui
                    proses pendataan sehingga dapat ditemukan berdasarkan lokasi,
                    rating, dan layanan yang tersedia.
                </p>

            </div>

            {{-- List Laundry --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                @forelse ($laundries as $laundry)

                    {{-- 4 Laundry Pertama --}}
                    <div x-show="{{ $loop->index }} < 4">

                        <a href="{{ route('user.laundry.detail', $laundry->id) }}"
                            class="group bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-xl transition duration-300 block">

                            <img src="{{ $laundry->foto_laundry ? asset($laundry->foto_laundry) : asset('images/TempatLaundry.jpeg') }}"
                                alt="{{ $laundry->nama_laundry }}"
                                class="w-full h-60 object-cover group-hover:scale-105 transition duration-500">

                            <div class="p-6">

                                <div class="flex items-center text-[#F97316] text-lg mb-3">

                                    @php
                                        $fullStar = floor($laundry->rating);
                                    @endphp

                                    @for ($i = 1; $i <= 5; $i++)
                                        {{ $i <= $fullStar ? '★' : '☆' }}
                                    @endfor

                                    <span class="ml-2 text-sm text-gray-500">
                                        ({{ number_format($laundry->rating, 1) }})
                                    </span>

                                </div>

                                <h2 class="text-2xl font-bold text-[#14B8A6] mb-2">
                                    {{ $laundry->nama_laundry }}
                                </h2>

                                <p class="text-gray-600">
                                    {{ $laundry->nama_jalan }}
                                </p>

                                <div class="mt-6">

                                    <span
                                        class="inline-flex items-center text-[#14B8A6] font-semibold group-hover:underline">

                                        Lihat Detail →

                                    </span>

                                </div>

                            </div>

                        </a>

                    </div>

                    {{-- Laundry Selanjutnya --}}
                    <template x-if="{{ $loop->index }} >= 4">

                        <div x-show="open" x-transition>

                            <a href="{{ route('user.laundry.detail', $laundry->id) }}"
                                class="group bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-xl transition duration-300 block">

                                <img src="{{ $laundry->foto_laundry ? asset($laundry->foto_laundry) : asset('images/TempatLaundry.jpeg') }}"
                                    alt="{{ $laundry->nama_laundry }}"
                                    class="w-full h-60 object-cover group-hover:scale-105 transition duration-500">

                                <div class="p-6">

                                    <div class="flex items-center text-[#F97316] text-lg mb-3">

                                        @php
                                            $fullStar = floor($laundry->rating);
                                        @endphp

                                        @for ($i = 1; $i <= 5; $i++)
                                            {{ $i <= $fullStar ? '★' : '☆' }}
                                        @endfor

                                        <span class="ml-2 text-sm text-gray-500">
                                            ({{ number_format($laundry->rating, 1) }})
                                        </span>

                                    </div>

                                    <h2 class="text-2xl font-bold text-[#14B8A6] mb-2">
                                        {{ $laundry->nama_laundry }}
                                    </h2>

                                    <p class="text-gray-600">
                                        {{ $laundry->nama_jalan }}
                                    </p>

                                    <div class="mt-6">

                                        <span
                                            class="inline-flex items-center text-[#14B8A6] font-semibold group-hover:underline">

                                            Lihat Detail →

                                        </span>

                                    </div>

                                </div>

                            </a>

                        </div>

                    </template>

                @empty

                    <div class="col-span-2">

                        <div class="bg-gray-50 rounded-2xl py-20 text-center">

                            <h2 class="text-2xl font-bold text-gray-500">
                                Belum Ada Partner Laundry
                            </h2>

                            <p class="text-gray-400 mt-3">
                                Saat ini belum terdapat laundry yang terdaftar pada sistem LaundryFinder.
                            </p>

                        </div>

                    </div>
                @endforelse

            </div>

            {{-- Button Expand --}}
            @if ($laundries->count() > 4)
                <div class="text-center mt-14">

                    <button @click="open = !open"
                        class="px-8 py-4 bg-[#14B8A6] text-white rounded-xl font-semibold hover:bg-[#0D9488] transition">

                        <span x-show="!open">
                            Lihat Laundry Lainnya
                        </span>

                        <span x-show="open">
                            Sembunyikan Laundry
                        </span>

                    </button>

                </div>
            @endif

        </div>

    </section>

@endsection
