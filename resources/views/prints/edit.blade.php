<x-app-layout>
<div class="p-6 max-w-xl">

    <h1 class="text-xl font-bold mb-4">Update Status Print</h1>

    <form action="{{ route('prints.update', $print->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="font-semibold">Status</label>
            <select name="status" class="w-full border rounded p-2">
                <option value="pending"   {{ $print->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="printing"  {{ $print->status == 'printing' ? 'selected' : '' }}>Printing</option>
                <option value="done"      {{ $print->status == 'done' ? 'selected' : '' }}>Done</option>
                <option value="canceled"  {{ $print->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Update
        </button>
    </form>

</div>
</x-app-layout>
