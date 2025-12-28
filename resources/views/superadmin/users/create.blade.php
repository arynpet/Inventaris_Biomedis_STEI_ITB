<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100 p-8">
                
                {{-- Error Validation Display --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                        <p class="font-bold mb-1">Perhatikan hal berikut:</p>
                        <ul class="list-disc ml-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('superadmin.users.store') }}" x-data="{ role: 'admin' }">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1" required autofocus />
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1" required />
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700">Role / Hak Akses</label>
                        <div class="mt-2 grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="admin" x-model="role" class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition text-center">
                                    <span class="block font-bold text-gray-700 peer-checked:text-blue-700">Admin Biasa</span>
                                    <span class="text-xs text-gray-500">Inventory & Peminjaman</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="superadmin" x-model="role" class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-purple-500 peer-checked:bg-purple-50 hover:bg-gray-50 transition text-center">
                                    <span class="block font-bold text-gray-700 peer-checked:text-purple-700">Super Admin</span>
                                    <span class="text-xs text-gray-500">Full Akses & Manage User</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Password Baru</label>
                            <input type="password" name="password" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1" required />
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1" required />
                        </div>
                    </div>

                    <hr class="border-gray-200 my-6">

                    <div x-show="role === 'superadmin'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="mb-6 bg-purple-50 p-5 rounded-xl border border-purple-200">
                        
                        <div class="flex items-center gap-3 mb-3 text-purple-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <h4 class="font-bold text-sm">Verifikasi Keamanan Diperlukan</h4>
                        </div>
                        
                        <label class="block font-medium text-sm text-gray-700">Masukkan Password Anda ({{ auth()->user()->name }})</label>
                        <p class="text-xs text-gray-500 mb-2">Untuk membuat akun Super Admin, konfirmasi identitas Anda.</p>
                        <input type="password" name="superadmin_verification" class="w-full border-purple-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 mt-1" placeholder="Password akun Anda saat ini..." />
                        @error('superadmin_verification') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('superadmin.users.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">Batal</a>
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gray-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>