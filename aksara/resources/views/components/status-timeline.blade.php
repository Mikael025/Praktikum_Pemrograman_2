@props(['history'])

<div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Status</h3>
    
    @if($history->count() > 0)
        <div class="relative">
            <!-- Vertical line -->
            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-300"></div>
            
            <!-- Timeline items -->
            <div class="space-y-6">
                @foreach($history as $entry)
                    <div class="relative pl-10">
                        <!-- Timeline dot -->
                        <div class="absolute left-0 top-1.5 w-8 h-8 rounded-full flex items-center justify-center
                            @if($entry->new_status === 'tidak_lolos') bg-red-500
                            @elseif($entry->new_status === 'lolos_perlu_revisi') bg-yellow-500
                            @elseif($entry->new_status === 'lolos') bg-green-500
                            @elseif($entry->new_status === 'revisi_pra_final') bg-orange-500
                            @elseif($entry->new_status === 'selesai') bg-blue-500
                            @else bg-gray-400
                            @endif">
                            @if($entry->new_status === 'tidak_lolos')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @elseif($entry->new_status === 'lolos')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @elseif($entry->new_status === 'selesai')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                        
                        <!-- Timeline content -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    @if($entry->old_status)
                                        <x-status-badge :status="$entry->old_status" />
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    @endif
                                    <x-status-badge :status="$entry->new_status" />
                                </div>
                                <span class="text-xs text-gray-500">
                                    {{ $entry->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            
                            @if($entry->notes)
                                <p class="text-sm text-gray-700 mb-2">
                                    <span class="font-medium">Catatan:</span> {{ $entry->notes }}
                                </p>
                            @endif
                            
                            @if($entry->changedBy)
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Diubah oleh: {{ $entry->changedBy->name }}
                                    @if($entry->changedBy->role === 'admin')
                                        <span class="ml-1 px-1.5 py-0.5 bg-purple-100 text-purple-700 rounded text-xs">Admin</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p>Belum ada riwayat perubahan status</p>
        </div>
    @endif
</div>
