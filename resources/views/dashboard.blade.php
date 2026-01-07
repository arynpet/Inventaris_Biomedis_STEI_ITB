<x-app-layout>
    <x-slot name="header">
        <h2 id="page-title" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2 transition-colors duration-500">
            <i class="fas fa-satellite-dish text-blue-500"></i>
            {{ __('Command Center') }}
        </h2>
    </x-slot>

    <div class="py-4"> 
        <div class="w-full px-4 sm:px-6 lg:px-8"> 
            
            <div id="nara-console-container" 
                 class="hidden bg-gray-900 overflow-hidden shadow-[0_0_40px_rgba(0,243,255,0.15)] sm:rounded-lg border border-cyan-500/50 relative transition-all duration-700 ease-in-out transform scale-95 opacity-0" 
                 style="height: 85vh; flex-direction: column;"> 
                
                <div class="bg-gray-800 p-3 border-b border-cyan-500/30 flex justify-between items-center shadow-md z-10 relative">
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-3">
                            <div id="status-light" class="w-3 h-3 bg-cyan-500 rounded-full animate-pulse shadow-[0_0_10px_#00f3ff]"></div>
                            <h3 class="text-cyan-400 font-mono font-bold text-lg tracking-widest hidden sm:block">N.A.R.A</h3>
                        </div>
                        
                        <div class="hidden md:flex gap-6 text-[10px] font-mono text-cyan-600/80 border-l border-cyan-900 pl-6">
                            <div class="flex flex-col group cursor-default">
                                <span class="tracking-widest group-hover:text-cyan-400 transition">ASSET DB</span>
                                <span class="text-cyan-100 font-bold text-sm counter" data-target="{{ $totalItems ?? 0 }}">0</span>
                            </div>
                            <div class="flex flex-col group cursor-default">
                                <span class="tracking-widest group-hover:text-cyan-400 transition">ACTIVE DEPLOY</span>
                                <span class="text-blue-300 font-bold text-sm counter" data-target="{{ $activeLoans ?? 0 }}">0</span>
                            </div>
                            <div class="flex flex-col group cursor-default">
                                <span class="tracking-widest group-hover:text-cyan-400 transition">FABRICATION</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-purple-300 font-bold text-sm counter" data-target="{{ $activePrints ?? 0 }}">0</span>
                                    @if(($activePrints ?? 0) > 0) <i class="fas fa-cog fa-spin text-xs text-purple-500"></i> @endif
                                </div>
                            </div>
                            <div class="flex flex-col group cursor-default">
                                <span class="tracking-widest group-hover:text-red-400 transition">SYS WARNING</span>
                                <span class="{{ ($maintenanceCount ?? 0) > 0 ? 'text-red-500 animate-pulse' : 'text-green-400' }} font-bold text-sm counter" data-target="{{ $maintenanceCount ?? 0 }}">0</span>
                            </div>
                        </div>
                    </div>

                    <div x-data="{ expanded: false }" class="absolute right-4 top-2 bottom-2 flex items-center justify-end">
                        <button @click="expanded = !expanded" x-show="!expanded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100" class="w-9 h-9 rounded-full bg-gray-900 border border-red-900/50 text-red-700 hover:text-red-500 hover:border-red-500 hover:shadow-[0_0_15px_rgba(255,0,0,0.5)] transition-all flex items-center justify-center group z-20"><i class="fas fa-power-off text-xs group-hover:animate-pulse"></i></button>
                        <div x-show="expanded" @click.away="expanded = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" class="flex items-center gap-2 bg-red-950/90 border border-red-600 rounded-lg p-1 shadow-[0_0_20px_rgba(255,0,0,0.4)] backdrop-blur-sm z-20">
                            <span class="text-[9px] text-red-400 font-mono font-bold uppercase tracking-widest animate-pulse ml-2 whitespace-nowrap">DANGER ZONE</span>
                            <button onclick="terminateSystem()" class="bg-red-600 hover:bg-red-500 text-white text-[10px] font-bold px-3 py-1.5 rounded shadow-inner border border-red-400 tracking-wider whitespace-nowrap active:scale-95 cursor-pointer transition-transform">TERMINATE</button>
                        </div>
                    </div>
                </div>

                <div id="dashboard-log" class="flex-grow p-6 overflow-y-auto space-y-4 bg-gray-900 scrollbar-hide transition-colors duration-500" 
                     style="background-image: radial-gradient(circle at 50% 50%, rgba(0, 243, 255, 0.02) 0%, transparent 50%);">
                    
                    <div id="welcome-message" class="flex flex-col space-y-1 animate-fade-in-up">
                        <div class="self-start bg-cyan-950/30 text-cyan-300 p-4 rounded-tr-xl rounded-br-xl rounded-bl-xl border-l-4 border-cyan-500 max-w-5xl font-mono text-sm shadow-[0_0_15px_rgba(0,243,255,0.1)]">
                            <p class="mb-2"><strong class="text-cyan-100">SYSTEM:</strong> Koneksi Neural Stabil.</p>
                            <p>Selamat datang di Deck Komando, Kapten. Akses penuh ke Database (Items & Rooms) aktif.</p>
                        </div>
                        <span class="text-[10px] text-cyan-800 font-mono ml-1">NARA v2.5 • Modular Core</span>
                    </div>

                </div>

                <div class="p-4 bg-gray-800 border-t border-cyan-500/30 z-10 transition-colors duration-500" id="input-area">
                    <div class="relative flex items-center gap-4">
                        <div class="absolute left-4 text-cyan-600 animate-pulse"><i class="fas fa-chevron-right"></i></div>
                        <input type="text" id="dashboard-input" class="w-full bg-gray-900/50 border border-cyan-900 text-cyan-300 rounded-lg py-3 pl-10 pr-20 focus:ring-2 focus:ring-cyan-500 focus:border-transparent font-mono placeholder-cyan-800 transition-all shadow-inner text-sm" placeholder="Ketik perintah... (contoh: 'Cari mouse', 'List ruangan')" onkeypress="handleDashboardEnter(event)" autocomplete="off">
                        <button onclick="sendDashboardMessage()" class="absolute right-2 bg-cyan-600 hover:bg-cyan-500 text-white px-4 py-1.5 rounded text-xs font-bold tracking-wide shadow-[0_0_10px_rgba(0,243,255,0.3)] hover:shadow-[0_0_20px_rgba(0,243,255,0.5)] transition-all">EXECUTE</button>
                    </div>
                </div>
            </div>

            <div id="offline-message" class="hidden bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 text-center border border-gray-200 mt-10 transition-all duration-500 opacity-0 transform translate-y-4">
                <div class="text-gray-900 text-2xl font-bold mb-2 flex flex-col items-center"><i class="fas fa-server text-gray-300 mb-4 text-6xl block"></i><span>SYSTEM OFFLINE</span></div>
                <p class="text-gray-500 mb-6 text-sm">Koneksi neural diputus secara manual.</p>
                <button onclick="rebootSystem()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full shadow-lg transition transform hover:scale-105 cursor-pointer flex items-center gap-2 mx-auto text-sm"><i class="fas fa-power-off"></i> REBOOT SYSTEM</button>
            </div>

        </div>
    </div>

    <script>
        // --- 1. ANIMASI STATS ---
        function animateCounters() {
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const speed = 200;
                const updateCount = () => {
                    const count = +counter.innerText;
                    const inc = target / speed;
                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 20);
                    } else { counter.innerText = target; }
                };
                updateCount();
            });
        }

        // --- 2. INIT & MEMORY ---
        document.addEventListener("DOMContentLoaded", () => {
            const status = localStorage.getItem('nara_status');
            const widget = document.getElementById('nara-activator');
            if(widget) widget.style.display = 'none'; 

            if (status === 'online') {
                showConsole(false);
                setTimeout(animateCounters, 800);
                loadChatHistory();
            } else {
                showOffline(false);
            }
        });

        function saveChatToMemory(htmlContent) {
            let history = JSON.parse(localStorage.getItem('nara_chat_history') || "[]");
            history.push(htmlContent);
            if (history.length > 50) history.shift();
            localStorage.setItem('nara_chat_history', JSON.stringify(history));
        }

        function loadChatHistory() {
            const history = JSON.parse(localStorage.getItem('nara_chat_history') || "[]");
            const log = document.getElementById('dashboard-log');
            const welcome = document.getElementById('welcome-message');
            if (history.length > 0) {
                if(welcome) welcome.style.display = 'none';
                history.forEach(html => log.innerHTML += html);
                log.innerHTML += `<div class="text-center text-[10px] text-cyan-800 font-mono my-4 border-b border-cyan-900/30 leading-[0.1em]"><span class="bg-gray-900 px-2">RESTORED SESSION</span></div>`;
                log.scrollTop = log.scrollHeight;
            }
        }

        function wipeMemory() { localStorage.removeItem('nara_chat_history'); }

        // --- 3. VISUAL EFFECTS ---
        function showConsole(animate = true) {
            const el = document.getElementById('nara-console-container');
            const off = document.getElementById('offline-message');
            el.classList.remove('hidden'); el.style.display = 'flex'; off.classList.add('hidden');
            setTimeout(() => { el.classList.remove('scale-95', 'opacity-0'); el.classList.add('scale-100', 'opacity-100'); }, 50);
            document.documentElement.classList.add('nara-mode');
            const title = document.getElementById('page-title');
            if(title) { title.classList.remove('text-gray-800'); title.classList.add('text-cyan-400'); }
        }

        function showOffline(animate = true) {
            const el = document.getElementById('nara-console-container');
            const off = document.getElementById('offline-message');
            el.classList.add('hidden'); el.style.display = 'none';
            off.classList.remove('hidden');
            setTimeout(() => { off.classList.remove('opacity-0', 'translate-y-4'); off.classList.add('opacity-100', 'translate-y-0'); }, 50);
            document.documentElement.classList.remove('nara-mode');
            const title = document.getElementById('page-title');
            if(title) { title.classList.add('text-gray-800'); title.classList.remove('text-cyan-400'); }
        }

        // --- 4. TERMINATE ---
        function terminateSystem() {
            const el = document.getElementById('nara-console-container');
            const log = document.getElementById('dashboard-log');
            const statusLight = document.getElementById('status-light');
            const inputArea = document.getElementById('input-area');

            el.classList.remove('border-cyan-500/50'); el.classList.add('border-red-600', 'shadow-[0_0_50px_rgba(255,0,0,0.3)]');
            statusLight.classList.remove('bg-cyan-500', 'shadow-[0_0_10px_#00f3ff]'); statusLight.classList.add('bg-red-500', 'shadow-[0_0_10px_#ff0000]');

            log.innerHTML += `<div class="mt-6 flex flex-col items-center animate-pulse border-t border-b border-red-900/50 py-4 bg-red-950/20"><div class="text-red-500 font-bold tracking-[0.2em] text-lg">⚠️ CRITICAL ALERT ⚠️</div><div class="text-red-400 font-mono text-xs mt-2 text-center">MANUAL OVERRIDE INITIATED.<br>WIPING NEURAL MEMORY...<br>GOODBYE, OPERATOR.</div></div>`;
            log.scrollTop = log.scrollHeight;
            inputArea.style.opacity = '0.3'; inputArea.style.pointerEvents = 'none';
            
            wipeMemory();

            setTimeout(() => {
                el.classList.remove('scale-100', 'opacity-100'); el.classList.add('scale-90', 'opacity-0');
                setTimeout(() => {
                    localStorage.setItem('nara_status', 'offline');
                    showOffline();
                    setTimeout(() => {
                        el.classList.remove('border-red-600', 'shadow-[0_0_50px_rgba(255,0,0,0.3)]', 'scale-90');
                        el.classList.add('border-cyan-500/50', 'scale-95');
                        statusLight.classList.remove('bg-red-500', 'shadow-[0_0_10px_#ff0000]');
                        statusLight.classList.add('bg-cyan-500', 'shadow-[0_0_10px_#00f3ff]');
                        inputArea.style.opacity = '1'; inputArea.style.pointerEvents = 'auto';
                        log.innerHTML = `<div class="flex flex-col space-y-1 animate-fade-in-up"><div class="self-start bg-cyan-950/30 text-cyan-300 p-4 rounded-tr-xl rounded-br-xl rounded-bl-xl border-l-4 border-cyan-500 max-w-5xl font-mono text-sm shadow-[0_0_15px_rgba(0,243,255,0.1)]"><p class="mb-2"><strong class="text-cyan-100">SYSTEM:</strong> Koneksi Neural Stabil.</p><p>Selamat datang kembali, Kapten. Memori baru telah dibuat.</p></div><span class="text-[10px] text-cyan-800 font-mono ml-1">NARA v2.0 • Rebooted</span></div>`;
                    }, 500);
                }, 700); 
            }, 2500); 
        }

        function rebootSystem() {
            localStorage.setItem('nara_status', 'online');
            window.location.reload(); 
        }

        // --- 5. CHAT LOGIC (INTELLIGENT ROUTER) ---
        function handleDashboardEnter(e) { if (e.key === 'Enter') sendDashboardMessage(); }
        
        async function sendDashboardMessage() {
            const input = document.getElementById('dashboard-input');
            const log = document.getElementById('dashboard-log');
            const message = input.value.trim();
            if (!message) return;

            // Render User Chat
            const userHtml = `<div class="flex flex-col space-y-1 items-end animate-fade-in-up"><div class="bg-gray-700 text-gray-200 p-3 rounded-tl-xl rounded-bl-xl rounded-br-xl max-w-5xl font-mono text-sm border border-gray-600">${message}</div></div>`;
            log.innerHTML += userHtml;
            saveChatToMemory(userHtml);
            input.value = '';
            
            // Loading
            const loadingId = 'loading-' + Date.now();
            log.innerHTML += `<div id="${loadingId}" class="flex flex-col space-y-1 animate-pulse"><div class="self-start bg-cyan-950/20 text-cyan-500 p-3 rounded-xl border border-cyan-900/50 max-w-xs font-mono text-xs flex items-center gap-2"><i class="fas fa-circle-notch fa-spin"></i> Memproses Data...</div></div>`;
            log.scrollTop = log.scrollHeight;

            try {
                const response = await fetch("{{ route('nara.chat') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    body: JSON.stringify({ message: message })
                });
                const data = await response.json();
                document.getElementById(loadingId).remove();

                // Render AI Text Reply
                const aiHtml = `<div class="flex flex-col space-y-1 animate-fade-in-up"><div class="self-start bg-cyan-950/30 text-cyan-300 p-4 rounded-tr-xl rounded-br-xl rounded-bl-xl border-l-4 border-cyan-500 max-w-5xl font-mono text-sm shadow-[0_0_10px_rgba(0,243,255,0.05)]">${data.reply}</div><span class="text-[10px] text-cyan-800 font-mono ml-1">NARA • Active</span></div>`;
                log.innerHTML += aiHtml;
                saveChatToMemory(aiHtml);

                // ============================================
                // SMART RENDERING BERDASARKAN TIPE DATA
                // ============================================

                // 1. Jika Action = CREATE_PREVIEW (Tabel Konfirmasi)
                if (data.action === 'CREATE_PREVIEW' && data.items_preview) {
                    renderCreatePreview(data.items_preview, log);
                }
                // 2. Jika Action = DELETE_CONFIRMATION (Tombol Hapus)
                else if (data.action === 'DELETE_CONFIRMATION' && data.target_items) {
                    const serials = data.target_items.map(item => item.serial_number);
                    renderDeleteConfirm(serials, log);
                }
                // 3. Jika Tipe Render = TABLE_ROOM (Tabel Ruangan) - Feature Baru Modular
                else if (data.render_type === 'TABLE_ROOM' && data.data) {
                    const tbl = renderRoomTable(data.data);
                    log.innerHTML += tbl;
                    saveChatToMemory(tbl);
                }
                // 4. Default: Jika ada Data Barang (Tabel Barang)
                else if (data.data && data.data.length > 0) {
                    const tbl = renderItemTable(data.data);
                    log.innerHTML += tbl;
                    saveChatToMemory(tbl);
                }

            } catch (error) {
                if(document.getElementById(loadingId)) document.getElementById(loadingId).remove();
                log.innerHTML += `<div class="text-red-500 text-xs text-center">Error Connection (Check Console)</div>`;
                console.error(error); 
            }
            log.scrollTop = log.scrollHeight;
        }

        // --- RENDER FUNCTIONS (TAMPILAN BERBEDA UNTUK TIAP TIPE DATA) ---
        
        // A. TABLE ITEM (Full Column)
        function renderItemTable(data) {
            let html = `<div class="overflow-x-auto mt-2 mb-4 border border-cyan-900 rounded-lg max-w-full animate-fade-in-up">
                <table class="w-full text-xs text-left text-cyan-100 font-mono whitespace-nowrap">
                    <thead class="bg-cyan-900/50 text-cyan-400 uppercase">
                        <tr>
                            <th class="px-4 py-2 border-r border-cyan-800">Asset #</th>
                            <th class="px-4 py-2 border-r border-cyan-800">Serial #</th>
                            <th class="px-4 py-2 border-r border-cyan-800">Name</th>
                            <th class="px-4 py-2 border-r border-cyan-800">Room</th>
                            <th class="px-4 py-2 border-r border-cyan-800">Status</th>
                            <th class="px-4 py-2">Cond.</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyan-900/30 bg-gray-900/50">`;
            
            data.forEach(item => {
                let roomName = item.room ? item.room.name : (item.room_id || '-');
                html += `<tr class="hover:bg-cyan-900/20 transition">
                    <td class="px-4 py-2 text-cyan-300 font-bold border-r border-cyan-900/30">${item.asset_number || '-'}</td>
                    <td class="px-4 py-2 border-r border-cyan-900/30">${item.serial_number || '-'}</td>
                    <td class="px-4 py-2 text-white border-r border-cyan-900/30">${item.name || '-'}</td>
                    <td class="px-4 py-2 text-gray-300 border-r border-cyan-900/30">${roomName}</td>
                    <td class="px-4 py-2 border-r border-cyan-900/30"><span class="${item.status === 'available' ? 'text-green-400' : 'text-yellow-400'}">${item.status}</span></td>
                    <td class="px-4 py-2"><span class="${item.condition === 'good' ? 'text-green-400' : 'text-red-400'}">${item.condition}</span></td>
                </tr>`; 
            });
            return html + `</tbody></table></div>`;
        }

        // B. TABLE ROOM (Kolom Beda: Lokasi, Total Item) - FEATURE BARU
        function renderRoomTable(data) {
            let html = `<div class="overflow-x-auto mt-2 mb-4 border border-purple-900 rounded-lg max-w-full animate-fade-in-up">
                <table class="w-full text-xs text-left text-purple-100 font-mono whitespace-nowrap">
                    <thead class="bg-purple-900/50 text-purple-400 uppercase">
                        <tr>
                            <th class="px-4 py-2 border-r border-purple-800">Nama Ruangan</th>
                            <th class="px-4 py-2 border-r border-purple-800">Lokasi</th>
                            <th class="px-4 py-2 border-r border-purple-800">Total Barang</th>
                            <th class="px-4 py-2">PIC</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-900/30 bg-gray-900/50">`;
            
            data.forEach(room => {
                html += `<tr class="hover:bg-purple-900/20 transition">
                    <td class="px-4 py-2 font-bold text-white border-r border-purple-900/30">${room.name}</td>
                    <td class="px-4 py-2 border-r border-purple-900/30">${room.location}</td>
                    <td class="px-4 py-2 border-r border-purple-900/30 text-center">${room.total_items}</td>
                    <td class="px-4 py-2">${room.pic || '-'}</td>
                </tr>`; 
            });
            return html + `</tbody></table></div>`;
        }

        // C. CREATE PREVIEW TABLE
        function renderCreatePreview(items, log) {
            const id = 'create-' + Date.now();
            const itemsJson = JSON.stringify(items).replace(/"/g, '&quot;');
            
            let html = `<div class="overflow-x-auto mt-2 mb-4 border border-blue-900 rounded-lg max-w-full animate-fade-in-up">
                <div class="bg-blue-900/50 text-blue-300 px-4 py-2 text-xs font-bold uppercase border-b border-blue-800">DRAFT BARANG BARU (${items.length})</div>
                <table class="w-full text-xs text-left text-cyan-100 font-mono whitespace-nowrap">
                    <thead class="bg-gray-900 text-gray-400 uppercase">
                        <tr>
                            <th class="px-4 py-2">Asset #</th>
                            <th class="px-4 py-2">Serial # (Auto)</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Room</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800 bg-gray-900/50">`;
            
            items.forEach(item => {
                html += `<tr>
                    <td class="px-4 py-2 text-yellow-400">${item.asset_number}</td>
                    <td class="px-4 py-2 text-cyan-300">${item.serial_number}</td>
                    <td class="px-4 py-2">${item.name}</td>
                    <td class="px-4 py-2 text-gray-400">${item.display_room}</td>
                </tr>`;
            });
            html += `</tbody></table>
                <div id="${id}" class="p-3 flex gap-3 bg-gray-900/80 border-t border-blue-900">
                    <button onclick="executeCreate(${itemsJson}, '${id}')" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded text-xs font-bold shadow-[0_0_10px_rgba(0,100,255,0.4)] transition">SIMPAN SEMUA DATA</button>
                    <button onclick="document.getElementById('${id}').parentElement.remove()" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded text-xs border border-gray-500 transition">BATALKAN</button>
                </div>
            </div>`;
            log.innerHTML += html;
        }

        // D. DELETE CONFIRMATION
        function renderDeleteConfirm(serials, log) {
            const id = 'conf-' + Date.now();
            const serialsJson = JSON.stringify(serials).replace(/"/g, '&quot;');
            
            const html = `<div id="${id}" class="mt-2 ml-2 flex gap-2 animate-fade-in-up">
                    <button onclick="executeDelete(${serialsJson}, '${id}')" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded text-xs border border-red-400 shadow-[0_0_10px_rgba(255,0,0,0.4)] transition">YA, HAPUS SEMUA (${serials.length})</button>
                    <button onclick="document.getElementById('${id}').remove()" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded text-xs border border-gray-500 transition">BATALKAN</button>
                </div>`;
            log.innerHTML += html;
        }

        // --- EXECUTE FUNCTIONS ---
        async function executeCreate(items, elementId) {
            document.getElementById(elementId).remove();
            const log = document.getElementById('dashboard-log');
            log.innerHTML += `<div class="text-cyan-500 text-xs mt-2 italic">Menyimpan data ke database...</div>`;
            
            try {
                const response = await fetch("{{ route('nara.store_batch') }}", { 
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    body: JSON.stringify({ items: items }) 
                });
                const result = await response.json();
                if(result.success) {
                    let msg = `<div class="text-green-400 font-mono mt-1 border-l-2 border-green-500 pl-2">>> SUCCESS: ${result.message}</div>`;
                    log.innerHTML += msg;
                    saveChatToMemory(msg);
                } else {
                    log.innerHTML += `<div class="text-red-400 font-mono mt-1">>> FAILED: ${result.message}</div>`;
                }
            } catch(e) { log.innerHTML += `<div class="text-red-400">System Error.</div>`; }
            log.scrollTop = log.scrollHeight;
        }

        async function executeDelete(serials, id) {
             document.getElementById(id).remove();
             const log = document.getElementById('dashboard-log');
             log.innerHTML += `<div class="text-cyan-500 text-xs mt-2 italic">Menghapus ${serials.length} data...</div>`;
             try {
                const response = await fetch("{{ route('nara.destroy') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    body: JSON.stringify({ serial_numbers: serials }) 
                });
                const result = await response.json();
                if(result.success) {
                    let msg = `<div class="text-green-400 font-mono mt-1 border-l-2 border-green-500 pl-2">>> SUCCESS: ${result.message}</div>`;
                    log.innerHTML += msg;
                    saveChatToMemory(msg);
                } else {
                    log.innerHTML += `<div class="text-red-400 font-mono mt-1">>> FAILED: ${result.message}</div>`;
                }
             } catch(e) { log.innerHTML += `<div class="text-red-400">System Error.</div>`; }
             log.scrollTop = log.scrollHeight;
        }
    </script>

    <style>
        .animate-fade-in-up { animation: fadeInUp 0.4s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-app-layout>