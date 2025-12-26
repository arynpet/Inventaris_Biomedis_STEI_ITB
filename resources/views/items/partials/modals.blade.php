{{-- BAGIAN 1: MODAL KONFIRMASI HAPUS SINGLE --}}
<div x-show="showModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center backdrop-blur-sm z-50">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-gray-200 mx-4" @click.away="showModal = false">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center"><svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
            <h2 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h2>
        </div>
        <p class="text-gray-600 text-sm mb-6">Hapus item <span class="font-bold" x-text="deleteName"></span>?</p>
        <div class="flex justify-end gap-3">
            <button @click="showModal = false" class="px-5 py-2.5 bg-gray-100 rounded-lg text-sm">Batal</button>
            <form :action="deleteUrl" method="POST">
                @csrf 
                @method('DELETE')
                <button class="px-5 py-2.5 bg-red-600 text-white rounded-lg text-sm font-bold shadow hover:bg-red-700">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

{{-- BAGIAN 2: FLOATING BULK ACTION BAR (INI YANG KAMU CARI) --}}
{{-- Muncul otomatis jika ada item yang diceklis (selectedItems.length > 0) --}}
<div x-show="selectedItems.length > 0" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-y-full opacity-0"
     x-transition:enter-end="translate-y-0 opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="translate-y-0 opacity-100"
     x-transition:leave-end="translate-y-full opacity-0"
     class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-white px-6 py-4 rounded-2xl shadow-2xl border border-gray-200 z-40 flex items-center gap-6 w-[90%] max-w-2xl">
    
    <div class="flex items-center gap-2 text-gray-700 font-medium">
        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-bold" x-text="selectedItems.length"></span>
        <span>Item Dipilih</span>
    </div>

    <div class="h-8 w-px bg-gray-300"></div>

    <div class="flex items-center gap-3 flex-1 justify-end">
        {{-- Tombol Copy --}}
        <button @click="submitBulkAction('copy')" 
                class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl transition text-sm font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            Duplicate
        </button>

        {{-- Tombol Delete Massal --}}
        <button @click="submitBulkAction('delete')" 
                class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-xl transition text-sm font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Hapus Terpilih
        </button>
    </div>
</div>