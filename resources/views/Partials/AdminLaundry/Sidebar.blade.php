<aside class="fixed top-[72px] left-0 w-64 h-[calc(100vh-72px)]
              bg-white border-r border-gray-200">

    <div class="p-6 space-y-1">

        {{-- ================= DASHBOARD ================= --}}
        <a href="{{ route('admin.laundry.dashboard') }}"
            class="flex items-center px-4 py-3 rounded-lg font-semibold
           {{ request()->routeIs('admin.laundry.dashboard')
               ? 'bg-[#E0F2FE] text-[#14B8A6] border-l-4 border-[#14B8A6]'
               : 'text-[#111827] hover:bg-[#E0F2FE]' }}">
            Dashboard
        </a>

        {{-- ================= UNIT USAHA ================= --}}
        <a href="{{ route('admin.laundry.unit.index') }}"
            class="flex items-center px-4 py-3 rounded-lg font-semibold
           {{ request()->routeIs('admin.laundry.unit.index*') || request()->routeIs('admin.laundry.unit.*')
               ? 'bg-[#E0F2FE] text-[#14B8A6] border-l-4 border-[#14B8A6]'
               : 'text-[#111827] hover:bg-[#E0F2FE]' }}">
            Unit Usaha
        </a>

        {{-- ================= LOGOUT ================= --}}
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="flex items-center px-4 py-3 rounded-lg
          text-red-600 hover:bg-red-50 transition">
            Logout
            <form id="logout-form" action="{{ route('admin.laundry.logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </a>

    </div>
</aside>
