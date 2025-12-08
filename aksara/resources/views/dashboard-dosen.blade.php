<x-layouts.dosen>
    <div class="space-y-6">
        <!-- Welcome -->
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-900">Selamat Datang, {{ auth()->user()->name ?? 'Dosen' }}</h1>
            <p class="mt-2 text-gray-600">Ringkasan aktivitas penelitian dan pengabdian Anda ditampilkan di bawah.</p>
        </div>

        <!-- Action Required Widget -->
        @if($actionRequired['needs_revision'] > 0 || $actionRequired['missing_documents'] > 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-yellow-800">Perhatian Diperlukan!</h3>
                    <div class="mt-2 text-sm text-yellow-700 space-y-1">
                        @if($actionRequired['needs_revision'] > 0)
                        <p>• <strong>{{ $actionRequired['needs_revision'] }}</strong> kegiatan memerlukan revisi</p>
                        @endif
                        @if($actionRequired['missing_documents'] > 0)
                        <p>• <strong>{{ $actionRequired['missing_documents'] }}</strong> kegiatan belum memiliki dokumen lengkap</p>
                        @endif
                    </div>
                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('dosen.penelitian.index') }}" class="text-sm font-medium text-yellow-800 hover:text-yellow-900 underline">
                            Lihat Penelitian →
                        </a>
                        <a href="{{ route('dosen.pengabdian.index') }}" class="text-sm font-medium text-yellow-800 hover:text-yellow-900 underline">
                            Lihat Pengabdian →
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Action Buttons -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('dosen.penelitian.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow p-4 flex flex-col items-center justify-center transition-colors">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span class="font-semibold text-sm">Penelitian Baru</span>
            </a>
            <a href="{{ route('dosen.pengabdian.create') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-lg shadow p-4 flex flex-col items-center justify-center transition-colors">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span class="font-semibold text-sm">Pengabdian Baru</span>
            </a>
            <a href="{{ route('dosen.laporan.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow p-4 flex flex-col items-center justify-center transition-colors">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="font-semibold text-sm">Lihat Laporan</span>
            </a>
            <a href="{{ route('dosen.laporan.perbandingan') }}" class="bg-orange-600 hover:bg-orange-700 text-white rounded-lg shadow p-4 flex flex-col items-center justify-center transition-colors">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="font-semibold text-sm">Perbandingan</span>
            </a>
        </div>

        <!-- Enhanced Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Kegiatan</p>
                        <p class="text-3xl font-bold mt-2">{{ $enhancedStats['total_kegiatan'] }}</p>
                        <p class="text-blue-100 text-xs mt-1">{{ $enhancedStats['total_penelitian'] }} Penelitian, {{ $enhancedStats['total_pengabdian'] }} Pengabdian</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Tingkat Keberhasilan</p>
                        <p class="text-3xl font-bold mt-2">{{ $enhancedStats['success_rate'] }}%</p>
                        <p class="text-green-100 text-xs mt-1">Lolos & Selesai</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Tingkat Penyelesaian</p>
                        <p class="text-3xl font-bold mt-2">{{ $enhancedStats['completion_rate'] }}%</p>
                        <p class="text-purple-100 text-xs mt-1">Selesai dari Total</p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Menunggu Verifikasi</p>
                        <p class="text-3xl font-bold mt-2">{{ $actionRequired['pending_verification'] }}</p>
                        <p class="text-yellow-100 text-xs mt-1">Status: Diusulkan</p>
                    </div>
                    <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Aktivitas Terkini</h2>
            </div>
            <div class="p-6">
                @if($recentActivities->count() > 0)
                <div class="space-y-3">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex-shrink-0 mt-1">
                            @if($activity['type'] === 'penelitian')
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            @else
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $activity['title'] }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $activity['type'] === 'penelitian' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($activity['type']) }}
                                </span>
                                <x-status-badge :status="$activity['status']" />
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <p class="text-xs text-gray-500">{{ $activity['updated_at']->diffForHumans() }}</p>
                            <a href="{{ route('dosen.' . $activity['type'] . '.show', $activity['id']) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                Lihat →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-gray-500 py-8">Belum ada aktivitas</p>
                @endif
            </div>
        </div>

    </div>
</x-layouts.dosen>


