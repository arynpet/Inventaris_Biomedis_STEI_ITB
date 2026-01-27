<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti Pengembalian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
        {{-- Header --}}
        <div class="bg-blue-600 px-6 py-6 text-center">
            <svg class="w-12 h-12 text-white mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <h1 class="text-xl font-bold text-white">Upload Bukti Kembali</h1>
            <p class="text-blue-100 text-sm mt-1">Silakan foto barang yang dikembalikan</p>
        </div>

        {{-- Form --}}
        <div class="p-6">
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('mobile.upload.handle') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div x-data="{ imagePreview: null }" class="space-y-6">

                    {{-- Upload Area --}}
                    <div class="relative">
                        <label class="block w-full cursor-pointer">
                            <input type="file" name="image" accept="image/*" capture="environment" class="hidden"
                                @change="imagePreview = URL.createObjectURL($event.target.files[0])">

                            {{-- Placeholder --}}
                            <div x-show="!imagePreview"
                                class="border-2 border-dashed border-gray-300 rounded-2xl h-64 flex flex-col items-center justify-center bg-gray-50 hover:bg-gray-100 transition">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="text-sm text-gray-500 font-medium">Ketuk untuk Ambil Foto</span>
                            </div>

                            {{-- Preview --}}
                            <div x-show="imagePreview" class="relative h-64 rounded-2xl overflow-hidden"
                                style="display: none;">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                    <span
                                        class="bg-white/90 text-gray-800 px-4 py-2 rounded-full text-sm font-bold shadow-lg">Ganti
                                        Foto</span>
                                </div>
                            </div>
                        </label>
                    </div>

                    <p class="text-xs text-center text-gray-400">Pastikan kondisi barang terlihat jelas.</p>

                    <button type="submit"
                        class="w-full py-3.5 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 active:scale-95 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        Kirim Bukti Foto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>