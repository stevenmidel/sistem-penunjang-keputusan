@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
@endphp

<nav class="fixed top-0 left-0 right-0 h-[72px] bg-white border-b border-gray-200 z-50">

    <div class="h-full px-6 flex items-center justify-between">

        {{-- Logo --}}
        <div class="flex items-center gap-3">

            <div class="w-10 h-10 rounded-lg bg-[#E0F2FE]
                        flex items-center justify-center">

                <span class="text-xl font-extrabold text-[#14B8A6]">
                    L
                </span>

            </div>

            <div>
                <span class="text-lg font-extrabold text-[#14B8A6]">
                    LaundryFinder
                </span>

                <p class="text-xs text-gray-500">
                    Pengawas Laundry
                </p>
            </div>

        </div>


        {{-- Profile --}}
        @if ($user)

            <div class="flex items-center gap-3">

                {{-- Avatar --}}
                <div
                    class="w-9 h-9 rounded-full bg-[#E0F2FE]
                            flex items-center justify-center
                            overflow-hidden">

                    @if ($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-[#14B8A6] font-bold text-sm">

                            {{ strtoupper(substr($user->username, 0, 1)) }}

                        </span>
                    @endif

                </div>


                {{-- Informasi user --}}
                <div class="hidden sm:block">

                    <p class="text-sm font-semibold text-gray-800">
                        {{ $user->nama_layanan ?? $user->username }}
                    </p>

                    <p class="text-xs text-gray-500">
                        Pengawas Laundry
                    </p>

                </div>

            </div>

        @endif

    </div>

</nav>
