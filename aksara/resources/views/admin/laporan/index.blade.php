<x-layouts.admin>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Laporan & Rekap Kegiatan</h1>
                <p class="mt-1 text-sm text-gray-600">Rekap kegiatan penelitian dan pengabdian seluruh dosen</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.laporan.perbandingan') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Perbandingan
                </a>
                <a href="{{ route('admin.laporan.export-csv', request()->query()) }}" 
                   class="inline-flex items-center px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}" 
                   class="inline-flex items-center px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow p-4">
            <form method="GET" class="flex flex-wrap gap-2 items-center">
                <select name="dosen_id" class="rounded-md border-gray-300" onchange="this.form.submit()">
                    <option value="">Semua Dosen</option>
                    @foreach($dosenList as $dosen)
                        <option value="{{ $dosen->id }}" {{ request('dosen_id') == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
                    @endforeach
                </select>
                <select name="year" class="rounded-md border-gray-300" onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    @foreach(range((int)date('Y'), (int)date('Y')-10) as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <select name="status" class="rounded-md border-gray-300" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="diusulkan" {{ request('status') === 'diusulkan' ? 'selected' : '' }}>Diusulkan</option>
                    <option value="tidak_lolos" {{ request('status') === 'tidak_lolos' ? 'selected' : '' }}>Tidak Lolos</option>
                    <option value="lolos_perlu_revisi" {{ request('status') === 'lolos_perlu_revisi' ? 'selected' : '' }}>Lolos Perlu Revisi</option>
                    <option value="lolos" {{ request('status') === 'lolos' ? 'selected' : '' }}>Lolos</option>
                    <option value="revisi_pra_final" {{ request('status') === 'revisi_pra_final' ? 'selected' : '' }}>Revisi Pra Final</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @if(request()->hasAny(['year', 'status', 'dosen_id']))
                    <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium">Reset</a>
                @endif
            </form>
        </div>

        <!-- Statistik Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500">Total Penelitian</h3>
                <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $stats['total_penelitian'] }}</p>
                <p class="mt-1 text-xs text-gray-600">Diusulkan: {{ $stats['penelitian_diusulkan'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500">Total Pengabdian</h3>
                <p class="mt-2 text-3xl font-bold text-green-600">{{ $stats['total_pengabdian'] }}</p>
                <p class="mt-1 text-xs text-gray-600">Diusulkan: {{ $stats['pengabdian_diusulkan'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500">Lolos Verifikasi</h3>
                <p class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['penelitian_lolos'] + $stats['pengabdian_lolos'] }}</p>
                <p class="mt-1 text-xs text-gray-600">P: {{ $stats['penelitian_lolos'] }} | PM: {{ $stats['pengabdian_lolos'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500">Selesai</h3>
                <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $stats['penelitian_selesai'] + $stats['pengabdian_selesai'] }}</p>
                <p class="mt-1 text-xs text-gray-600">P: {{ $stats['penelitian_selesai'] }} | PM: {{ $stats['pengabdian_selesai'] }}</p>
            </div>
        </div>

        <!-- Top Dosen Produktif -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Top 10 Dosen Produktif</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ranking</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Dosen</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Penelitian</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Pengabdian</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Kegiatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($topDosen as $index => $dosen)
                            <tr>
                                <td class="px-4 py-3">
                                    @if($index == 0)
                                        <span class="text-2xl">🥇</span>
                                    @elseif($index == 1)
                                        <span class="text-2xl">🥈</span>
                                    @elseif($index == 2)
                                        <span class="text-2xl">🥉</span>
                                    @else
                                        <span class="text-gray-600">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-900 font-medium">{{ $dosen->name }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $dosen->penelitian_count }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $dosen->pengabdian_count }}</td>
                                <td class="px-4 py-3 text-gray-900 font-bold">{{ $dosen->penelitian_count + $dosen->pengabdian_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Penelitian -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Penelitian</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($penelitian as $index => $item)
                            <tr>
                                <td class="px-4 py-3 text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ Str::limit($item->judul, 50) }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $item->user->name }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $item->tahun }}</td>
                                <td class="px-4 py-3"><x-status-badge :status="$item->status" /></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data penelitian</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Pengabdian -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Pengabdian Masyarakat</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($pengabdian as $index => $item)
                            <tr>
                                <td class="px-4 py-3 text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ Str::limit($item->judul, 50) }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $item->user->name }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $item->tahun }}</td>
                                <td class="px-4 py-3"><x-status-badge :status="$item->status" /></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data pengabdian</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>
