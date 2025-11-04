<x-layouts.admin>
    <div class="space-y-6">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Informasi</h1>
        <form method="POST" action="{{ route('admin.informasi.update', $informasi->slug) }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
            @method('PUT')
            @include('admin.informasi._form')
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">Update</button>
                <a href="{{ route('admin.informasi.index') }}" class="px-4 py-2 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.admin>


