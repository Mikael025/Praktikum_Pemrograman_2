@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Judul</label>
        <input type="text" name="title" value="{{ old('title', $informasi->title) }}" class="mt-1 block w-full rounded-md border-gray-300">
        @error('title')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Slug (opsional)</label>
        <input type="text" name="slug" value="{{ old('slug', $informasi->slug) }}" class="mt-1 block w-full rounded-md border-gray-300">
        @error('slug')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Gambar</label>
        <input type="file" name="image" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300" @if(!$informasi->exists) required @endif>
        <p class="text-xs text-gray-500 mt-1">Unggah gambar representatif (maks 2MB).</p>
        @if($informasi->image_path)
            <div class="mt-2">
                <img src="{{ asset('storage/'.$informasi->image_path) }}" alt="Gambar" class="h-24 w-36 object-cover rounded">
            </div>
        @endif
        @error('image')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Kategori</label>
        <select name="category" class="mt-1 block w-full rounded-md border-gray-300">
            @php($cats = ['penelitian' => 'Penelitian', 'pengabdian' => 'Pengabdian', 'umum' => 'Umum'])
            @foreach($cats as $val => $label)
                <option value="{{ $val }}" {{ old('category', $informasi->category) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('category')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Visibilitas</label>
        <select name="visibility" class="mt-1 block w-full rounded-md border-gray-300">
            @php($vis = ['semua' => 'Semua', 'admin' => 'Admin', 'dosen' => 'Dosen'])
            @foreach($vis as $val => $label)
                <option value="{{ $val }}" {{ old('visibility', $informasi->visibility) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('visibility')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Publikasi</label>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($informasi->published_at)->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300">
        @error('published_at')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Konten</label>
        <textarea name="content" rows="8" class="mt-1 block w-full rounded-md border-gray-300">{{ old('content', $informasi->content) }}</textarea>
        @error('content')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
</div>


