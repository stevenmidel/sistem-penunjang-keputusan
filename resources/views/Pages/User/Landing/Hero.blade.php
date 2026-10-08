{{-- Hero Section --}}
@include('pages.user.landing.visitor-modal')
<section class="relative bg-white min-h-screen flex items-center">
    <div class="max-w-7xl mx-auto px-6 flex flex-col lg:flex-row items-center lg:items-start gap-12">

        {{-- Kiri: Teks utama hero --}}
        <div class="lg:w-1/2 text-center lg:text-left">
            <h2 class="text-5xl lg:text-6xl font-extrabold text-[#14B8A6] mb-6 font-manrope leading-tight">
                Temukan Laundry Terdekat dengan Mudah
            </h2>

            <p class="text-[#111827] text-lg lg:text-xl max-w-lg mb-8">
                Sistem rekomendasi laundry berdasarkan jarak, harga, dan kualitas layanan.
                Dapatkan layanan terbaik di sekitar Anda dengan cepat dan praktis.
            </p>

            {{-- Button --}}
            <div class="flex justify-center lg:justify-start">
                <a href="{{ route('user.temukan') }}"
                    class="inline-block bg-[#0EA5E9] text-white px-8 py-4 rounded-lg
              font-semibold text-lg hover:bg-[#FACC15] hover:text-[#1F2937]
              transition duration-300 shadow-md">
                    Temukan Laundry
                </a>
            </div>
        </div>

        {{-- Kanan: Gambar hero --}}
        <div class="lg:w-1/2 flex justify-center lg:justify-end">
            <img src="{{ asset('images/LandingPage.png') }}" alt="Layanan Laundry"
                class="w-full max-w-lg lg:max-w-2xl object-contain">
        </div>

    </div>
</section>
