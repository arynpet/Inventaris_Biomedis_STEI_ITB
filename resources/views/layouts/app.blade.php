<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Inventory Lab') }}</title>

    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600&display=swap" rel="stylesheet" />

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        

        {{-- Main Content --}}
        <div class="flex-1 ml-64">

            {{-- Top Navigation --}}
            @include('layouts.navigation')

            <main class="p-8">
                {{ $slot }}
            </main>

        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>

</body>
</html>
