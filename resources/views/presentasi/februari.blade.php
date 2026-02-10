<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Report - Feb 2026</title>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS (CDN for standalone presentation safety) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=JetBrains+Mono:wght@400;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        pre,
        code {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Custom Scrollbar for Code Blocks */
        ::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1e1e1e;
        }

        ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        /* Neon Glow Effects */
        .neon-text {
            text-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }

        .neon-border {
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 overflow-hidden" x-data="{ 
          slide: 1, 
          maxSlide: 7,
          next() { if(this.slide < this.maxSlide) this.slide++ },
          prev() { if(this.slide > 1) this.slide-- }
      }" @keydown.right.window="next()" @keydown.left.window="prev()" @keydown.space.window="next()">

    <!-- Progress Bar -->
    <div class="fixed top-0 left-0 h-1 bg-gray-800 w-full z-50">
        <div class="h-full bg-blue-500 transition-all duration-500" :style="`width: ${(slide / maxSlide) * 100}%`">
        </div>
    </div>

    <!-- Navigation Controls (Floating) -->
    <div class="fixed bottom-8 right-8 flex gap-4 z-50 opacity-50 hover:opacity-100 transition-opacity">
        <button @click="prev()"
            class="p-3 bg-gray-800 rounded-full hover:bg-gray-700 disabled:opacity-30 border border-gray-600"
            :disabled="slide === 1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <span class="self-center font-mono text-gray-400" x-text="slide + ' / ' + maxSlide"></span>
        <button @click="next()"
            class="p-3 bg-blue-600 rounded-full hover:bg-blue-500 disabled:opacity-30 shadow-lg shadow-blue-500/30"
            :disabled="slide === maxSlide">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>

    <!-- Main Content Area -->
    <main class="h-screen w-full flex items-center justify-center p-8 relative">

        <!-- SLIDE 1: JUDUL -->
        <div x-show="slide === 1" x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="text-center max-w-4xl">
            <div
                class="mb-4 inline-block px-4 py-1 rounded-full bg-blue-900/30 border border-blue-500/50 text-blue-400 text-sm font-mono tracking-wider">
                FEBRUARY 2026 UPDATE
            </div>
            <h1
                class="text-7xl font-extrabold mb-6 tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 neon-text">
                Inventaris Biomedis
            </h1>
            <p class="text-2xl text-gray-400 font-light mb-12">
                Ekosistem QR, Integrasi Mobile & Gamifikasi
            </p>
            <div class="mt-8 pt-8 border-t border-gray-800 flex justify-center gap-12 text-sm text-gray-500 font-mono">
                <div>
                    <span class="block text-gray-300">Raden Satya Pangestu</span>
                    <span class="text-xs">SYSTEM ARCHITECT</span>
                </div>
                <div>
                    <span class="block text-gray-300">Laravel 10 + Flutter</span>
                    <span class="text-xs">TECH STACK</span>
                </div>
            </div>
        </div>

        <!-- SLIDE 2: EKOSISTEM QR CODE -->
        <div x-show="slide === 2" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-20" x-transition:enter-end="opacity-100 translate-x-0"
            class="w-full max-w-6xl grid grid-cols-2 gap-12 items-center">

                <div>
                    <h2 class="text-blue-400 font-mono text-sm mb-2">CORE FEATURE</h2>
                    <h3 class="text-4xl font-bold mb-6">Generated QR Ecosystem</h3>
                    <p class="text-gray-400 leading-relaxed mb-6">
                        Mekanisme pembuatan QR Code unik untuk setiap aset menggunakan library <code>bacon-qr-code</code> dan wrapper <code>simplesoftwareio</code>.
                    </p>
                    
                    <!-- Visual Illustration -->
                    <div class="flex items-center gap-4 mt-8">
                        <div class="relative w-32 h-32 bg-white p-2 rounded-lg shadow-[0_0_20px_rgba(59,130,246,0.5)] flex items-center justify-center">
                            <!-- Simulated QR Pattern -->
                            <div class="w-full h-full bg-gray-900 grid grid-cols-6 grid-rows-6 gap-0.5">
                                <div class="col-span-2 row-span-2 bg-black"></div>
                                <div class="col-start-5 col-span-2 row-span-2 bg-black"></div>
                                <div class="col-start-1 col-span-2 row-start-5 row-span-2 bg-black"></div>
                                <div class="col-start-3 row-start-3 bg-black"></div>
                                <div class="col-start-4 row-start-4 bg-black"></div>
                                <div class="col-start-3 row-start-5 bg-black"></div>
                            </div>
                            <!-- Overlay badge -->
                            <div class="absolute -bottom-2 -right-2 bg-green-500 text-black font-bold px-2 py-0.5 rounded text-[10px]">SVG</div>
                        </div>
                        <div class="h-1 flex-1 bg-gradient-to-r from-blue-500 to-green-500 rounded"></div>
                        <div class="text-gray-300 text-sm font-mono text-right">
                            <div class="text-xs text-gray-500">FORMAT</div>
                            Vector Scalable
                        </div>
                    </div>

                    <!-- Demo Button -->
                    <div class="mt-8">
                        <a href="{{ route('items.index') }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600/20 hover:bg-blue-600 border border-blue-500 text-blue-300 hover:text-white rounded-full transition-all text-sm font-bold group">
                            <span>Lihat Daftar Aset & QR</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                </div>

            <div
                class="bg-[#1e1e1e] rounded-xl border border-gray-700 shadow-2xl p-6 neon-border h-full flex flex-col justify-center">
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-blue-900/30 rounded-lg text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-200">Backend Logic</h4>
                            <p class="text-gray-400 text-sm mt-1">Menggunakan <code>bacon-qr-code</code> untuk generate
                                vektor SVG yang ringan dan scalable secara otomatis saat item dibuat.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-green-900/30 rounded-lg text-green-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-200">Storage & Identifiers</h4>
                            <p class="text-gray-400 text-sm mt-1">QR Code disimpan di <code>public/storage</code> dengan
                                penamaan unik berbasis timestamp mikrodetik untuk mencegah konflik cache.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLIDE 3: MANAJEMEN GAMBAR CERDAS -->
        <div x-show="slide === 3" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-20" x-transition:enter-end="opacity-100 translate-x-0"
            class="w-full max-w-6xl">

            <div class="flex items-end justify-between mb-8 border-b border-gray-800 pb-4">
                <div>
                    <h2 class="text-green-400 font-mono text-sm mb-2">STORAGE OPTIMIZATION</h2>
                    <h3 class="text-4xl font-bold">Smart Image Management</h3>
                </div>
                <div class="flex items-center gap-4 text-right">
                    <a href="{{ route('items.create') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-1.5 bg-gray-800 hover:bg-gray-700 border border-gray-600 text-gray-300 rounded text-xs font-mono transition-all group">
                        <span>Upload Demo</span>
                        <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                    <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded text-sm font-mono">Intervention Image V2</span>
                </div>
            </div>

            <!-- Visual Pipeline Illustration -->
            <div class="flex items-center justify-center gap-4 mb-8 text-center">
                <div class="bg-gray-800 p-4 rounded-lg border border-red-500/30">
                    <div class="w-16 h-16 bg-gray-700 mx-auto mb-2 flex items-center justify-center text-xs text-gray-400">RAW</div>
                    <div class="text-xs text-red-400 font-bold">LARGE FILE</div>
                    <div class="text-[10px] text-gray-500">~5MB</div>
                </div>

                <div class="text-gray-500">
                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </div>

                 <div class="bg-purple-900/20 p-4 rounded-lg border border-purple-500/50">
                    <div class="w-16 h-16 bg-purple-900/40 mx-auto mb-2 flex items-center justify-center text-xl">⚙️</div>
                    <div class="text-xs text-purple-400 font-bold">PROCESS</div>
                    <div class="text-[10px] text-gray-500">Crop & Compress</div>
                </div>

                <div class="text-gray-500">
                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </div>

                <div class="bg-gray-800 p-4 rounded-lg border border-green-500/30">
                    <div class="w-16 h-16 bg-gray-700 mx-auto mb-2 flex items-center justify-center text-xs text-gray-400 bg-cover bg-center" style="background-image: url('https://placehold.co/100x100/10b981/ffffff?text=IMG');"></div>
                    <div class="text-xs text-green-400 font-bold">OPTIMIZED</div>
                    <div class="text-[10px] text-gray-500">~150KB</div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <!-- Backend Processor Node -->
                <div
                    class="bg-[#1e1e1e] rounded-xl border border-gray-700 p-6 relative group hover:border-purple-500 transition-colors">
                    <div
                        class="absolute -top-3 left-6 px-3 py-1 bg-gray-800 border border-purple-500 text-purple-400 text-xs rounded-full">
                        Server Side</div>

                    <h4 class="text-xl font-bold mb-4 text-purple-300">Intervention Image V2</h4>
                    <ul class="space-y-4 text-gray-400 text-sm">
                        <li class="flex items-start gap-3">
                            <span
                                class="w-6 h-6 rounded bg-purple-900/50 flex items-center justify-center text-purple-400 flex-shrink-0">1</span>
                            <span><strong>Smart Resize:</strong> Memotong gambar secara cerdas (center crop) ke ukuran
                                standar 500x500px.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span
                                class="w-6 h-6 rounded bg-purple-900/50 flex items-center justify-center text-purple-400 flex-shrink-0">2</span>
                            <span><strong>Compression:</strong> Mengurangi kualitas ke 80% JPG untuk menghemat disk
                                space tanpa merusak visual.</span>
                        </li>
                    </ul>
                </div>

                <!-- Frontend Delivery Node -->
                <div
                    class="bg-[#1e1e1e] rounded-xl border border-gray-700 p-6 relative group hover:border-orange-500 transition-colors">
                    <div
                        class="absolute -top-3 left-6 px-3 py-1 bg-gray-800 border border-orange-500 text-orange-400 text-xs rounded-full">
                        Client Side</div>

                    <h4 class="text-xl font-bold mb-4 text-orange-300">Optimized Delivery</h4>
                    <ul class="space-y-4 text-gray-400 text-sm">
                        <li class="flex items-start gap-3">
                            <span
                                class="w-6 h-6 rounded bg-orange-900/50 flex items-center justify-center text-orange-400 flex-shrink-0">1</span>
                            <span><strong>Storage Link:</strong> Mengakses file langsung melalui symlink web server
                                untuk performa statis maksimal.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span
                                class="w-6 h-6 rounded bg-orange-900/50 flex items-center justify-center text-orange-400 flex-shrink-0">2</span>
                            <span><strong>Lazy Loading:</strong> Browser hanya memuat gambar saat berada di viewport
                                user untuk hemat bandwidth.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- SLIDE 4: BIOMED SCANNER FLUTTER -->
        <div x-show="slide === 4" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-20" x-transition:enter-end="opacity-100 translate-y-0"
            class="w-full max-w-5xl text-center">

            <div class="mb-8 flex flex-col items-center">
                <!-- Phone Mockup Frame -->
                <div class="w-32 h-64 border-4 border-gray-700 rounded-2xl bg-gray-800 relative shadow-2xl overflow-hidden mb-4 transform hover:scale-105 transition-transform duration-500">
                    <!-- Top Notch -->
                    <div class="absolute top-0 inset-x-0 h-4 bg-gray-900 z-20 rounded-b-lg w-16 mx-auto"></div>
                    
                    <!-- Screen Content -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900">
                        <div class="w-20 h-20 border-2 border-blue-500 rounded relative overflow-hidden">
                             <div class="absolute top-0 left-0 w-full h-0.5 bg-green-400 shadow-[0_0_10px_#4ade80]" style="animation: scan 1.5s infinite linear;"></div>
                             <!-- QR Mock inside -->
                             <div class="w-full h-full opacity-20 bg-white" style="background-image: radial-gradient(black 2px, transparent 0); background-size: 4px 4px;"></div>
                        </div>
                        <p class="mt-4 text-[8px] font-mono text-green-400">Scanning...</p>
                    </div>
                </div>

                <h2 class="text-3xl font-bold">Aplikasi Android "Biomed Scanner"</h2>
                <p class="text-gray-400 mt-2">Flutter Dart Implementation</p>
                
                <style>
                    @keyframes scan {
                        0% { top: 0; opacity: 0; }
                        10% { opacity: 1; }
                        90% { opacity: 1; }
                        100% { top: 100%; opacity: 0; }
                    }
                </style>

                <!-- Demo Button -->
                 <div class="mt-4">
                    <a href="{{ route('remote.token') }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2 bg-gray-800 hover:bg-gray-700 border border-green-500/50 text-green-400 hover:text-green-300 rounded-full transition-all text-sm font-bold group">
                        <span>Coba Remote Upload (QR)</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M16 3h5v5M4 3h5v5M4 16h5v5M16 16h5v5"></path></svg>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 text-left">
                <!-- Feature 1 -->
                <div class="bg-[#1e1e1e] p-6 rounded-xl border border-gray-700 hover:scale-105 transition-transform">
                    <div
                        class="w-12 h-12 bg-cyan-900/30 rounded-lg flex items-center justify-center text-cyan-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-200 mb-2">Real-time Scanner</h4>
                    <p class="text-gray-400 text-sm">Menggunakan kamera HP untuk memindai QR code aset secara instan dan
                        mengirimkan data string ke server.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-[#1e1e1e] p-6 rounded-xl border border-gray-700 hover:scale-105 transition-transform">
                    <div
                        class="w-12 h-12 bg-blue-900/30 rounded-lg flex items-center justify-center text-blue-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-200 mb-2">REST API Integration</h4>
                    <p class="text-gray-400 text-sm">Aplikasi berkomunikasi dengan Laravel melalui secure API endpoint
                        (Sanctum Token Auth).</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-[#1e1e1e] p-6 rounded-xl border border-gray-700 hover:scale-105 transition-transform">
                    <div
                        class="w-12 h-12 bg-purple-900/30 rounded-lg flex items-center justify-center text-purple-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-200 mb-2">Admin Mobility</h4>
                    <p class="text-gray-400 text-sm">Memungkinkan admin melakukan audit inventaris dan peminjaman
                        langsung di lapangan tanpa laptop.</p>
                </div>
            </div>
        </div>

        <!-- SLIDE 5: INFRASTRUCTURE (Cloudflare) -->
        <div x-show="slide === 5" x-transition:enter="transition ease-out duration-300"
            class="w-full max-w-6xl grid grid-cols-5 gap-8 items-center">

            <div class="col-span-2">
                <h3 class="text-4xl font-bold mb-4 text-orange-400">Security & Access</h3>
                <h4 class="text-xl text-white mb-6">Cloudflare Tunnel + HTTPS Enforcement</h4>
                <p class="text-gray-400 mb-6">
                    Membuka akses localhost ke publik menggunakan Cloudflare Tunnel, namun seringkali asset loading
                    gagal karena mixed content (HTTP vs HTTPS).
                </p>
                <div class="bg-blue-900/20 border border-blue-500/30 p-4 rounded-lg mt-6">
                    <div class="font-bold text-blue-400 text-sm mb-4">FLOW DIAGRAM:</div>
                    
                    <!-- Diagram Visual -->
                    <div class="flex items-center justify-between text-center text-xs font-mono">
                        <div class="flex flex-col items-center">
                            <div class="text-2xl mb-1">🌍</div>
                            <span class="text-gray-400">User</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-gray-600 mx-2 relative">
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gray-900 px-1 text-green-500">HTTPS</div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="text-2xl mb-1 text-orange-400">☁️</div>
                            <span class="text-orange-400 font-bold">Cloudflare</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-gray-600 mx-2 relative">
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gray-900 px-1 text-blue-500">Tunnel</div>
                        </div>
                         <div class="flex flex-col items-center">
                            <div class="text-2xl mb-1">💻</div>
                            <span class="text-gray-400">Localhost</span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="col-span-3 bg-[#1e1e1e] rounded-xl border border-gray-700 p-8 shadow-orange-500/10 shadow-lg flex flex-col justify-center">
                <h4 class="text-xl font-bold text-white mb-6 border-b border-gray-700 pb-4">Implementation Strategy</h4>

                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div
                            class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center font-mono text-gray-400 border border-gray-600">
                            1</div>
                        <div>
                            <h5 class="text-yellow-400 font-bold">HTTPS Enforcement</h5>
                            <p class="text-gray-400 text-sm mt-1">Mendeteksi jika environment adalah Production atau
                                melalui Tunnel, lalu memaksa seluruh URL schema menjadi <code>https://</code> untuk
                                menghindari "Mixed Content Error".</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div
                            class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center font-mono text-gray-400 border border-gray-600">
                            2</div>
                        <div>
                            <h5 class="text-yellow-400 font-bold">Root URL Fix</h5>
                            <p class="text-gray-400 text-sm mt-1">Memaksa generator URL Laravel untuk menggunakan domain
                                publik Cloudflare, bukan <code>localhost</code>, agar link asset dan pagination
                                berfungsi normal bagi pengguna luar.</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-700">
                        <a href="{{ url('/') }}" target="_blank" class="block w-full text-center px-4 py-2 bg-orange-900/30 hover:bg-orange-900/50 border border-orange-500/30 text-orange-300 rounded transition-colors text-sm font-mono">
                            Test Akses Publik (Cloudflare)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLIDE 6: GAMIFIKASI -->
        <div x-show="slide === 6" x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            class="w-full max-w-6xl">

            <div class="text-center mb-10">
                <span class="text-purple-400 font-bold tracking-widest text-sm uppercase">Gamification Engine</span>
                <h2
                    class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-600">
                    RPG Style Admin Tasks
                </h2>
            </div>

            <div class="grid grid-cols-3 gap-6">
                <!-- Card 1: Logic -->
                <div
                    class="col-span-2 bg-[#1e1e1e] rounded-xl border border-gray-700 p-8 relative flex flex-col justify-center overflow-hidden">
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-purple-600/10 rounded-full blur-3xl"></div>

                    <h3 class="text-2xl font-bold text-gray-200 mb-6 z-10">Logic & Rules</h3>

                    <ul class="space-y-4 z-10">
                        <li class="flex items-center gap-4 bg-gray-800/50 p-3 rounded-lg border border-gray-700">
                            <span class="text-2xl">🌱</span>
                            <div>
                                <span class="block text-white font-bold">Creation Reward (+100 XP)</span>
                                <span class="text-gray-400 text-sm">Menambahkan item atau data baru memberikan XP
                                    terbesar.</span>
                            </div>
                        </li>
                        <li class="flex items-center gap-4 bg-gray-800/50 p-3 rounded-lg border border-gray-700">
                            <span class="text-2xl">🛠️</span>
                            <div>
                                <span class="block text-white font-bold">Update Reward (+20 XP)</span>
                                <span class="text-gray-400 text-sm">Menjaga data tetap up-to-date memberikan XP
                                    reguler.</span>
                            </div>
                        </li>
                        <li class="flex items-center gap-4 bg-gray-800/50 p-3 rounded-lg border border-gray-700">
                            <span class="text-2xl">📈</span>
                            <div>
                                <span class="block text-white font-bold">Square Root Leveling</span>
                                <span class="text-gray-400 text-sm">Semakin tinggi level, semakin sulit naik level
                                    (Kurva Eksponensial).</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Card 2: Visual Result -->
                <div
                    class="bg-gray-800 rounded-xl p-6 flex flex-col items-center justify-center border border-purple-500/30 shadow-lg shadow-purple-900/20">
                    <div
                        class="w-20 h-20 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-3xl font-bold text-white mb-4 ring-4 ring-purple-900">
                        24
                    </div>
                    <h3 class="text-xl font-bold text-gray-100">Level 24</h3>
                    <p class="text-purple-400 text-sm font-bold uppercase tracking-wider mb-4">Elite Specialist</p>

                    <div class="w-full bg-gray-700 rounded-full h-2.5 mb-2">
                        <div class="bg-purple-500 h-2.5 rounded-full" style="width: 75%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mb-6">1,250 / 1,500 XP to Level 25</p>

                    <a href="{{ route('gamification.index') }}" target="_blank" class="w-full text-center px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded font-bold transition-colors shadow-lg shadow-purple-900/50">
                        Lihat Leaderboard Live
                    </a>
                </div>
            </div>
        </div>

        <!-- SLIDE 7: ENDING -->
        <div x-show="slide === 7" x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-24" x-transition:enter-end="opacity-100 translate-y-0"
            class="flex flex-col items-center justify-center text-center">

            <h1 class="text-6xl md:text-8xl font-black text-white mb-8 neon-text">
                LIVE DEMO
            </h1>

            <p class="text-2xl text-gray-400 mb-12 max-w-2xl">
                Silahkan akses aplikasi sekarang untuk melihat implementasi langsung.
            </p>

            <div class="flex gap-4">
                <a href="/dashboard"
                    class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold transition transform hover:scale-105 shadow-lg">
                    Go to Dashboard
                </a>
                <a href="/gamification"
                    class="px-8 py-4 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold transition transform hover:scale-105 shadow-lg">
                    Check Leaderboard
                </a>
            </div>

            <div class="mt-20 text-gray-600 font-mono text-sm">
                Tekan <kbd class="px-2 py-1 bg-gray-800 rounded border border-gray-700 text-gray-400">←</kbd> atau <kbd
                    class="px-2 py-1 bg-gray-800 rounded border border-gray-700 text-gray-400">→</kbd> untuk navigasi
            </div>
        </div>

    </main>

</body>

</html>