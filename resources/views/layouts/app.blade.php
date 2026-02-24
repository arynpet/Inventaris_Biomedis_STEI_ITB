<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NARA SYSTEM') }}</title>

    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        window.isDashboard = {{ request()->routeIs('dashboard') ? 'true' : 'false' }};
    </script>

    <style>
        /* ==================================================
           1. DEFAULT STYLE (CLEAN / WHITE MODE)
           Dipaksa agar tidak ada sisa garis biru saat NARA mati
           ================================================== */
        body {
            background-color: #f3f4f6;
            color: #1f2937;
            font-family: 'Figtree', sans-serif;
        }

        /* Paksa Navbar & Sidebar jadi standar */
        nav {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .sidebar-container {
            background-color: #ffffff !important;
            border-right: 1px solid #e5e7eb !important;
        }
    </style>
</head>

<body class="antialiased" x-data="{ sidebarOpen: true }">

    @if(session()->has('impersonate_original_id'))
        <div
            class="bg-red-600 text-white text-center py-2 px-4 shadow-md relative z-[100] flex justify-center items-center gap-4 animate-pulse">
            <div class="flex items-center gap-2 font-bold text-sm">
                <i class="fas fa-user-secret text-lg"></i>
                <span>MODE PENYAMARAN AKTIF: Anda sedang login sebagai {{ auth()->user()->name }}</span>
            </div>
            <a href="{{ route('impersonate.stop') }}"
                class="bg-white text-red-600 px-3 py-1 rounded-full text-xs font-bold hover:bg-gray-100 transition shadow-sm uppercase border border-red-800">
                KEMBALI KE ADMIN
            </a>
        </div>
    @endif

    <div class="flex min-h-screen relative">
        @include('layouts.sidebar')

        <div class="flex-1 transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-20'">
            @include('layouts.navigation')
            <main class="p-8">
                {{ $slot }}

                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                        class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-xl shadow-xl flex items-center gap-3 border-2 border-white/20">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                @endif
            </main>
        </div>
    </div>



    <script>
        lucide.createIcons();



        // ==========================================
        // 4. SCREEN TIME TRACKER (GAMIFICATION)
        // ==========================================
        // ==========================================
        // 4. SCREEN TIME TRACKER (GAMIFICATION)
        // ==========================================
        document.addEventListener("DOMContentLoaded", () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (csrfToken) {
                setInterval(() => {
                    // 1. Check if tab is focused (Not idle/minimized)
                    if (!document.hidden) {
                        fetch("{{ route('user.heartbeat') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({})
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'pumped') {
                                    console.log(`Heartbeat [OK]. Total Seconds: ${data.val}`);
                                } else if (data.status === 'guest') {
                                    console.log("Heartbeat: Guest (Not logged in)");
                                } else {
                                    console.error("Heartbeat Error:", data);
                                }
                            })
                            .catch(err => console.error("Heartbeat Network/JSON Error:", err));
                    } else {
                        console.log("Heartbeat skipped (Idle/Hidden)");
                    }
                }, 10000); // 10s
            }
        });
</body >

</html >