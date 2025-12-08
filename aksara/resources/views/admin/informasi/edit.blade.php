<x-layouts.admin>
    <div class="space-y-6">
        <h1 class="text-xl font-semibold text-gray-900">Edit Informasi</h1>
        <form method="POST" action="{{ route('admin.informasi.update', $informasi->slug) }}" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4">
            @method('PUT')
            @include('admin.informasi._form')
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">Update</button>
                <a href="{{ route('admin.informasi.index') }}" class="px-4 py-2 rounded-md bg-gray-100 text-gray-800">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.admin>


