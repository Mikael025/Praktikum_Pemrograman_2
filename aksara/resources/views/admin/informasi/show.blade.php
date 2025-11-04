<x-layouts.admin>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $informasi->title }}</h1>
            <a href="{{ route('admin.informasi.index') }}" class="text-sm text-blue-600 hover:underline">Kembali</a>
        </div>
        <div class="text-sm text-gray-500">{{ optional($informasi->published_at)->format('d M Y') }} • {{ ucfirst($informasi->category) }} • {{ ucfirst($informasi->visibility) }}</div>
        <div class="prose dark:prose-invert max-w-none">
            {!! nl2br(e($informasi->content)) !!}
        </div>
    </div>
</x-layouts.admin>


