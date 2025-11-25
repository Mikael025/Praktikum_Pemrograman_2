@props(['years' => [], 'showStatus' => false])

<form method="GET" class="flex flex-wrap gap-2 items-center">
    <select name="year" class="rounded-md border-gray-300" onchange="this.form.submit()">
        <option value="">Semua Tahun</option>
        @foreach($years as $year)
            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
        @endforeach
    </select>
    @if($showStatus)
        <select name="status" class="rounded-md border-gray-300" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="diusulkan" {{ request('status') === 'diusulkan' ? 'selected' : '' }}>Diusulkan</option>
            <option value="tidak_lolos" {{ request('status') === 'tidak_lolos' ? 'selected' : '' }}>Tidak Lolos</option>
            <option value="lolos_perlu_revisi" {{ request('status') === 'lolos_perlu_revisi' ? 'selected' : '' }}>Lolos Perlu Revisi</option>
            <option value="lolos" {{ request('status') === 'lolos' ? 'selected' : '' }}>Lolos</option>
            <option value="revisi_pra_final" {{ request('status') === 'revisi_pra_final' ? 'selected' : '' }}>Revisi Pra Final</option>
            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        <div class="relative flex-1 min-w-[220px]">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full rounded-md border-gray-300 pl-9" />
            <span class="absolute left-2 top-2.5 text-gray-400">🔎</span>
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">Filter</button>
    @endif
    @if(request()->hasAny(['year', 'status', 'search']))
        <a href="{{ url()->current() }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium">Reset</a>
    @endif
</form>


