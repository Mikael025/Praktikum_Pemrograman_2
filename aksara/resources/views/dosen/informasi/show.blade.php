<x-layouts.dosen>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900">{{ $informasi->title }}</h1>
            <a href="{{ route('dosen.informasi') }}" class="text-sm text-blue-600 hover:underline">Kembali</a>
        </div>
        @if($informasi->image_path)
            <div>
                <img src="{{ asset('storage/'.$informasi->image_path) }}" alt="{{ $informasi->title }}" class="w-full max-h-80 object-cover rounded">
            </div>
        @endif
        <div class="text-sm text-gray-500">{{ optional($informasi->published_at)->format('d M Y') }} • {{ ucfirst($informasi->category) }}</div>
        <div class="prose max-w-none">
            {!! nl2br(e($informasi->content)) !!}
        </div>
    </div>
</x-layouts.dosen>


