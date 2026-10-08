@extends('layouts.SuperAdmin.app')

@section('title', 'Dashboard Super Admin')

@section('content')

    @include('partials.SuperAdmin.Navbar')
    @include('partials.SuperAdmin.Sidebar')
    @include('Pages.SuperAdmin.ProfileUser')

    <main class="pt-[72px] pl-64 min-h-screen bg-gray-50">
        <div class="p-10 space-y-10">

            {{-- HEADER --}}
            <div>
                <h1 class="text-4xl font-extrabold text-[#14B8A6] mb-2">
                    Dashboard Super Admin
                </h1>
                <p class="text-gray-600 max-w-xl">
                    Ringkasan kondisi sistem LaundryFinder secara keseluruhan.
                </p>
            </div>

            {{-- KPI CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500">Admin Laundry</p>
                    <h2 class="text-4xl font-bold text-gray-900 mt-2">
                        {{ $totalAdminLaundry ?? 12 }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500">Usaha Laundry</p>
                    <h2 class="text-4xl font-bold text-gray-900 mt-2">
                        {{ $totalUsaha ?? 48 }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500">Pengguna Aktif</p>
                    <h2 class="text-4xl font-bold text-emerald-600 mt-2">
                        {{ $userActive ?? 1284 }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500">Pengguna Tidak Aktif</p>
                    <h2 class="text-4xl font-bold text-red-500 mt-2">
                        {{ $userInactive ?? 214 }}
                    </h2>
                </div>

            </div>

            {{-- CHART SECTION --}}
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Statistik Sistem</h3>
                        <p class="text-sm text-gray-400 mt-1">Pertumbuhan Data Tahunan Oleh Sistem</p>
                    </div>
                </div>
                <div style="height: 340px;">
                    <canvas id="systemOverviewChart"></canvas>
                </div>
            </div>

        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('systemOverviewChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [
                        'Admin Laundry',
                        'Usaha Laundry',
                        'Pengguna Aktif',
                        'Pengguna Tidak Aktif'
                    ],
                    datasets: [{
                        label: 'Jumlah',
                        data: [
                            {{ $totalAdminLaundry ?? 0 }},
                            {{ $totalUsaha ?? 0 }},
                            {{ $userActive ?? 0 }},
                            {{ $userInactive ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgba(99,102,241,0.8)',
                            'rgba(249,115,22,0.8)',
                            'rgba(20,184,166,0.8)',
                            'rgba(239,68,68,0.8)',
                        ],
                        borderColor: [
                            '#6366F1',
                            '#F97316',
                            '#14B8A6',
                            '#EF4444',
                        ],
                        borderWidth: 2,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: ctx => ' ' + ctx.parsed.y.toLocaleString('id-ID') + ' data'
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
    </script>

@endsection
