<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('superadmin.logs.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Timeline Perubahan Data') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Info Data --}}
            <div
                class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6 flex justify-between items-center">
                <div>
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Timeline Untuk</div>
                    <div class="text-2xl font-bold text-gray-800">
                        @if($targetObject)
                            {{ $targetObject->name ?? $targetObject->code ?? class_basename($model) }}
                            @if(class_basename($model) === 'Item')
                                <span class="text-sm font-normal text-gray-500">(SN: {{ $targetObject->serial_number ?? '-' }})</span>
                            @endif
                        @else
                            {{ class_basename($model) }}
                        @endif
                    </div>
                    <div class="text-sm text-gray-500 font-mono flex items-center gap-2 mt-1">
                        <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600">Type: {{ class_basename($model) }}</span>
                        <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600">ID: {{ $id }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Revisi</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ $logs->count() }}</div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="relative border-l-4 border-gray-200 ml-4 space-y-8">

                @forelse ($logs as $log)
                    <div class="relative pl-8">
                        {{-- Dot Indikator --}}
                        <div class="absolute -left-3 top-0 w-6 h-6 rounded-full border-4 border-white 
                                @if($log->action == 'create') bg-green-500
                                @elseif($log->action == 'update') bg-blue-500
                                @elseif($log->action == 'delete') bg-red-500
                                @else bg-gray-500 @endif">
                        </div>

                        {{-- Card Content --}}
                        <div class="bg-white p-5 rounded-lg shadow-md border border-gray-100 hover:shadow-lg transition">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-bold text-white uppercase mb-1
                                            @if($log->action == 'create') bg-green-500
                                            @elseif($log->action == 'update') bg-blue-500
                                            @elseif($log->action == 'delete') bg-red-500
                                            @else bg-gray-500 @endif">
                                        {{ $log->action }}
                                    </span>
                                    <h4 class="font-bold text-gray-800">{{ $log->user->name ?? 'System' }}</h4>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-bold text-gray-600">{{ $log->created_at->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                                </div>
                            </div>

                            <div
                                class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-100 text-gray-700 text-sm leading-relaxed">
                                {!! $log->description !!}
                            </div>

                            {{-- Jika Anda menyimpan data 'changes' (old/new value) di activity log --}}
                            @if(!empty($log->properties))
                                <div
                                    class="mt-4 bg-gray-50 p-3 rounded text-xs font-mono overflow-x-auto border border-gray-200">
                                    <pre>{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="pl-8 text-gray-500 italic">Tidak ada riwayat perubahan tercatat untuk data ini.</div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>