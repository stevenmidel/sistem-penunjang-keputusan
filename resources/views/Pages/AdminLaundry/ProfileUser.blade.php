{{-- ================= PROFILE USER MODAL ================= --}}
@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

<div id="profileModal" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/40">

    {{-- Card --}}
    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl p-8 relative">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold text-[#14B8A6]">
                Profil Admin Laundry
            </h2>

            <button onclick="closeProfileModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                &times;
            </button>
        </div>

        {{-- Content --}}
        <form action="{{ route('admin.laundry.profile.update') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf

            {{-- Feedback --}}
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Foto Profile --}}
            <div class="flex items-center gap-6">
                <div
                    class="w-24 h-24 rounded-full bg-[#E0F2FE]
                            flex items-center justify-center overflow-hidden">

                    <img id="previewImage" src="{{ $user->foto ? asset($user->foto) : '' }}" alt="Foto Profil"
                        class="{{ $user->foto ? '' : 'hidden' }}
                                w-full h-full object-cover">
                </div>

                <label class="cursor-pointer text-sm font-semibold text-[#14B8A6]">
                    Ubah Foto
                    <input type="file" name="foto" accept="image/*" class="hidden"
                        onchange="previewProfileImage(event)">
                </label>
            </div>

            {{-- Form Fields --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Username (READ ONLY, TETAP TERKIRIM) --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Username
                    </label>
                    <input type="text" name="username" value="{{ $user->username }}"
                        class="w-full px-4 py-3 rounded-lg border
              focus:ring-2 focus:ring-[#14B8A6]
              focus:outline-none"
                        required>
                </div>

                {{-- Nama Layanan --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Nama Layanan Laundry
                    </label>
                    <input type="text" name="nama_layanan" value="{{ $user->nama_layanan }}"
                        class="w-full px-4 py-3 rounded-lg border
                                  focus:ring-2 focus:ring-[#14B8A6]
                                  focus:outline-none">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ $user->email }}"
                        class="w-full px-4 py-3 rounded-lg border
                                  focus:ring-2 focus:ring-[#14B8A6]
                                  focus:outline-none">
                </div>

                {{-- Password (OPSIONAL, SIAP DIPAKAI) --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Password Baru
                    </label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diganti"
                        class="w-full px-4 py-3 rounded-lg border">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Konfirmasi Password
                    </label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password"
                        class="w-full px-4 py-3 rounded-lg border">
                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-4 pt-6">
                <button type="button" onclick="closeProfileModal()"
                    class="px-6 py-3 rounded-lg border
                               font-semibold hover:bg-gray-100 transition">
                    Keluar
                </button>

                <button type="submit"
                    class="px-6 py-3 rounded-lg bg-[#0EA5E9]
                               text-white font-semibold
                               hover:bg-[#FACC15] hover:text-[#1F2937]
                               transition shadow-md">
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>
