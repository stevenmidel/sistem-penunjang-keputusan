{{-- Contact Section --}}
<section class="bg-white py-20 border-t border-[#E5E7EB]">
    <div class="max-w-7xl mx-auto px-6">

        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#14B8A6] mb-12 text-center font-manrope">
            Hubungi Kami
        </h2>

        <div class="grid lg:grid-cols-2 gap-12 items-start">

            {{-- KIRI: Informasi Kontak --}}
            <div class="space-y-8">

                {{-- Brand --}}
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/LogoLaundry.svg') }}" alt="LaundryFinder Logo" class="h-14 w-14">
                    <div>
                        <h3 class="text-2xl font-bold text-[#14B8A6] font-manrope">
                            LaundryFinder
                        </h3>
                        <p class="text-[#111827] text-sm">
                            Sistem Rekomendasi Laundry Terdekat
                        </p>
                    </div>
                </div>

                {{-- Email --}}
                <div class="flex items-center space-x-4">
                    <div class="bg-[#F97316] text-white p-3 rounded-full">
                        ✉️
                    </div>
                    <div>
                        <p class="font-semibold text-[#14B8A6]">Email</p>
                        <p class="text-[#111827]">support@laundryfinder.id</p>
                    </div>
                </div>

                {{-- Contact --}}
                <div class="flex items-center space-x-4">
                    <div class="bg-[#F97316] text-white p-3 rounded-full">
                        📞
                    </div>
                    <div>
                        <p class="font-semibold text-[#14B8A6]">Kontak</p>
                        <p class="text-[#111827]">+62 822-9698-9326</p>
                    </div>
                </div>

                {{-- Social Media --}}
                <div>
                    <p class="font-semibold text-[#14B8A6] mb-3">Media Sosial</p>
                    <div class="flex space-x-4">

                        <a target="blank" href="https://wa.link/00mefq"
                            class="flex items-center space-x-2 text-[#111827] hover:text-[#14B8A6] transition">
                            💬 <span>WhatsApp</span>
                        </a>

                    </div>
                </div>

            </div>

            {{-- KANAN: MAP --}}
            <div class="w-full h-[350px] rounded-xl overflow-hidden shadow-md">
                <div id="contactMap" class="w-full h-full"></div>
            </div>

        </div>
    </div>
</section>

{{-- Leaflet Map Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const latitude = -5.119536903945367;
        const longitude = 119.50335235652605;

        const map = L.map('contactMap').setView([latitude, longitude], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        L.marker([latitude, longitude])
            .addTo(map)
            .bindPopup('<b>LaundryFinder</b><br>Kantor Operasional')
            .openPopup();
    });
</script>
