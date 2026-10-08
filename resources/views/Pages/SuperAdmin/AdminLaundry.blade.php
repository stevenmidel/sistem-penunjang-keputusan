@extends('layouts.SuperAdmin.app')

@section('title', 'Manajemen Admin Laundry')

@section('content')

    @include('partials.SuperAdmin.Navbar')
    @include('partials.SuperAdmin.Sidebar')

    <main class="pt-[72px] pl-64 min-h-screen bg-[#F9FAFB]">

        <div class="p-10 space-y-8">

            {{-- ================= HEADER ================= --}}
            <div class="flex items-center justify-between">

                <div>
                    <h1 class="text-4xl font-extrabold text-[#14B8A6]">
                        Admin Laundry
                    </h1>

                    <p class="text-gray-600 mt-1">
                        Kelola admin laundry dan pengawas masing-masing unit
                    </p>
                </div>

                {{-- Tombol Tambah Pengawas --}}
                <button type="button" onclick="openAddPengawasModal()"
                    class="px-5 py-3 rounded-xl bg-[#14B8A6] text-white font-semibold
                       hover:bg-[#0F766E] transition shadow">
                    + Tambah Pengawas
                </button>

            </div>


            {{-- ================= SUCCESS MESSAGE ================= --}}
            @if (session('success'))
                <div class="px-5 py-4 rounded-xl bg-green-100 text-green-700">
                    {{ session('success') }}
                </div>
            @endif


            {{-- ================= ERROR MESSAGE ================= --}}
            @if ($errors->any())

                <div class="px-5 py-4 rounded-xl bg-red-100 text-red-700">

                    <ul class="list-disc list-inside">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ================= TABLE ================= --}}
            <section class="bg-white rounded-2xl shadow-md overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-[#E0F2FE] text-[#0F766E]">

                            <tr>

                                <th class="px-6 py-4 text-left font-semibold">
                                    No
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Nama Admin
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Email
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Nama Laundry
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Pengawas
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y">

                            @forelse ($admins as $index => $admin)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- NO --}}
                                    <td class="px-6 py-4">
                                        {{ $index + 1 }}
                                    </td>


                                    {{-- NAMA ADMIN --}}
                                    <td class="px-6 py-4 font-semibold">
                                        {{ $admin->nama_layanan }}
                                    </td>


                                    {{-- EMAIL --}}
                                    <td class="px-6 py-4">
                                        {{ $admin->email }}
                                    </td>


                                    {{-- NAMA LAUNDRY --}}
                                    <td class="px-6 py-4">
                                        {{ $admin->nama_layanan }}
                                    </td>


                                    {{-- STATUS --}}
                                    <td class="px-6 py-4 text-center">

                                        @if ($admin->status)
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold
                                                   bg-green-100 text-green-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold
                                                   bg-red-100 text-red-700">
                                                Nonaktif
                                            </span>
                                        @endif

                                    </td>


                                    {{-- PENGAWAS --}}
                                    <td class="px-6 py-4">

                                        @if ($admin->pengawas)
                                            <div class="flex flex-col">

                                                <span class="font-semibold text-gray-800">
                                                    {{ $admin->pengawas->nama_layanan }}
                                                </span>

                                                <span class="text-xs text-gray-500">
                                                    {{ $admin->pengawas->username }}
                                                </span>

                                            </div>
                                        @else
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold
                                                   bg-yellow-100 text-yellow-700">
                                                Belum ditentukan
                                            </span>
                                        @endif

                                    </td>


                                    {{-- AKSI --}}
                                    <td class="px-6 py-4">

                                        <div class="flex flex-col gap-3">


                                            {{-- STATUS --}}
                                            <form method="POST"
                                                action="{{ route('superadmin.admin-laundry.update-status', $admin->id) }}"
                                                class="flex items-center gap-3">

                                                @csrf
                                                @method('PUT')


                                                <label class="flex items-center gap-1 cursor-pointer">

                                                    <input type="radio" name="status" value="aktif"
                                                        {{ $admin->status ? 'checked' : '' }} class="text-[#14B8A6]">

                                                    <span class="text-xs">
                                                        Aktif
                                                    </span>

                                                </label>


                                                <label class="flex items-center gap-1 cursor-pointer">

                                                    <input type="radio" name="status" value="nonaktif"
                                                        {{ !$admin->status ? 'checked' : '' }} class="text-red-600">

                                                    <span class="text-xs text-red-600">
                                                        Nonaktif
                                                    </span>

                                                </label>


                                                <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg
                                                       bg-[#14B8A6] text-white
                                                       text-xs font-semibold
                                                       hover:bg-[#0F766E] transition">

                                                    Simpan

                                                </button>

                                            </form>


                                            {{-- PENGAWAS --}}
                                            <div class="flex gap-2">


                                                {{-- TENTUKAN / GANTI --}}
                                                <button type="button"
                                                    onclick="openAssignPengawasModal(
                                                    {{ $admin->id }},
                                                    '{{ $admin->nama_layanan }}',
                                                    {{ $admin->pengawas_id ?? 'null' }}
                                                )"
                                                    class="px-3 py-2 rounded-lg
                                                       bg-blue-500 text-white
                                                       text-xs font-semibold
                                                       hover:bg-blue-600 transition">

                                                    {{ $admin->pengawas_id ? 'Ganti Pengawas' : 'Tentukan Pengawas' }}

                                                </button>


                                                {{-- LEPAS --}}
                                                @if ($admin->pengawas_id)
                                                    <form method="POST"
                                                        action="{{ route('superadmin.admin-laundry.remove-pengawas', $admin->id) }}">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                            onclick="return confirm(
                                                            'Lepaskan pengawas dari admin laundry ini?'
                                                        )"
                                                            class="px-3 py-2 rounded-lg
                                                               bg-red-500 text-white
                                                               text-xs font-semibold
                                                               hover:bg-red-600 transition">

                                                            Lepas

                                                        </button>

                                                    </form>
                                                @endif

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">

                                        Belum ada Admin Laundry.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </section>

        </div>

    </main>


    {{-- ========================================================= --}}
    {{-- MODAL TAMBAH PENGAWAS --}}
    {{-- ========================================================= --}}

    <div id="addPengawasModal" class="hidden fixed inset-0 z-50 bg-black/50
           items-center justify-center">

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">

            <div class="flex justify-between items-center mb-6">

                <h2 class="text-2xl font-bold text-[#14B8A6]">
                    Tambah Pengawas Laundry
                </h2>

                <button type="button" onclick="closeAddPengawasModal()" class="text-gray-500 hover:text-gray-800 text-2xl">

                    &times;

                </button>

            </div>


            <form method="POST" action="{{ route('superadmin.pengawas.store') }}" class="space-y-5">

                @csrf


                {{-- NAMA --}}
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Nama Pengawas
                    </label>

                    <input type="text" name="nama_layanan" required
                        class="w-full px-4 py-3 border rounded-xl
                           focus:ring-2 focus:ring-[#14B8A6]">

                </div>


                {{-- USERNAME --}}
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Username
                    </label>

                    <input type="text" name="username" required
                        class="w-full px-4 py-3 border rounded-xl
                           focus:ring-2 focus:ring-[#14B8A6]">

                </div>


                {{-- EMAIL --}}
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Email
                    </label>

                    <input type="email" name="email" required
                        class="w-full px-4 py-3 border rounded-xl
                           focus:ring-2 focus:ring-[#14B8A6]">

                </div>


                {{-- PASSWORD --}}
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Password
                    </label>

                    <input type="password" name="password" required class="w-full px-4 py-3 border rounded-xl">

                </div>


                {{-- KONFIRMASI PASSWORD --}}
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Konfirmasi Password
                    </label>

                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border rounded-xl">

                </div>


                <div class="flex justify-end gap-3 pt-3">

                    <button type="button" onclick="closeAddPengawasModal()"
                        class="px-5 py-2.5 rounded-xl
                           bg-gray-200 text-gray-700
                           font-semibold">

                        Batal

                    </button>


                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl
                           bg-[#14B8A6] text-white
                           font-semibold hover:bg-[#0F766E]">

                        Simpan Pengawas

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MODAL ASSIGN PENGAWAS --}}
    {{-- ========================================================= --}}

    <div id="assignPengawasModal" class="hidden fixed inset-0 z-50 bg-black/50
           items-center justify-center">

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">

            <div class="flex justify-between items-center mb-6">

                <div>

                    <h2 class="text-2xl font-bold text-[#14B8A6]">
                        Tentukan Pengawas
                    </h2>

                    <p id="assignAdminName" class="text-sm text-gray-500 mt-1">
                    </p>

                </div>

                <button type="button" onclick="closeAssignPengawasModal()"
                    class="text-gray-500 hover:text-gray-800 text-2xl">

                    &times;

                </button>

            </div>


            <form id="assignPengawasForm" method="POST" class="space-y-5">

                @csrf
                @method('PUT')


                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Pilih Pengawas
                    </label>

                    <select name="pengawas_id" id="pengawasSelect" required
                        class="w-full px-4 py-3 border rounded-xl
                           focus:ring-2 focus:ring-[#14B8A6]">

                        <option value="">
                            -- Pilih Pengawas --
                        </option>

                        @foreach ($pengawas as $item)
                            <option value="{{ $item->id }}">

                                {{ $item->nama_layanan }}
                                ({{ $item->username }})
                            </option>
                        @endforeach

                    </select>

                </div>


                <div class="flex justify-end gap-3">

                    <button type="button" onclick="closeAssignPengawasModal()"
                        class="px-5 py-2.5 rounded-xl bg-gray-200
                           text-gray-700 font-semibold">

                        Batal

                    </button>


                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl
                           bg-[#14B8A6] text-white
                           font-semibold hover:bg-[#0F766E]">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}

    <script>
        function openAddPengawasModal() {

            const modal = document.getElementById('addPengawasModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

        }


        function closeAddPengawasModal() {

            const modal = document.getElementById('addPengawasModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

        }


        function openAssignPengawasModal(
            adminId,
            adminName,
            pengawasId
        ) {

            const modal = document.getElementById('assignPengawasModal');

            const form = document.getElementById('assignPengawasForm');

            const name = document.getElementById('assignAdminName');

            const select = document.getElementById('pengawasSelect');


            name.textContent =
                'Admin Laundry: ' + adminName;


            form.action =
                "{{ url('/admin/admin-laundry') }}/" +
                adminId +
                "/pengawas";


            select.value =
                pengawasId ?? '';


            modal.classList.remove('hidden');
            modal.classList.add('flex');

        }


        function closeAssignPengawasModal() {

            const modal =
                document.getElementById('assignPengawasModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

        }


        // Tutup modal jika klik area luar
        window.addEventListener('click', function(event) {

            const addModal =
                document.getElementById('addPengawasModal');

            const assignModal =
                document.getElementById('assignPengawasModal');


            if (event.target === addModal) {

                closeAddPengawasModal();

            }


            if (event.target === assignModal) {

                closeAssignPengawasModal();

            }

        });
    </script>

@endsection
