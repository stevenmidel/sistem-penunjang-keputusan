@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

<nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between px-8 py-4">

        {{-- Logo --}}
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-[#E0F2FE] flex items-center justify-center">
                <span class="text-xl font-extrabold text-[#14B8A6]">L</span>
            </div>
            <span class="text-lg font-extrabold text-[#14B8A6]">
                Admin Laundry
            </span>
        </div>

        {{-- Profile (Trigger Modal) --}}
        @if ($user)
            <button onclick="openProfileModal()" class="flex items-center gap-3 hover:opacity-80 transition">

                {{-- Avatar --}}
                <div
                    class="w-9 h-9 rounded-full bg-[#E0F2FE]
                           flex items-center justify-center overflow-hidden">

                    @if ($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-[#14B8A6] font-bold text-sm">
                            {{ strtoupper(substr($user->username, 0, 1)) }}
                        </span>
                    @endif
                </div>

                {{-- Nama User --}}
                <span class="text-sm font-semibold text-gray-800">
                    {{ $user->nama_layanan ?? $user->username }}
                </span>
            </button>
        @endif

    </div>
</nav>
