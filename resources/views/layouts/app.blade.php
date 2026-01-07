<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NARA SYSTEM') }}</title>

    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        body { background-color: #f3f4f6; color: #1f2937; font-family: 'Figtree', sans-serif; }
        
        /* Paksa Navbar & Sidebar jadi standar */
        nav { background-color: #ffffff !important; border-bottom: 1px solid #e5e7eb !important; }
        .sidebar-container { background-color: #ffffff !important; border-right: 1px solid #e5e7eb !important; }

        /* Widget Floating Default (Biru Standar) */
        #nara-activator {
            display: none; /* Default sembunyi, dimunculkan JS */
            position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px;
            background: #2563eb; border-radius: 50%; align-items: center; justify-content: center;
            color: white; font-size: 24px; cursor: pointer; z-index: 9999;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: 0.3s;
            border: 2px solid transparent;
        }
        #nara-activator:hover { transform: scale(1.1); }
        
        /* Widget Chatbox Default */
        #nara-interface {
            display: none; position: fixed; bottom: 100px; right: 30px; width: 380px; height: 500px;
            background: white; border: 1px solid #e5e7eb; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            border-radius: 12px; z-index: 9999; flex-direction: column; overflow: hidden;
        }
        #nara-interface.active { display: flex !important; }

        /* ==================================================
           2. NARA MODE (FUTURISTIC)
           Hanya aktif jika class 'nara-mode' ada di <html>
           ================================================== */
        
        /* Body & Background */
        .nara-mode body {
            background-color: #050f19 !important;
            background-image: 
                linear-gradient(rgba(0, 243, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 243, 255, 0.03) 1px, transparent 1px) !important;
            background-size: 30px 30px !important;
            font-family: 'Consolas', monospace !important;
            color: #e2e8f0 !important;
        }

        /* Scrollbar */
        .nara-mode ::-webkit-scrollbar { width: 8px; }
        .nara-mode ::-webkit-scrollbar-track { background: #050f19; }
        .nara-mode ::-webkit-scrollbar-thumb { background: #1e293b; border: 1px solid #00f3ff; border-radius: 4px; }

        /* Navbar Override */
        .nara-mode nav {
            background-color: rgba(5, 15, 25, 0.9) !important;
            border-bottom: 1px solid rgba(0, 243, 255, 0.3) !important;
            backdrop-filter: blur(10px);
        }
        .nara-mode nav * { color: #a5f3fc !important; }
        .nara-mode nav input { background: rgba(15, 23, 42, 0.6) !important; border-color: #0e7490 !important; }

        /* Sidebar Override */
        .nara-mode .sidebar-container {
            background-color: rgba(5, 15, 25, 0.9) !important;
            border-right: 1px solid rgba(0, 243, 255, 0.3) !important;
        }
        .nara-mode .sidebar-container * { color: #94a3b8; }
        .nara-mode .sidebar-container .active-link {
            background-color: rgba(0, 243, 255, 0.15) !important;
            border: 1px solid rgba(0, 243, 255, 0.4) !important;
            color: #00f3ff !important;
            box-shadow: 0 0 10px rgba(0, 243, 255, 0.1) !important;
        }
        .nara-mode .sidebar-container .active-link * { color: #00f3ff !important; }
        .nara-mode .sidebar-container a:hover { background-color: rgba(0, 243, 255, 0.1) !important; color: #fff !important; }

        /* Floating Widget Override (Jika muncul di mode Nara) */
        .nara-mode #nara-activator { background: rgba(0,0,0,0.8) !important; border: 2px solid #00f3ff !important; color: #00f3ff !important; box-shadow: 0 0 15px #00f3ff !important; }
        .nara-mode #nara-interface { background: rgba(5, 15, 25, 0.95) !important; border: 1px solid rgba(0, 243, 255, 0.5) !important; }
        .nara-mode .nara-header { background: rgba(0, 243, 255, 0.1) !important; border-bottom: 1px solid rgba(0, 243, 255, 0.5) !important; color: #00f3ff !important; }
        .nara-mode .ai-msg { background: rgba(0, 243, 255, 0.15) !important; color: #00f3ff !important; }
        .nara-mode .user-msg { background: rgba(255, 255, 255, 0.1) !important; color: #fff !important; }
        .nara-mode #nara-input { color: #00f3ff !important; border-color: #444 !important; background: transparent !important; }
        .nara-mode .btn-send { background: #00f3ff !important; color: #000 !important; }
    </style>
</head>

<body class="antialiased">
    
    <div class="flex min-h-screen relative">
        @include('layouts.sidebar')

        <div class="flex-1 ml-64 transition-all duration-300">
            @include('layouts.navigation')
            <main class="p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <div id="nara-activator" onclick="toggleNara()">
        <i class="fas fa-network-wired"></i>
    </div>

    <div id="nara-interface">
        <div class="nara-header" style="padding: 12px 15px; background: #f3f4f6; border-bottom: 1px solid #e5e7eb; color: #1f2937; font-weight: bold; display: flex; justify-content: space-between;">
            <span><i class="fas fa-bolt me-2 text-yellow-500"></i> N.A.R.A</span>
            <button onclick="toggleNara()" style="background:none; border:none; cursor:pointer;">✖</button>
        </div>
        
        <div class="nara-body" id="nara-log" style="flex-grow: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px;">
            <div class="ai-msg" style="align-self: flex-start; background: #eff6ff; color: #1e40af; padding: 8px 12px; border-radius: 0 10px 10px 10px; max-width: 90%; font-size: 13px; border-left: 3px solid #2563eb;">
                NARA Online. Menunggu perintah.
            </div>
        </div>
        
        <div class="nara-footer" style="padding: 10px; border-top: 1px solid #e5e7eb; display: flex; gap: 8px;">
            <input type="text" id="nara-input" placeholder="Ketik pesan..." onkeypress="handleEnter(event)" style="flex-grow: 1; border: 1px solid #ccc; padding: 8px; border-radius: 4px;">
            <button onclick="sendMessage()" class="btn-send" style="background: #2563eb; color: white; border: none; border-radius: 4px; width: 40px; cursor: pointer;">></button>
        </div>
    </div>

    <script>
        lucide.createIcons();
        
        // 1. LOGIC TAMPILAN (Dashboard vs Halaman Lain)
        document.addEventListener("DOMContentLoaded", () => {
            const widget = document.getElementById('nara-activator');
            const ui = document.getElementById('nara-interface');
            
            // A. JIKA DI DASHBOARD
            if (window.isDashboard) {
                // Sembunyikan widget pinggir total (karena ada console besar)
                widget.style.display = 'none';
                ui.classList.remove('active');

                // Cek status Online untuk mengaktifkan tema background
                if (localStorage.getItem('nara_status') === 'online') {
                    document.documentElement.classList.add('nara-mode');
                } else {
                    document.documentElement.classList.remove('nara-mode');
                }
            } 
            // B. JIKA DI HALAMAN LAIN (Data Barang, Laporan, dll)
            else {
                // MATIKAN tema futuristik (agar tetap putih bersih)
                document.documentElement.classList.remove('nara-mode');

                // MUNCULKAN widget pinggir (sebagai asisten mini)
                widget.style.display = 'flex';
            }
        });

        // 2. LOGIC INTERAKSI WIDGET
        function toggleNara() {
            const ui = document.getElementById('nara-interface');
            ui.classList.toggle('active');
            if(ui.classList.contains('active')) {
                setTimeout(() => document.getElementById('nara-input').focus(), 100);
            }
        }

        function handleEnter(e) { if (e.key === 'Enter') sendMessage(); }

        // 3. LOGIC CHAT WIDGET (PENTING: Agar bisa chat)
        async function sendMessage() {
            const input = document.getElementById('nara-input');
            const log = document.getElementById('nara-log');
            const message = input.value.trim();

            if (!message) return;

            // Tampilkan Pesan User
            log.innerHTML += `<div class="user-msg" style="align-self: flex-end; background: #e5e7eb; color: #1f2937; padding: 8px 12px; border-radius: 10px 10px 0 10px; max-width: 90%; font-size: 13px;">${message}</div>`;
            input.value = '';
            log.scrollTop = log.scrollHeight;

            try {
                // Fetch ke NARA Controller
                const response = await fetch("{{ route('nara.chat') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    body: JSON.stringify({ message: message })
                });
                const data = await response.json();

                // Tampilkan Balasan AI
                log.innerHTML += `<div class="ai-msg" style="align-self: flex-start; background: #eff6ff; color: #1e40af; padding: 8px 12px; border-radius: 0 10px 10px 10px; max-width: 90%; font-size: 13px; border-left: 3px solid #2563eb;">${data.reply}</div>`;
            } catch (error) {
                log.innerHTML += `<div style="color: red; font-size: 12px; text-align: center;">Gagal terhubung ke NARA.</div>`;
            }
            log.scrollTop = log.scrollHeight;
        }
    </script>
</body>
</html>