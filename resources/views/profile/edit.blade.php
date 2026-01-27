<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Account Settings') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Manage your profile, security, and preferences.</p>
            </div>

            <div
                class="flex items-center gap-2 text-sm text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                <div
                    class="w-2 h-2 rounded-full {{ auth()->user()->email_verified_at ? 'bg-green-500' : 'bg-red-500' }}">
                </div>
                {{ auth()->user()->email_verified_at ? 'Verified Account' : 'Unverified' }}
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- LEFT COLUMN: Profile & Preferences --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- 1. Profile Information --}}
                    <div
                        class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-2xl relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Profile Information</h3>
                                <p class="text-xs text-gray-500">Update your account's profile information and email
                                    address.</p>
                            </div>
                        </div>

                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    {{-- 2. Developer Mode (Only for Superadmin) --}}
                    @if(auth()->user()->role === 'superadmin')
                        <div
                            class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-2xl relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
                            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                                <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ __('Developer Tools') }}</h3>
                                    <p class="text-xs text-gray-500">Utilities for testing and debugging.</p>
                                </div>
                            </div>

                            <div class="max-w-xl">
                                <section>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">
                                                {{ __('Developer Mode') }}
                                            </h4>
                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ __('Enable "Magic Buttons" to auto-fill forms with dummy data.') }}
                                            </p>
                                        </div>

                                        <form method="post" action="{{ route('profile.dev_mode') }}">
                                            @csrf
                                            @method('patch')

                                            @if(auth()->user()->is_dev_mode)
                                                <button type="submit"
                                                    class="px-4 py-2 bg-red-100 text-red-700 font-bold rounded-lg hover:bg-red-200 transition text-sm flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                    Matikan Mode Pengembang
                                                </button>
                                            @else
                                                <button type="submit"
                                                    class="px-4 py-2 bg-green-100 text-green-700 font-bold rounded-lg hover:bg-green-200 transition text-sm flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Aktifkan Mode Pengembang
                                                </button>
                                            @endif
                                        </form>
                                    </div>

                                    @if (session('status') === 'Developer Mode Activated! 🚀')
                                        <div x-data="{ show: true }" x-show="show" x-transition
                                            x-init="setTimeout(() => show = false, 3000)"
                                            class="mt-4 p-3 bg-indigo-50 text-indigo-700 text-sm rounded-lg flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    @if (session('status') === 'Developer Mode Deactivated.')
                                        <div x-data="{ show: true }" x-show="show" x-transition
                                            x-init="setTimeout(() => show = false, 3000)"
                                            class="mt-4 p-3 bg-gray-100 text-gray-600 text-sm rounded-lg flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                </section>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- RIGHT COLUMN: Security & Danger --}}
                <div class="lg:col-span-1 space-y-8">

                    {{-- 3. Security (Password) --}}
                    <div
                        class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-2xl relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-yellow-400"></div>
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                            <div class="p-2 bg-yellow-100 text-yellow-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Security</h3>
                                <p class="text-xs text-gray-500">Update your password.</p>
                            </div>
                        </div>

                        <div>
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    {{-- 4. Danger Zone --}}
                    <div
                        class="p-6 sm:p-8 bg-red-50 shadow-sm border border-red-100 rounded-2xl relative overflow-hidden">
                        <div class="flex items-center gap-3 mb-6 border-b border-red-200 pb-4">
                            <div class="p-2 bg-red-100 text-red-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-red-900">Danger Zone</h3>
                                <p class="text-xs text-red-600">Irreversible actions.</p>
                            </div>
                        </div>

                        <div>
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="py-6 text-center text-xs text-gray-400">
        <p>&copy; {{ date('Y') }} Biomedis All Rights Reserved. Created by Raden <span id="secret-trigger"
                class="cursor-default hover:text-gray-500 transition duration-300">Satya</span> and Aryan Veda.</p>
    </div>

    {{-- EASTER EGG SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const trigger = document.getElementById('secret-trigger');
            let clickCount = 0;
            let resetTimer;

            if (trigger) {
                trigger.addEventListener('click', function () {
                    clickCount++;

                    // Reset timer on every click (debounce)
                    clearTimeout(resetTimer);
                    resetTimer = setTimeout(() => {
                        clickCount = 0; // Reset if inactive for 2 seconds
                    }, 2000);

                    console.log('Click:', clickCount); // Debugging

                    if (clickCount >= 6) {
                        clickCount = 0; // Reset immediately to prevent multiple triggers

                        const isSuperAdmin = @json(auth()->user()->isSuperAdmin());

                        if (isSuperAdmin) {
                            // CASE 1: SUPERADMIN (Langsung Konfirmasi)
                            Swal.fire({
                                title: 'Activate Developer Mode?',
                                text: 'Superadmin detected. Enable Dev Mode instantly?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, Activate!',
                                showLoaderOnConfirm: true,
                                preConfirm: () => {
                                    return fetch('{{ route("profile.upgrade_dev") }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                        // No password needed for superadmin (handled by backend)
                                    })
                                        .then(response => response.json())
                                        .catch(error => Swal.showValidationMessage(`Request failed: ${error}`))
                                }
                            }).then(handleResult);
                        } else {
                            // CASE 2: USER BIASA (Butuh Password)
                            Swal.fire({
                                title: 'Developer Access',
                                input: 'password',
                                inputLabel: 'Enter Secret Key',
                                inputPlaceholder: 'Type password...',
                                showCancelButton: true,
                                confirmButtonText: 'Unlock',
                                showLoaderOnConfirm: true,
                                preConfirm: (password) => {
                                    return fetch('{{ route("profile.upgrade_dev") }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ password: password })
                                    })
                                        .then(response => {
                                            if (!response.ok) throw new Error(response.statusText);
                                            return response.json();
                                        })
                                        .catch(error => Swal.showValidationMessage(`Request failed: ${error}`))
                                }
                            }).then(handleResult);
                        }

                        function handleResult(result) {
                            if (result.isConfirmed) {
                                if (result.value && result.value.success) {
                                    Swal.fire('Access Granted!', result.value.message, 'success')
                                        .then(() => window.location.reload());
                                } else {
                                    Swal.fire('Error', 'Invalid Password or unauthorized.', 'error');
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>