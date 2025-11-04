<x-layouts.admin>
    @php($isAdmin = auth()->user() && auth()->user()->role === 'admin')

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Pengabdian Masyarakat</h1>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <x-filter-bar :years="range((int)date('Y'), (int)date('Y')-5)" :show-status="true" />
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mitra</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($pengabdian as $item)
                        <tr>
                            <td class="px-4 py-3 text-gray-900">
                                <div class="max-w-xs truncate" title="{{ $item->judul }}">
                                    {{ $item->judul }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-900">{{ $item->tahun }}</td>
                            <td class="px-4 py-3 text-gray-900">{{ $item->user->name }}</td>
                            <td class="px-4 py-3 text-gray-900">{{ $item->lokasi }}</td>
                            <td class="px-4 py-3 text-gray-900">{{ $item->mitra }}</td>
                            <td class="px-4 py-3">
                                <x-status-badge :status="$item->status" />
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('pengabdian.show', $item) }}" class="text-indigo-600 hover:text-indigo-800">Lihat</a>
                                <a href="{{ route('pengabdian.edit', $item) }}" class="text-yellow-600 hover:text-yellow-700">Edit</a>
                                
                                <!-- Aksi Verifikasi per Status -->
                                @if($item->status === 'diusulkan')
                                    <div class="inline-flex space-x-1">
                                        <form action="{{ route('pengabdian.tidak-lolos', $item) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-700 text-sm" onclick="return confirm('Apakah Anda yakin ingin menolak pengabdian ini?')">Tolak</button>
                                        </form>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('pengabdian.lolos-perlu-revisi', $item) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-700 text-sm" onclick="return confirm('Apakah Anda yakin ingin menyetujui dengan catatan revisi?')">Lolos Revisi</button>
                                        </form>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('pengabdian.lolos', $item) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-700 text-sm" onclick="return confirm('Apakah Anda yakin ingin menyetujui pengabdian ini?')">Lolos</button>
                                        </form>
                                    </div>
                                @elseif($item->status === 'lolos_perlu_revisi')
                                    <form action="{{ route('pengabdian.lolos', $item) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-700 text-sm" onclick="return confirm('Apakah Anda yakin ingin menyetujui pengabdian ini?')">Lolos</button>
                                    </form>
                                @elseif($item->status === 'lolos')
                                    <div class="inline-flex space-x-1">
                                        <form action="{{ route('pengabdian.revisi-pra-final', $item) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-orange-600 hover:text-orange-700 text-sm" onclick="return confirm('Apakah Anda yakin ingin meminta revisi pra-final?')">Revisi Pra-final</button>
                                        </form>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('pengabdian.selesai', $item) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-emerald-600 hover:text-emerald-700 text-sm" onclick="return confirm('Apakah Anda yakin ingin menandai pengabdian ini sebagai selesai?')">Selesai</button>
                                        </form>
                                    </div>
                                @elseif($item->status === 'revisi_pra_final')
                                    <form action="{{ route('pengabdian.selesai', $item) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-700 text-sm" onclick="return confirm('Apakah Anda yakin ingin menandai pengabdian ini sebagai selesai?')">Selesai</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data pengabdian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($pengabdian->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $pengabdian->links() }}
            </div>
            @endif
        </div>
    </div>

</x-layouts.admin>


