<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <form action="{{ route('items.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
        {{-- Search Input --}}
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm"
                   placeholder="Cari Nama, No Asset, atau Serial Number...">
        </div>

        {{-- Filters --}}
        <div class="flex flex-wrap md:flex-nowrap gap-3">
            <select name="status" class="w-full md:w-40 rounded-xl border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Status</option>
                @foreach(['available', 'borrowed', 'maintenance', 'dikeluarkan'] as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                @endforeach
            </select>

            <select name="room_id" class="w-full md:w-48 rounded-xl border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Ruangan</option>
                {{-- Pastikan variabel $rooms dikirim dari Controller --}}
                @foreach($rooms ?? [] as $room) 
                    <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gray-800 text-white rounded-xl hover:bg-gray-900 transition-colors font-semibold text-sm">
                Filter
            </button>

            @if(request()->anyFilled(['search', 'status', 'room_id']))
                <a href="{{ route('items.index') }}" class="w-full md:w-auto px-6 py-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-colors font-semibold text-sm text-center">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>