<tr class="hover:bg-blue-50/50 transition-colors duration-200" :class="{'bg-blue-50': selectedItems.includes('{{ $item->id }}')}">
    <td class="px-4 py-4 text-center">
        <input type="checkbox" name="selected_ids[]" value="{{ $item->id }}" 
               @click="toggleItem('{{ $item->id }}')" 
               :checked="selectedItems.includes('{{ $item->id }}')"
               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 cursor-pointer w-4 h-4">
    </td>
    <td class="px-4 py-4 whitespace-nowrap">
        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-xs">{{ $item->id }}</span>
    </td>
    <td class="px-4 py-4 whitespace-normal break-words max-w-xs">
        <span class="font-semibold text-gray-900">{{ $item->name }}</span>
    </td>
    <td class="px-4 py-4 whitespace-nowrap"><span class="text-gray-600 font-mono text-xs">{{ $item->asset_number ?? '-' }}</span></td>
    <td class="px-4 py-4 whitespace-nowrap"><span class="text-gray-800 font-mono text-xs font-bold">{{ $item->serial_number }}</span></td>
    <td class="px-4 py-4">
        @if ($item->qr_code)
            <img src="{{ asset('storage/'.$item->qr_code) }}" class="w-10 h-10 rounded border hover:scale-150 transition-transform bg-white">
        @else
            <span class="text-xs text-gray-400 italic">Belum ada</span>
        @endif
    </td>
    <td class="px-4 py-4 whitespace-nowrap">
        <span class="inline-flex items-center gap-1.5 text-gray-700">{{ $item->room->name }}</span>
    </td>
    <td class="px-4 py-4 whitespace-nowrap">
        <span class="inline-flex items-center px-2.5 py-0.5 bg-gray-100 rounded-full text-xs font-bold text-gray-700 border border-gray-200">{{ $item->quantity }}</span>
    </td>
    <td class="px-4 py-4 whitespace-nowrap"><x-status-badge :status="$item->status" /></td>
    
    {{-- BAGIAN INI JADI PENDEK KARENA PAKAI ACCESSOR MODEL --}}
    <td class="px-4 py-4 whitespace-nowrap">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $item->condition_badge_class }}">
            {{ $item->condition_label }}
        </span>
    </td>

    <td class="px-4 py-4 whitespace-normal max-w-xs">
        <div class="flex flex-wrap gap-1">
            @foreach ($item->categories as $cat)
                <span class="inline-flex items-center px-2 py-0.5 text-[10px] bg-blue-50 text-blue-600 rounded border border-blue-100">{{ $cat->name }}</span>
            @endforeach
        </div>
    </td>
    <td class="px-4 py-4 whitespace-nowrap">
        <div class="flex items-center gap-2">
            <a href="{{ route('items.show', $item->id) }}" class="p-1.5 bg-sky-100 text-sky-600 rounded-lg hover:bg-sky-200 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
            <a href="{{ route('items.edit', $item->id) }}" class="p-1.5 bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-200 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
            @if($item->status !== 'dikeluarkan')
            <a href="{{ route('items.out.create', $item->id) }}" class="p-1.5 bg-orange-100 text-orange-600 rounded-lg hover:bg-orange-600 hover:text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg></a>
            @endif
            <button type="button" @click="confirmDelete({{ $item->id }}, '{{ $item->name }}')" class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
        </div>
    </td>
</tr>