@props(['progress', 'showDetails' => false])

@php
    $total = $progress['total'];
    $color = match(true) {
        $total >= 80 => 'green',
        $total >= 50 => 'blue',
        $total >= 30 => 'yellow',
        default => 'red'
    };
@endphp

<div class="space-y-3">
    <!-- Main Progress Bar -->
    <div>
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Progress Penyelesaian</span>
            <span class="text-sm font-bold 
                @if($color === 'green') text-green-600
                @elseif($color === 'blue') text-blue-600
                @elseif($color === 'yellow') text-yellow-600
                @else text-red-600
                @endif">
                {{ $total }}%
            </span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
            <div class="h-3 rounded-full transition-all duration-500
                @if($color === 'green') bg-gradient-to-r from-green-500 to-green-600
                @elseif($color === 'blue') bg-gradient-to-r from-blue-500 to-blue-600
                @elseif($color === 'yellow') bg-gradient-to-r from-yellow-500 to-yellow-600
                @else bg-gradient-to-r from-red-500 to-red-600
                @endif"
                style="width: {{ $total }}%">
            </div>
        </div>
    </div>

    <!-- Detailed Breakdown -->
    @if($showDetails)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2">
            <!-- Status Progress -->
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-700">Status</span>
                    </div>
                    <span class="text-xs font-bold text-purple-600">{{ $progress['status'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-purple-600 h-1.5 rounded-full transition-all duration-300" style="width: {{ $progress['status'] }}%"></div>
                </div>
            </div>

            <!-- Documents Progress -->
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-700">Dokumen</span>
                    </div>
                    <span class="text-xs font-bold text-indigo-600">{{ $progress['documents'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-300" style="width: {{ $progress['documents'] }}%"></div>
                </div>
            </div>

            <!-- Feedback Progress -->
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-700">Feedback</span>
                    </div>
                    <span class="text-xs font-bold text-emerald-600">{{ $progress['feedback'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-emerald-600 h-1.5 rounded-full transition-all duration-300" style="width: {{ $progress['feedback'] }}%"></div>
                </div>
            </div>
        </div>
    @endif
</div>
