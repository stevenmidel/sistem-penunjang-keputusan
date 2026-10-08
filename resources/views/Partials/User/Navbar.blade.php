<header x-data="{ open: false }" class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    {{-- BAR UTAMA --}}
    <div class="max-w-7xl mx-auto px-4 lg:px-6 py-4 flex items-center justify-between">

        {{-- LOGO --}}
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/LogoLaundry.svg') }}" alt="LaundryFinder Logo" class="h-10 w-10">
            <h1 class="text-2xl font-bold text-[#14B8A6] tracking-tight">
                LaundryFinder
            </h1>
        </div>

        {{-- MENU DESKTOP --}}
        <nav class="hidden lg:flex items-center gap-6">
            <a href="/" class="text-gray-800 hover:text-emerald-500 font-medium">Home</a>
            <a href="{{ route('user.features') }}" class="text-gray-800 hover:text-emerald-500 font-medium">Feature</a>
            <a href="{{ route('user.partner') }}" class="text-gray-800 hover:text-emerald-500 font-medium">Partner</a>
            <a href="{{ route('user.temukan') }}" class="text-gray-800 hover:text-emerald-500 font-medium">Temukan</a>
            <a href="{{ route('admin.laundry.login') }}" target="_blank"
                class="text-orange-500 font-medium hover:underline">
                Login
            </a>
        </nav>

        {{-- HAMBURGER MOBILE --}}
        <button @click="open = !open" class="lg:hidden text-gray-700 focus:outline-none" aria-label="Toggle Menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    {{-- MENU MOBILE --}}
    <div x-cloak x-show="open" x-transition.origin.top @click.outside="open = false"
        class="lg:hidden bg-white border-t border-gray-200">
        <nav class="flex flex-col px-6 py-4 space-y-4">
            <a href="/" class="text-gray-800 font-medium">Home</a>
            <a href="#features" class="text-gray-800 font-medium">Feature</a>
            <a href="#partner" class="text-gray-800 font-medium">Partner</a>
            <a href="{{ route('user.temukan') }}" class="text-gray-800 font-medium">Temukan</a>
            <a href="{{ route('admin.laundry.login') }}" target="_blank"
                class="text-orange-500 font-medium hover:underline">
                Login
            </a>
        </nav>
    </div>
</header>
