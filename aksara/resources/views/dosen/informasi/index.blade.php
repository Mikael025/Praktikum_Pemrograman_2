<x-layouts.dosen>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900">Informasi/Berita</h1>
        </div>

        <div class="flex gap-2">
            @php($k = request('k'))
            <a href="{{ route('dosen.informasi') }}" class="px-3 py-1 rounded {{ $k ? 'text-gray-500' : 'bg-blue-100 text-blue-800' }}">Semua</a>
            <a href="{{ route('dosen.informasi', ['k' => 'penelitian']) }}" class="px-3 py-1 rounded {{ $k==='penelitian' ? 'bg-blue-100 text-blue-800' : 'text-gray-500' }}">Penelitian</a>
            <a href="{{ route('dosen.informasi', ['k' => 'pengabdian']) }}" class="px-3 py-1 rounded {{ $k==='pengabdian' ? 'bg-blue-100 text-blue-800' : 'text-gray-500' }}">Pengabdian</a>
            <a href="{{ route('dosen.informasi', ['k' => 'umum']) }}" class="px-3 py-1 rounded {{ $k==='umum' ? 'bg-blue-100 text-blue-800' : 'text-gray-500' }}">Umum</a>
        </div>

        <div class="bg-white shadow rounded-lg divide-y divide-gray-200">
            @forelse($informasi as $info)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <div class="font-medium text-gray-900">{{ $info->title }}</div>
                        <div class="text-sm text-gray-500">{{ optional($info->published_at)->format('d M Y') }} • {{ ucfirst($info->category) }}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('dosen.informasi.show', $info->slug) }}" class="text-blue-600 hover:underline">Selengkapnya</a>
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
</x-layouts.dosen>


