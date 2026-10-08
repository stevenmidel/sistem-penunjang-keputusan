@extends('layouts.AdminLaundry.app')

@section('title', 'Verifikasi Password Transaksi')

@section('content')

    <section class="relative bg-white min-h-screen flex items-center">

        ```
        <div class="max-w-7xl mx-auto px-6 w-full flex flex-col lg:flex-row items-center lg:items-start gap-12">

            {{-- Kiri --}}
            <div class="lg:w-1/2 text-center lg:text-left">

                <h2 class="text-5xl lg:text-6xl font-extrabold text-[#14B8A6] mb-6 font-manrope leading-tight">
                    Verifikasi
                </h2>

                <p class="text-[#111827] text-lg lg:text-xl max-w-lg mb-8">
                    Masukkan password transaksi yang telah dikirim
                    ke email Anda.
                </p>

            </div>


            {{-- Kanan --}}
            <div class="lg:w-1/2 w-full max-w-md">

                <div class="bg-white shadow-lg rounded-2xl p-8">

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-6">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.verify.process') }}" class="space-y-6">

                        @csrf

                        <div>

                            <label for="otp" class="block text-sm font-semibold text-[#111827] mb-2">
                                Password Transaksi
                            </label>

                            <input type="text" name="otp" id="otp" maxlength="6" inputmode="numeric" required
                                autofocus
                                class="w-full px-4 py-3 rounded-lg border border-gray-300
                               text-center text-2xl tracking-[0.5em]
                               focus:outline-none focus:ring-2 focus:ring-[#14B8A6]
                               focus:border-transparent transition"
                                placeholder="000000">

                        </div>

                        <button type="submit"
                            class="w-full bg-[#0EA5E9] text-white py-3 rounded-lg
                           font-semibold text-lg hover:bg-[#FACC15]
                           hover:text-[#1F2937] transition duration-300 shadow-md">
                            Verifikasi
                        </button>

                    </form>

                </div>

            </div>

        </div>
        ```

    </section>

@endsection
