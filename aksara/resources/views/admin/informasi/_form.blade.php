@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul</label>
        <input type="text" name="title" value="{{ old('title', $informasi->title) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('title')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug (opsional)</label>
        <input type="text" name="slug" value="{{ old('slug', $informasi->slug) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('slug')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
        <select name="category" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            @php($cats = ['penelitian' => 'Penelitian', 'pengabdian' => 'Pengabdian', 'umum' => 'Umum'])
            @foreach($cats as $val => $label)
                <option value="{{ $val }}" {{ old('category', $informasi->category) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('category')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visibilitas</label>
        <select name="visibility" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            @php($vis = ['semua' => 'Semua', 'admin' => 'Admin', 'dosen' => 'Dosen'])
            @foreach($vis as $val => $label)
                <option value="{{ $val }}" {{ old('visibility', $informasi->visibility) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('visibility')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Publikasi</label>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($informasi->published_at)->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        @error('published_at')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konten</label>
        <textarea name="content" rows="8" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('content', $informasi->content) }}</textarea>
        @error('content')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
</div>


