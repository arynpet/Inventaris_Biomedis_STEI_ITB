<!DOCTYPE html>
<html>

<head>
    <title>Debug Remote Upload</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body>
    <div x-data="{
        testUrl: '',
        log: []
    }" class="p-8">
        <h1 class="text-2xl font-bold mb-4">Debug Remote Upload Event</h1>

        <!-- Input yang mendengarkan event -->
        <div class="mb-4">
            <label class="block mb-2">Image URL Input (Listening to event)</label>
            <input type="url" name="image_url" x-model="testUrl" @remote-image-selected.window="
                    log.push('Event received at: ' + new Date().toLocaleTimeString());
                    log.push('URL: ' + $event.detail.url);
                    $el.value = $event.detail.url;
                    testUrl = $event.detail.url;
                " class="border p-2 w-full" placeholder="Will be filled by remote upload event">
        </div>

        <!-- Tombol trigger manual -->
        <div class="mb-4">
            <button @click="$dispatch('open-remote-upload')" class="bg-purple-600 text-white px-4 py-2 rounded">
                Open Remote Upload Modal
            </button>
        </div>

        <!-- Tombol untuk test dispatch manual -->
        <div class="mb-4">
            <button
                @click="$dispatch('remote-image-selected', { url: 'http://localhost:8000/storage/temp/test_123.jpg' })"
                class="bg-green-600 text-white px-4 py-2 rounded">
                Test Dispatch Event (Manual)
            </button>
        </div>

        <!-- Log display -->
        <div class="bg-gray-100 p-4 rounded">
            <h3 class="font-bold mb-2">Event Log:</h3>
            <div x-show="log.length === 0" class="text-gray-500">No events yet...</div>
            <template x-for="(item, index) in log" :key="index">
                <div class="text-sm" x-text="item"></div>
            </template>
        </div>

        <!-- Current value display -->
        <div class="mt-4 p-4 border rounded">
            <strong>Current testUrl value:</strong>
            <pre x-text="testUrl || '(empty)'"></pre>
        </div>
    </div>

    <!-- Include the actual modal component -->
    @include('components.remote-upload-modal')
</body>

</html>