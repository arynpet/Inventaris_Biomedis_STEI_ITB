<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevTools Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #0d1117;
            color: #c9d1d9;
            font-family: 'Courier New', Courier, monospace;
        }

        .hacker-box {
            border: 1px solid #30363d;
            background: #161b22;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        .hacker-input {
            background: #0d1117;
            border: 1px solid #30363d;
            color: #58a6ff;
        }

        .hacker-btn {
            background: #238636;
            color: white;
            border: 1px solid rgba(240, 246, 252, 0.1);
        }

        .hacker-btn:hover {
            background: #2ea043;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">

    <div class="hacker-box p-8 rounded-lg w-full max-w-sm text-center">
        <h1 class="text-2xl font-bold mb-2 text-green-500">System Override</h1>
        <p class="text-xs text-gray-500 mb-6">Restricted Access // Level 5 Clearance</p>

        @if(session('error'))
            <div class="mb-4 text-red-500 text-sm font-bold animate-pulse">
                [ACCESS DENIED] {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('dev.tools.auth') }}" method="POST">
            @csrf

            <div class="mb-4">
                <input type="password" name="password"
                    class="hacker-input w-full p-2 rounded focus:outline-none focus:ring-1 focus:ring-green-500 placeholder-gray-700 font-bold"
                    placeholder="Enter Passkey...">
            </div>

            <button type="submit" class="hacker-btn w-full py-2 rounded font-bold uppercase text-sm tracking-wider">
                Unlock Console
            </button>
        </form>
    </div>

</body>

</html>