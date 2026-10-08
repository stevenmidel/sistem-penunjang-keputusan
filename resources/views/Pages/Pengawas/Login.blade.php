@extends('layouts.Pengawas.app')

@section('title', 'Login Pengawas Laundry')

@section('content')

    <section class="relative bg-white min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto px-6 flex flex-col lg:flex-row items-center lg:items-start gap-12">

            ```
            {{-- ================= KIRI: TEKS ================= --}}
            <div class="lg:w-1/2 text-center lg:text-left">

                <h2
                    class="text-5xl lg:text-6xl font-extrabold text-[#14B8A6]
                   mb-6 font-manrope leading-tight">
                    Login Pengawas Laundry
                </h2>

                <p class="text-[#111827] text-lg lg:text-xl max-w-lg mb-8">
                    Halaman login khusus Pengawas Laundry untuk memantau
                    aktivitas dan mengawasi pengelolaan laundry yang menjadi
                    tanggung jawabnya.
                </p>

            </div>


            {{-- ================= KANAN: FORM ================= --}}
            <div class="lg:w-1/2 w-full max-w-md">

                <div class="bg-white shadow-lg rounded-2xl p-8">

                    {{-- ERROR GLOBAL --}}
                    @if ($errors->has('login'))
                        <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-600 text-sm font-semibold">
                            {{ $errors->first('login') }}
                        </div>
                    @endif


                    {{-- FORM LOGIN --}}
                    <form method="POST" action="{{ route('pengawas.login.process') }}" class="space-y-6">

                        @csrf


                        {{-- USERNAME --}}
                        <div>

                            <label for="username" class="block text-sm font-semibold text-[#111827] mb-2">
                                Username
                            </label>

                            <input type="text" name="username" id="username" value="{{ old('username') }}" required
                                autofocus
                                class="w-full px-4 py-3 rounded-lg border border-gray-300
                               focus:outline-none focus:ring-2 focus:ring-[#14B8A6]
                               focus:border-transparent"
                                placeholder="Masukkan username">

                        </div>


                        {{-- PASSWORD --}}
                        <div>

                            <label for="password" class="block text-sm font-semibold text-[#111827] mb-2">
                                Password
                            </label>

                            <input type="password" name="password" id="password" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300
                               focus:outline-none focus:ring-2 focus:ring-[#14B8A6]
                               focus:border-transparent"
                                placeholder="Masukkan password">

                        </div>


                        {{-- BUTTON LOGIN --}}
                        <button type="submit"
                            class="w-full bg-[#0EA5E9] text-white py-3 rounded-lg
                           font-semibold text-lg hover:bg-[#FACC15]
                           hover:text-[#1F2937] transition duration-300 shadow-md">
                            Login
                        </button>

                    </form>

                </div>

            </div>

        </div>
        ```

    </section>

@endsection
