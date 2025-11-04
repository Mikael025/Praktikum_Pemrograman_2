<x-layouts.admin>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Informasi/Berita</h1>
            <a href="{{ route('admin.informasi.create') }}" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium bg-blue-600 text-white hover:bg-blue-700">Tambah</a>
        </div>

        <div class="flex gap-2">
            @php($k = request('k'))
            <a href="{{ route('admin.informasi.index') }}" class="px-3 py-1 rounded {{ $k ? 'text-gray-500' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' }}">Semua</a>
            <a href="{{ route('admin.informasi.index', ['k' => 'penelitian']) }}" class="px-3 py-1 rounded {{ $k==='penelitian' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'text-gray-500' }}">Penelitian</a>
            <a href="{{ route('admin.informasi.index', ['k' => 'pengabdian']) }}" class="px-3 py-1 rounded {{ $k==='pengabdian' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'text-gray-500' }}">Pengabdian</a>
            <a href="{{ route('admin.informasi.index', ['k' => 'umum']) }}" class="px-3 py-1 rounded {{ $k==='umum' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'text-gray-500' }}">Umum</a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($informasi as $info)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $info->title }}</div>
                        <div class="text-sm text-gray-500">{{ optional($info->published_at)->format('d M Y') }} • {{ ucfirst($info->category) }} • {{ ucfirst($info->visibility) }}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.informasi.show', $info->slug) }}" class="text-blue-600 hover:underline">Lihat</a>
                        <a href="{{ route('admin.informasi.edit', $info->slug) }}" class="text-gray-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.informasi.destroy', $info->slug) }}" onsubmit="return confirm('Hapus informasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-6 text-gray-500">Belum ada informasi.</div>
            @endforelse
        </div>

        <div>
            {{ $informasi->links() }}
        </div>
    </div>
</x-layouts.admin>


