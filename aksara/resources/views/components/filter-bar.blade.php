@props(['years' => [], 'showStatus' => false])

<div class="flex flex-wrap gap-2 items-center">
    <select class="rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100">
        <option value="">Semua Tahun</option>
        @foreach($years as $year)
            <option value="{{ $year }}">{{ $year }}</option>
        @endforeach
    </select>
    @if($showStatus)
        <select class="rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100">
            <option value="">Semua Status</option>
            <option>Aktif</option>
            <option>Selesai</option>
            <option>Menunggu Verifikasi</option>
            <option>Ditolak</option>
        </select>
    @endif
    <div class="relative flex-1 min-w-[220px]">
        <input type="search" placeholder="Cari..." class="w-full rounded-md border-gray-300 pl-9 dark:bg-gray-800 dark:text-gray-100" />
        <span class="absolute left-2 top-2.5 text-gray-400">🔎</span>
    </div>
</div>


