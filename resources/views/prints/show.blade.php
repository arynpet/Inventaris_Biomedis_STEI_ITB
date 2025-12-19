<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">
            Detail Print 3D
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-gradient-to-br from-white to-gray-50 shadow-xl rounded-3xl overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300">

                <!-- Header Card -->
                <div class="bg-gradient-to-r from-cyan-500 to-blue-600 p-8">
                    <div class="flex items-center text-white">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mr-5 shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">Informasi Print Job</h3>
                            <p class="text-cyan-100 text-sm mt-1">Detail lengkap pekerjaan 3D printing</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8">
                    
                    <!-- User & Schedule Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                        <!-- User Info -->
                        <div class="group p-6 rounded-2xl border-2 border-gray-100 hover:border-purple-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-start">
                                <div class="w-14 h-14 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Operator</p>
                                    <p class="text-xl font-bold text-gray-800">{{ $print->user->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Date Info -->
                        <div class="group p-6 rounded-2xl border-2 border-gray-100 hover:border-blue-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-start">
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Tanggal</p>
                                    <p class="text-xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($print->date)->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Time & Duration Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                        <!-- Start Time -->
                        <div class="group p-5 rounded-2xl border-2 border-gray-100 hover:border-green-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-center flex-col text-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Mulai</p>
                                <p class="text-lg font-bold text-gray-800">{{ $print->start_time }}</p>
                            </div>
                        </div>

                        <!-- End Time -->
                        <div class="group p-5 rounded-2xl border-2 border-gray-100 hover:border-orange-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-center flex-col text-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Selesai</p>
                                <p class="text-lg font-bold text-gray-800">{{ $print->end_time }}</p>
                            </div>
                        </div>

                        <!-- Duration -->
                        <div class="group p-5 rounded-2xl border-2 border-cyan-100 bg-gradient-to-br from-cyan-50 to-blue-50 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-center flex-col text-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-cyan-700 uppercase tracking-wide mb-1">Durasi</p>
                                <p class="text-2xl font-bold text-cyan-900">{{ $print->duration }} <span class="text-sm">menit</span></p>
                            </div>
                        </div>

                    </div>

                    <!-- Material Section -->
                    <div class="p-6 bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl border-2 border-amber-100 mb-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-800">Detail Material</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white p-4 rounded-xl shadow-sm">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Jenis Material</p>
                                <p class="text-base font-bold text-gray-800">{{ $print->materialType->name ?? '-' }}</p>
                            </div>
                            <div class="bg-white p-4 rounded-xl shadow-sm">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Jumlah</p>
                                <p class="text-base font-bold text-gray-800">{{ $print->material_amount ?? '-' }} {{ $print->material_unit }}</p>
                            </div>
                            <div class="bg-white p-4 rounded-xl shadow-sm">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Sumber</p>
                                <p class="text-base font-bold text-gray-800">{{ $print->material_source ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Notes Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        <!-- Status -->
                        <div class="p-6 bg-white rounded-2xl border-2 border-gray-100 shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Status Print Job</p>
                            </div>
                            
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-300', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'processing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-300', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                    'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-300', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-300', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'cancelled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-300', 'icon' => 'M6 18L18 6M6 6l12 12'],
                                ];
                                $config = $statusConfig[$print->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-300', 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'];
                            @endphp
                            
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-4 py-2.5 {{ $config['bg'] }} {{ $config['text'] }} border-2 {{ $config['border'] }} rounded-xl text-sm font-bold shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"></path>
                                    </svg>
                                    {{ ucfirst($print->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- File Download -->
                        <div class="p-6 bg-white rounded-2xl border-2 border-gray-100 shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-gradient-to-br from-rose-100 to-pink-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">File 3D Model</p>
                            </div>
                            
                            @if($print->file_path)
                                <a href="{{ asset('storage/'.$print->file_path) }}"
                                   target="_blank"
                                   class="group inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-cyan-700 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download File
                                </a>
                            @else
                                <div class="flex items-center text-gray-400">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Tidak ada file</span>
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- Notes Section -->
                    @if($print->notes)
                    <div class="p-6 bg-gradient-to-br from-slate-50 to-gray-50 rounded-2xl border-2 border-slate-100">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-gradient-to-br from-slate-500 to-gray-600 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Catatan</p>
                                <p class="text-gray-700 leading-relaxed">{{ $print->notes }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- Action Footer -->
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-100">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('prints.index') }}"
                            class="group flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl hover:from-gray-600 hover:to-gray-700 transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>

                        <!-- Optional: Print Button -->
                        <button onclick="window.print()"
                            class="group flex items-center px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl hover:from-cyan-600 hover:to-blue-700 transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Cetak
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>

</x-app-layout>