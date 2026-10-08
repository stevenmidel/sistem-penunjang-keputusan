<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistem Laundry')</title>



    {{-- ===================== VITE ===================== --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ===================== GLOBAL STYLE ===================== --}}
    <style>
        /* ===== Alpine Cloak ===== */
        [x-cloak] {
            display: none !important;
        }

        /* ===== Page Transition ===== */
        body.page-enter {
            opacity: 0;
            transform: translateY(10px);
        }

        body.page-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        /* ===== Smooth Interaction ===== */
        html {
            scroll-behavior: smooth;
        }

        button,
        a,
        input,
        select,
        textarea {
            transition: all 0.2s ease;
        }

        /* ===== Page Loader ===== */
        #page-loader {
            transition: opacity 0.25s ease;
        }

        /* ===== Font Helpers ===== */
        .font-manrope {
            font-family: 'Manrope', sans-serif;
        }

        .font-inter {
            font-family: 'Inter', sans-serif;
        }
    </style>

    {{-- ===================== LEAFLET CSS ===================== --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />

    {{-- ===================== GOOGLE FONTS ===================== --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&family=Inter:wght@400;500;700&display=swap"
        rel="stylesheet">
</head>



<body class="bg-white text-[#111827] font-inter page-enter" x-data x-init="$nextTick(() => document.body.classList.add('page-enter-active'))">

    {{-- ===================== NAVBAR ===================== --}}
    @include('partials.user.navbar')

    {{-- ===================== MAIN CONTENT ===================== --}}
    <main class="min-h-[80vh]">
        @yield('content')
    </main>

    {{-- ===================== FOOTER ===================== --}}
    @unless (View::hasSection('no-footer'))
        @include('partials.user.footer')
    @endunless

    {{-- ===================== GLOBAL PAGE LOADER ===================== --}}
    <div id="page-loader"
        class="fixed inset-0 z-[9999] hidden
               flex items-center justify-center
               bg-white">
        <div class="flex flex-col items-center gap-4">
            <div
                class="w-10 h-10
                       border-2 border-gray-200
                       border-t-gray-800
                       rounded-full
                       animate-spin">
            </div>

            <span class="text-xs text-gray-500 tracking-widest uppercase">
                Loading
            </span>
        </div>
    </div>

    {{-- ===================== LEAFLET JS ===================== --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin="" defer></script>

    {{-- ===================== PAGE LOADER SCRIPT ===================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loader = document.getElementById('page-loader');
            if (!loader) return;

            const showLoader = () => {
                loader.classList.remove('hidden');
                loader.classList.add('flex');
            };

            // Saat klik link
            document.querySelectorAll('a[href]').forEach(link => {
                link.addEventListener('click', () => {
                    const href = link.getAttribute('href');

                    if (
                        !href ||
                        href.startsWith('#') ||
                        link.target === '_blank' ||
                        link.hasAttribute('download')
                    ) return;

                    showLoader();
                });
            });

            // Saat submit form
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', showLoader);
            });
        });
    </script>

    {{-- ===================== SCROLL TO TOP BUTTON ===================== --}}
    <button id="scrollTopBtn"
        class="fixed bottom-6 right-6 z-[9998]
           hidden items-center justify-center
           w-11 h-11 rounded-full
           bg-gray-900 text-white
           shadow-lg
           hover:bg-gray-800
           transition-all duration-300"
        aria-label="Scroll to top">
        ↑
    </button>

</body>

</html>
