<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            Detail Peminjaman Ruangan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-gradient-to-br from-white to-gray-50 shadow-xl rounded-3xl overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300">

                <!-- Header Card -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-8">
                    <div class="flex items-center text-white">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mr-5 shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">Informasi Peminjaman</h3>
                            <p class="text-blue-100 text-sm mt-1">Detail lengkap reservasi ruangan</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Room Info -->
                        <div class="group p-5 rounded-2xl border-2 border-gray-100 hover:border-blue-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Ruangan</p>
                                    <p class="text-lg font-bold text-gray-800">{{ $roomBorrowing->room->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Borrower Info -->
                        <div class="group p-5 rounded-2xl border-2 border-gray-100 hover:border-purple-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Peminjam</p>
                                    <p class="text-lg font-bold text-gray-800">{{ $roomBorrowing->user->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Start Time -->
                        <div class="group p-5 rounded-2xl border-2 border-gray-100 hover:border-green-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Waktu Mulai</p>
                                    <p class="text-base font-bold text-gray-800">{{ \Carbon\Carbon::parse($roomBorrowing->start_time)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- End Time -->
                        <div class="group p-5 rounded-2xl border-2 border-gray-100 hover:border-orange-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Waktu Selesai</p>
                                    <p class="text-base font-bold text-gray-800">{{ \Carbon\Carbon::parse($roomBorrowing->end_time)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Purpose Section -->
                    <div class="mt-6 p-6 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl border-2 border-indigo-100">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide mb-2">Keperluan</p>
                                <p class="text-gray-800 leading-relaxed">{{ $roomBorrowing->purpose }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Notes -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Status -->
                        <div class="p-5 bg-white rounded-2xl border-2 border-gray-100 shadow-sm">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</p>
                            </div>
                            
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-300'],
                                    'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-300'],
                                    'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-300'],
                                    'completed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-300'],
                                    'cancelled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-300'],
                                ];
                                $config = $statusConfig[$roomBorrowing->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-300'];
                            @endphp
                            
                            <span class="inline-flex items-center px-4 py-2 {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }} rounded-xl text-sm font-bold shadow-sm">
                                {{ ucfirst($roomBorrowing->status) }}
                            </span>
                        </div>

                        <!-- Notes -->
                        <div class="p-5 bg-white rounded-2xl border-2 border-gray-100 shadow-sm">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Catatan</p>
                            </div>
                            <p class="text-gray-700 leading-relaxed">{{ $roomBorrowing->notes ?? 'Tidak ada catatan' }}</p>
                        </div>

                    </div>

                    <!-- Duration Info -->
                    @php
                        $start = \Carbon\Carbon::parse($roomBorrowing->start_time);
                        $end = \Carbon\Carbon::parse($roomBorrowing->end_time);
                        $duration = $start->diff($end);
                        $hours = $duration->h + ($duration->days * 24);
                        $minutes = $duration->i;
                    @endphp
                    
                    <div class="mt-6 p-5 bg-gradient-to-r from-cyan-50 to-blue-50 rounded-2xl border-2 border-cyan-100">
                        <div class="flex items-center justify-center">
                            <div class="text-center">
                                <div class="flex items-center justify-center mb-2">
                                    <svg class="w-6 h-6 text-cyan-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <p class="text-sm font-semibold text-cyan-700 uppercase tracking-wide">Durasi Peminjaman</p>
                                </div>
                                <p class="text-3xl font-bold text-cyan-900">
                                    @if($hours > 0)
                                        {{ $hours }} jam
                                    @endif
                                    @if($minutes > 0)
                                        {{ $minutes }} menit
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Action Footer -->
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-100">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('room_borrowings.index') }}"
                            class="group flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl hover:from-gray-600 hover:to-gray-700 transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>

                        <!-- Optional: Print Button -->
                        <button onclick="window.print()"
                            class="group flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-purple-700 transform hover:-translate-y-0.5 transition-all duration-200">
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