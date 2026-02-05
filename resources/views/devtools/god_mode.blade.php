<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God Mode // Control Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            background-color: #0d1117;
            color: #c9d1d9;
            font-family: 'Consolas', 'Monaco', monospace;
        }

        .tech-box {
            border: 1px solid #30363d;
            background: #161b22;
        }

        .tech-header {
            border-bottom: 1px solid #30363d;
            background: #0d1117;
        }

        .matrix-text {
            color: #00ff41;
            text-shadow: 0 0 5px rgba(0, 255, 65, 0.5);
        }

        input {
            background: #0d1117;
            border: 1px solid #30363d;
            color: #58a6ff;
            padding: 4px 8px;
            font-size: 0.9em;
        }

        input:focus {
            outline: none;
            border-color: #58a6ff;
        }

        .btn-update {
            background: #1f6feb;
            color: white;
            border: none;
            padding: 4px 12px;
            font-size: 0.8em;
            cursor: pointer;
        }

        .btn-update:hover {
            background: #388bfd;
        }
    </style>
</head>

<body class="p-8">

    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
            <div>
                <h1 class="text-3xl font-bold matrix-text tracking-tighter"> GOD MODE <span
                        class="text-gray-600 text-lg">// v1.0.0</span></h1>
                <p class="text-gray-500 text-sm">Direct manipulation of user statistics table.</p>
            </div>
            <a href="{{ url('/dashboard') }}" class="text-gray-500 hover:text-white text-sm">[ Exit to Reality ]</a>
        </div>

        @if(session('success'))
            <div class="bg-green-900/30 border border-green-700 text-green-400 p-3 mb-6 rounded text-sm">
                > SUCCESS: {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <div class="tech-box rounded overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="tech-header text-gray-400 text-xs uppercase">
                    <tr>
                        <th class="p-3 border-r border-gray-800">ID</th>
                        <th class="p-3 border-r border-gray-800">User Identity</th>
                        <th class="p-3 border-r border-gray-800 text-right">Calc. Base XP</th>
                        <th class="p-3 border-r border-gray-800 text-right text-yellow-500">Bonus XP (Input)</th>
                        <th class="p-3 border-r border-gray-800 text-right text-blue-400">Total Seconds On (Input)</th>
                        <th class="p-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-sm">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="p-3 border-r border-gray-800 text-gray-500 font-mono">#{{ $user->id }}</td>
                            <td class="p-3 border-r border-gray-800">
                                <div class="font-bold text-gray-200">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="p-3 border-r border-gray-800 text-right text-gray-400 font-mono">
                                {{ number_format($baseXpMap[$user->id] ?? 0) }}
                            </td>
                            <td class="p-3 border-r border-gray-800 text-right">
                                <input type="number" id="bonus_xp_{{ $user->id }}" value="{{ $user->bonus_xp ?? 0 }}" class="w-32 text-right text-yellow-500 font-bold">
                            </td>
                            <td class="p-3 border-r border-gray-800 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <span class="text-xs text-gray-600">
                                        {{ floor(($user->total_seconds_online ?? 0) / 3600) }}h
                                        {{ floor((($user->total_seconds_online ?? 0) % 3600) / 60) }}m
                                    </span>
                                    <input type="number" id="seconds_{{ $user->id }}" value="{{ $user->total_seconds_online ?? 0 }}"
                                        class="w-32 text-right text-blue-400 font-bold">
                                </div>
                            </td>
                            <td class="p-3 text-center">
                                <button type="button" onclick="submitUpdate({{ $user->id }})"
                                    class="btn-update rounded px-3 py-1 uppercase font-bold text-xs tracking-wide">
                                    Save
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 text-xs text-gray-600 font-mono">
            > SYSTEM STATUS: ONLINE<br>
            > LISTENING ON PORT 80...<br>
            > REMEMBER: WITH GREAT POWER COMES... ABSOLUTELY NO RESPONSIBILITY IN DEV MODE.
        </div>

    </div>

    <!-- Hidden Generic Form for Submission -->
    <form id="god-mode-form" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="bonus_xp" id="form-bonus-xp">
        <input type="hidden" name="total_seconds_online" id="form-seconds">
    </form>

    <script>
        lucide.createIcons();

        function submitUpdate(userId) {
            // 1. Get Values
            const bonusXp = document.getElementById('bonus_xp_' + userId).value;
            const seconds = document.getElementById('seconds_' + userId).value;

            // 2. Set to Hidden Form
            document.getElementById('form-bonus-xp').value = bonusXp;
            document.getElementById('form-seconds').value = seconds;

            // 3. Set Action URL
            const form = document.getElementById('god-mode-form');
            // Construct route manually since we are in JS. Assumes standard Laravel routing.
            // Route: dev.tools.update -> /dev-tools/update/{id}
            form.action = "{{ url('/dev-tools/update') }}/" + userId;

            // 4. Submit
            form.submit();
        }
    </script>
</body>

</html>