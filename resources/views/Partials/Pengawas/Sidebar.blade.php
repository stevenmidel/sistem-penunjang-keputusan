<aside class="fixed top-[72px] left-0 bottom-0
           w-64 bg-white border-r border-gray-200
           z-40">


    <div class="p-6 space-y-1">

        {{-- ================= DASHBOARD ================= --}}

        <a href="{{ route('pengawas.dashboard') }}"
            class="flex items-center px-4 py-3 rounded-lg font-semibold transition
        {{ request()->routeIs('pengawas.dashboard')
            ? 'bg-[#E0F2FE] text-[#14B8A6] border-l-4 border-[#14B8A6]'
            : 'text-[#111827] hover:bg-[#E0F2FE]' }}">

            Dashboard

        </a>


        {{-- ================= AKTIVITAS ================= --}}

        <a href="{{ route('pengawas.activity-log') }}"
            class="flex items-center px-4 py-3 rounded-lg font-semibold transition
        {{ request()->routeIs('pengawas.activity-log*')
            ? 'bg-[#E0F2FE] text-[#14B8A6] border-l-4 border-[#14B8A6]'
            : 'text-[#111827] hover:bg-[#E0F2FE]' }}">

            Aktivitas Admin

        </a>


        {{-- ================= PEMBATAS ================= --}}

        <div class="pt-4 pb-2 px-4">

            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                Akun
            </p>

        </div>


        {{-- ================= LOGOUT ================= --}}

        <form action="{{ route('pengawas.logout') }}" method="POST">

            @csrf

            <button type="submit"
                class="w-full text-left flex items-center
                   px-4 py-3 rounded-lg
                   text-red-600 hover:bg-red-50
                   transition">

                Logout

            </button>

        </form>

    </div>


</aside>
