<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nebeng - Superadmin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .nebeng-bg { background: #EEF5FF; }
        .nebeng-sidebar{ box-shadow: 8px 0 30px rgba(0,0,0,.08); }

        /* watermark / logo tengah */
        .nebeng-watermark{
            position:absolute; inset:0;
            display:flex; align-items:center; justify-content:center;
            pointer-events:none;
            opacity:.22;
            filter: drop-shadow(0 6px 10px rgba(0,0,0,.15));
        }
        .nebeng-watermark img{
            width: 560px;
            max-width: 72vw;
            height:auto;
        }
    </style>
</head>

<body class="min-h-screen nebeng-bg overflow-x-hidden">
    <div class="min-h-screen flex min-w-0 overflow-x-hidden">

        {{-- SIDEBAR --}}
        <div class="min-h-screen shrink-0">
            @include('superadmin.layouts.sidebar')
        </div>

        {{-- MAIN --}}
        <div class="flex-1 min-h-screen relative min-w-0 overflow-x-hidden">

            {{-- TOPBAR --}}
            <div class="relative z-50">
                @include('superadmin.layouts.topbar')
            </div>

            {{-- MAIN CONTENT --}}
            <main class="relative z-0 w-full min-w-0 px-8 pb-8 pt-5 min-h-[calc(100vh-92px)] overflow-x-hidden">
                @yield('content')
            </main>

            {{-- MODAL LAYER: paling atas --}}
            <div id="modal-root" class="fixed inset-0 z-[2147483647] pointer-events-none">
                <div class="pointer-events-auto">
                    @stack('modals')
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    {{-- Script per halaman (kalau ada @push("scripts")) --}}
    @stack('scripts')

    <script>
        /**
         * Default block modal handler (opsional).
         * Jika halaman SUDAH punya openBlockConfirm() sendiri (seperti verifikasi_customer),
         * maka fungsi di bawah TIDAK akan menimpa.
         */
        if (typeof window.openBlockConfirm === 'undefined') {
            window.openBlockConfirm = function (url) {
                const modal = document.getElementById('blockModal');
                const form  = document.getElementById('blockForm');

                if (!modal || !form) {
                    console.error('blockModal / blockForm tidak ditemukan. Pastikan sudah @push("modals") di page.');
                    return;
                }

                form.action = url;
                modal.classList.remove('hidden');
            }
        }

        if (typeof window.closeBlockConfirm === 'undefined') {
            window.closeBlockConfirm = function () {
                const modal = document.getElementById('blockModal');
                if (modal) modal.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
