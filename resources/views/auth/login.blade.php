<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'Inventaris Biomedis') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .heading-font { font-family: 'Poppins', sans-serif; }

        /* --- ANIMATIONS --- */
        .animate-fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- PATTERNS --- */
        /* Grid Pattern for Technical Feel */
        .bg-tech-grid {
            background-size: 40px 40px;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        }

        /* Organic Blobs Animation */
        @keyframes float {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: float 10s infinite ease-in-out;
        }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 antialiased">

    <div class="min-h-screen w-full flex flex-col lg:flex-row">

        <div class="hidden lg:flex lg:w-1/2 relative bg-indigo-900 items-center justify-center overflow-hidden">
            
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-blue-900 to-slate-900"></div>

            <div class="absolute inset-0 bg-tech-grid opacity-30"></div>

            <div class="absolute top-0 -left-4 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-32 left-20 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-blob animation-delay-4000"></div>

            <div class="relative z-10 p-12 text-center max-w-lg">
                <div class="mb-8 inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-lg border border-white/20 shadow-2xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>

                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6 heading-font leading-tight">
                    Smart <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-purple-300">Inventory</span>
                    <br>Management.
                </h2>
                
                <p class="text-blue-100/80 text-lg font-light leading-relaxed mb-8">
                    Sistem pemantauan aset biomedis terintegrasi untuk efisiensi laboratorium dan presisi data yang lebih tinggi.
                </p>

                <div class="flex justify-center gap-4">
                    <div class="px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm text-xs text-blue-200 font-mono">
                        v2.0 Stable
                    </div>
                    <div class="px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm text-xs text-blue-200 font-mono">
                        Secure Access
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12 relative overflow-y-auto">
            
            <div class="lg:hidden absolute top-0 left-0 w-full h-64 bg-indigo-900 rounded-b-[3rem] -z-10"></div>

            <div class="w-full max-w-md bg-white p-8 lg:p-12 rounded-3xl shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100 animate-fade-in-up">
                
                <div class="lg:hidden flex justify-center mb-6">
                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="mb-10 text-center lg:text-left">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 heading-font">Welcome Back</h1>
                    <p class="text-gray-500">Masuk untuk mengelola data inventaris.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="animate-fade-in-up delay-100 group">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Email</label>
                        <div class="relative transition-all duration-300 transform group-focus-within:-translate-y-1">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                class="block w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 placeholder-gray-400 shadow-sm focus:shadow-md"
                                placeholder="admin@biomedis.itb.ac.id">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 pl-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="animate-fade-in-up delay-200 group">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Password</label>
                        <div class="relative transition-all duration-300 transform group-focus-within:-translate-y-1">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="block w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 placeholder-gray-400 shadow-sm focus:shadow-md"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 pl-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between animate-fade-in-up delay-300">
                        <label class="flex items-center cursor-pointer group">
                            <div class="relative">
                                <input id="remember_me" type="checkbox" name="remember" class="sr-only">
                                <div class="w-5 h-5 border-2 border-gray-300 rounded bg-white transition-all duration-200 group-hover:border-indigo-400"></div>
                                <svg class="w-3 h-3 text-white absolute top-1 left-1 opacity-0 pointer-events-none transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-800 transition-colors">Ingat saya</span>
                        </label>
                        
                        <script>
                            // Simple Custom Checkbox Script (Optional visual enhancement)
                            document.getElementById('remember_me').addEventListener('change', function() {
                                const box = this.nextElementSibling;
                                const check = box.nextElementSibling;
                                if(this.checked) {
                                    box.classList.remove('bg-white', 'border-gray-300');
                                    box.classList.add('bg-indigo-600', 'border-indigo-600');
                                    check.classList.remove('opacity-0');
                                } else {
                                    box.classList.add('bg-white', 'border-gray-300');
                                    box.classList.remove('bg-indigo-600', 'border-indigo-600');
                                    check.classList.add('opacity-0');
                                }
                            });
                        </script>

                        @if (Route::has('password.request'))
                            <a class="text-sm font-medium text-indigo-600 hover:text-purple-600 transition-colors duration-200" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <div class="pt-2 animate-fade-in-up delay-400">
                        <button type="submit"
                            class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-700 hover:from-indigo-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:-translate-y-0.5 transition-all duration-300">
                            Sign In
                        </button>
                    </div>
                </form>
            </div>

            <div class="absolute bottom-4 text-center w-full lg:w-auto text-xs text-gray-400 animate-fade-in-up delay-400">
                &copy; {{ date('Y') }} Lab Biomedis. Protected System.
            </div>
        </div>
    </div>
</body>
</html>