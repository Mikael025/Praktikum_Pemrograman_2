<x-layouts.dosen>
    <div class="space-y-6">
        <!-- Welcome -->
        <div class="bg-white rounded-2xl shadow-md p-4 md:p-6 border border-slate-100">
            <h1 class="text-xl md:text-3xl font-bold text-slate-900">Selamat Datang, {{ auth()->user()->name ?? 'Dosen' }}</h1>
            <p class="mt-2 text-sm md:text-base text-slate-600">Ringkasan aktivitas penelitian dan pengabdian Anda ditampilkan di bawah.</p>
        </div>

        <!-- Action Required Widget -->
        @if($actionRequired['needs_revision'] > 0 || $actionRequired['missing_documents'] > 0)
        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-xl shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-amber-900">Perhatian Diperlukan!</h3>
                    <div class="mt-2 text-sm text-amber-800 space-y-1">
                        @if($actionRequired['needs_revision'] > 0)
                        <p>• <strong>{{ $actionRequired['needs_revision'] }}</strong> kegiatan memerlukan revisi</p>
                        @endif
                        @if($actionRequired['missing_documents'] > 0)
                        <p>• <strong>{{ $actionRequired['missing_documents'] }}</strong> kegiatan belum memiliki dokumen lengkap</p>
                        @endif
                    </div>
                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('dosen.penelitian.index') }}" class="text-sm font-medium text-amber-900 hover:text-amber-950 underline">
                            Lihat Penelitian →
                        </a>
                        <a href="{{ route('dosen.pengabdian.index') }}" class="text-sm font-medium text-amber-900 hover:text-amber-950 underline">
                            Lihat Pengabdian →
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Action Buttons -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 md:gap-3">
            <a href="{{ route('dosen.penelitian.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md hover:shadow-lg p-3 md:p-4 flex flex-col items-center justify-center transition-all transform hover:scale-105">
                <svg class="w-6 md:w-8 h-6 md:h-8 mb-1 md:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span class="font-semibold text-xs md:text-sm text-center">Penelitian Baru</span>
            </a>
            <a href="{{ route('dosen.pengabdian.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md hover:shadow-lg p-3 md:p-4 flex flex-col items-center justify-center transition-all transform hover:scale-105">
                <svg class="w-6 md:w-8 h-6 md:h-8 mb-1 md:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span class="font-semibold text-xs md:text-sm text-center">Pengabdian Baru</span>
            </a>
            <a href="{{ route('dosen.laporan.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md hover:shadow-lg p-3 md:p-4 flex flex-col items-center justify-center transition-all transform hover:scale-105">
                <svg class="w-6 md:w-8 h-6 md:h-8 mb-1 md:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="font-semibold text-xs md:text-sm text-center">Lihat Laporan</span>
            </a>
            <a href="{{ route('dosen.laporan.perbandingan') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md hover:shadow-lg p-3 md:p-4 flex flex-col items-center justify-center transition-all transform hover:scale-105">
                <svg class="w-6 md:w-8 h-6 md:h-8 mb-1 md:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="font-semibold text-xs md:text-sm text-center">Perbandingan</span>
            </a>
        </div>

        <!-- Enhanced Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-2xl shadow-lg p-4 md:p-6 border border-indigo-400/20">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex-1">
                        <p class="text-indigo-100 text-xs md:text-sm font-medium">Total Kegiatan</p>
                        <p class="text-2xl md:text-3xl font-bold mt-2">{{ $enhancedStats['total_kegiatan'] }}</p>
                        <p class="text-indigo-100 text-xs mt-1">{{ $enhancedStats['total_penelitian'] }} Penelitian, {{ $enhancedStats['total_pengabdian'] }} Pengabdian</p>
                    </div>
                    <div class="bg-indigo-400/30 rounded-full p-2 md:p-3 flex-shrink-0">
                        <svg class="w-6 md:w-8 h-6 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl shadow-lg p-4 md:p-6 border border-green-400/20">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex-1">
                        <p class="text-green-100 text-xs md:text-sm font-medium">Tingkat Keberhasilan</p>
                        <p class="text-2xl md:text-3xl font-bold mt-2">{{ $enhancedStats['success_rate'] }}%</p>
                        <p class="text-green-100 text-xs mt-1">Lolos & Selesai</p>
                    </div>
                    <div class="bg-green-400/30 rounded-full p-2 md:p-3 flex-shrink-0">
                        <svg class="w-6 md:w-8 h-6 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-slate-400 to-slate-500 text-white rounded-2xl shadow-lg p-4 md:p-6 border border-slate-300/20">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex-1">
                        <p class="text-slate-100 text-xs md:text-sm font-medium">Tingkat Penyelesaian</p>
                        <p class="text-2xl md:text-3xl font-bold mt-2">{{ $enhancedStats['completion_rate'] }}%</p>
                        <p class="text-slate-100 text-xs mt-1">Selesai dari Total</p>
                    </div>
                    <div class="bg-slate-300/30 rounded-full p-2 md:p-3 flex-shrink-0">
                        <svg class="w-6 md:w-8 h-6 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-2xl shadow-lg p-4 md:p-6 border border-amber-400/20">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex-1">
                        <p class="text-amber-100 text-xs md:text-sm font-medium">Menunggu Verifikasi</p>
                        <p class="text-2xl md:text-3xl font-bold mt-2">{{ $actionRequired['pending_verification'] }}</p>
                        <p class="text-amber-100 text-xs mt-1">Status: Diusulkan</p>
                    </div>
                    <div class="bg-amber-400/30 rounded-full p-2 md:p-3 flex-shrink-0">
                        <svg class="w-6 md:w-8 h-6 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-100">
            <div class="px-4 md:px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Aktivitas Terkini</h2>
            </div>
            <div class="p-3 md:p-6">
                @if($recentActivities->count() > 0)
                <div class="space-y-2 md:space-y-3">
                    @foreach($recentActivities as $activity)
                    <div class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-3 p-3 hover:bg-slate-50 rounded-lg transition-colors">
                        <div class="flex-shrink-0 flex items-start gap-2 sm:gap-3 w-full sm:w-auto">
                            <div class="flex-shrink-0 mt-1">
                                @if($activity['type'] === 'penelitian')
                                <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                                @else
                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs md:text-sm font-medium text-slate-900 truncate">{{ $activity['title'] }}</p>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $activity['type'] === 'penelitian' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ ucfirst($activity['type']) }}
                                    </span>
                                    <x-status-badge :status="$activity['status']" />
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-right flex items-center justify-between sm:flex-col sm:gap-1">
                            <p class="text-xs text-slate-500">{{ $activity['updated_at']->diffForHumans() }}</p>
                            <a href="{{ route('dosen.' . $activity['type'] . '.show', $activity['id']) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                Lihat →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-slate-500 py-8">Belum ada aktivitas</p>
                @endif
            </div>
        </div>

    </div>
</x-layouts.dosen>


