@props([
    'showRoute' => null,
    'editRoute' => null,
    'deleteId' => null,      // UBAH dari deleteAction
    'deleteName' => null,    // TAMBAH
    'additionalButtons' => null,
])

<div {{ $attributes->merge(['class' => 'flex items-center justify-end gap-2']) }}>
    
    @if($showRoute)
        <a href="{{ $showRoute }}" 
           class="p-2 bg-sky-50 text-sky-600 rounded-lg hover:bg-sky-100 transition" 
           title="Detail">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </a>
    @endif
    
    @if($editRoute)
        <a href="{{ $editRoute }}" 
           class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition" 
           title="Edit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </a>
    @endif
    
    {{ $additionalButtons ?? '' }}
    
    @if($deleteId)
        <button @click="confirmDelete({{ $deleteId }}, '{{ addslashes($deleteName) }}')"
                class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" 
                title="Hapus">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </button>
    @endif
</div>