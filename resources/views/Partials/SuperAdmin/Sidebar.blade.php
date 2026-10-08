<aside class="fixed top-[72px] left-0 w-64 h-[calc(100vh-72px)]
              bg-white border-r border-gray-200">

    <div class="p-6 space-y-1">

        {{-- ================= DASHBOARD ================= --}}
        <a href="{{ route('superadmin.dashboard') }}"
            class="flex items-center px-4 py-3 rounded-lg font-semibold
           {{ request()->routeIs('superadmin.dashboard')
               ? 'bg-[#E0F2FE] text-[#14B8A6] border-l-4 border-[#14B8A6]'
               : 'text-[#111827] hover:bg-[#E0F2FE]' }}">
            Dashboard
        </a>

        {{-- ================= DATA LAUNDRY ================= --}}
        <a href="{{ route('superadmin.laundry') }}"
            class="flex items-center px-4 py-3 rounded-lg font-semibold
           {{ request()->routeIs('superadmin.laundry*')
               ? 'bg-[#E0F2FE] text-[#14B8A6] border-l-4 border-[#14B8A6]'
               : 'text-[#111827] hover:bg-[#E0F2FE]' }}">
            Data Laundry
        </a>

        {{-- ================= ADMIN LAUNDRY ================= --}}
        <a href="{{ route('superadmin.admin-laundry.index') }}"
            class="flex items-center px-4 py-3 rounded-lg font-semibold
       {{ request()->routeIs('superadmin.admin-laundry*')
           ? 'bg-[#E0F2FE] text-[#14B8A6] border-l-4 border-[#14B8A6]'
           : 'text-[#111827] hover:bg-[#E0F2FE]' }}">
            Admin Laundry
        </a>

        {{-- ================= LOGOUT ================= --}}
        <form action="{{ route('superadmin.logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full text-left flex items-center px-4 py-3 rounded-lg
               text-red-600 hover:bg-red-50 transition">
                Logout
            </button>
        </form>

    </div>
</aside>
