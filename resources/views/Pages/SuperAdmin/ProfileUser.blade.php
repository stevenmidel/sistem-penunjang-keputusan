{{-- ================= PROFILE SUPER ADMIN MODAL ================= --}}
@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

<div id="profileModal" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/40">

    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl p-8 relative">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold text-[#14B8A6]">
                Profil Super Admin
            </h2>

            <button onclick="closeProfileModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                &times;
            </button>
        </div>

        {{-- Content --}}
        <form action="{{ route('superadmin.profile.update') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Foto Profile --}}
            <div class="flex items-center gap-6">
                <div
                    class="w-24 h-24 rounded-full bg-[#E0F2FE]
                            flex items-center justify-center overflow-hidden">

                    <img id="previewImage" src="{{ $user->foto ? asset($user->foto) : '' }}"
                        class="{{ $user->foto ? '' : 'hidden' }} w-full h-full object-cover" alt="Foto Profil">
                </div>

                <label class="cursor-pointer text-sm font-semibold text-[#14B8A6]">
                    Ubah Foto
                    <input type="file" name="foto" accept="image/*" class="hidden"
                        onchange="previewProfileImage(event)">
                </label>
            </div>

            {{-- Form Fields --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Username (EDITABLE) --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Username
                    </label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-[#14B8A6]
                               focus:outline-none">
                </div>

                {{-- Nama Layanan --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Nama Layanan
                    </label>
                    <input type="text" name="nama_layanan" value="{{ old('nama_layanan', $user->nama_layanan) }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-[#14B8A6]
                               focus:outline-none">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-[#14B8A6]
                               focus:outline-none">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Password Baru
                    </label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diganti"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-[#14B8A6]
                               focus:outline-none">
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Konfirmasi Password
                    </label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-[#14B8A6]
                               focus:outline-none">
                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-4 pt-6">
                <button type="button" onclick="closeProfileModal()"
                    class="px-6 py-3 rounded-lg border border-gray-300
                           font-semibold hover:bg-gray-100 transition">
                    Batal
                </button>

                <button type="submit"
                    class="px-6 py-3 rounded-lg bg-[#0EA5E9]
                           text-white font-semibold
                           hover:bg-[#FACC15] hover:text-[#1F2937]
                           transition shadow-md">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
