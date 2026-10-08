@if (!Cookie::has('visitor_token'))

    <div id="visitorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">

            <div class="p-8">

                <div class="text-center mb-8">

                    <div class="text-5xl mb-4">
                        👋
                    </div>

                    <h2 class="text-3xl font-bold text-[#14B8A6]">
                        Selamat Datang
                    </h2>

                    <p class="text-gray-600 mt-3">
                        Sebelum menggunakan <strong>LaundryFinder</strong>,
                        silakan masukkan nama Anda terlebih dahulu.
                    </p>

                </div>

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('visitor.register') }}">

                    @csrf

                    <div class="mb-5">

                        <label class="block text-sm font-semibold mb-2">
                            Nama Pengunjung
                        </label>

                        <input type="text" name="nama" value="{{ old('nama') }}" maxlength="100" required
                            autofocus
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-[#14B8A6] focus:ring-2 focus:ring-[#14B8A6] outline-none">

                    </div>

                    <button type="submit"
                        class="w-full bg-[#14B8A6] hover:bg-[#0F766E] text-white py-3 rounded-lg font-semibold transition">

                        Mulai Jelajahi

                    </button>

                </form>

            </div>

        </div>

    </div>

@endif
