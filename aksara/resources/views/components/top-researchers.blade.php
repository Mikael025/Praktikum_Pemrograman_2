@props(['year' => null])

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Top Productive Researchers</h3>
        <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
    </div>

    <div id="top-researchers-container" class="space-y-3">
        <!-- Loading state -->
        <div class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            <p class="mt-2 text-sm text-gray-500">Loading researchers...</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const year = {{ $year ?? 'null' }};
    const container = document.getElementById('top-researchers-container');
    
    fetch(`/api/top-researchers?year=${year || ''}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p>No data available</p>
                    </div>
                `;
                return;
            }

            const medals = ['🥇', '🥈', '🥉'];
            let html = '';
            
            data.forEach((researcher, index) => {
                const medal = medals[index] || '';
                const progressColor = researcher.completion_rate >= 80 ? 'bg-green-500' : 
                                     researcher.completion_rate >= 50 ? 'bg-blue-500' : 'bg-yellow-500';
                
                html += `
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex-shrink-0 w-8 text-center text-2xl">
                            ${medal || `<span class="text-gray-400 text-sm font-bold">#${index + 1}</span>`}
                        </div>
                        <div class="flex-1 ml-3">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-sm font-medium text-gray-900">${researcher.name}</p>
                                <span class="text-xs font-bold text-indigo-600">${researcher.productivity_score.toFixed(1)} pts</span>
                            </div>
                            <div class="flex items-center space-x-4 text-xs text-gray-500 mb-2">
                                <span>📊 ${researcher.total_activities} total</span>
                                <span>✅ ${researcher.completed_activities} done</span>
                                <span class="font-medium ${researcher.completion_rate >= 80 ? 'text-green-600' : researcher.completion_rate >= 50 ? 'text-blue-600' : 'text-yellow-600'}">
                                    ${researcher.completion_rate}% rate
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="${progressColor} h-1.5 rounded-full transition-all duration-300" style="width: ${researcher.completion_rate}%"></div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading researchers:', error);
            container.innerHTML = `
                <div class="text-center py-8 text-red-500">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>Failed to load data</p>
                </div>
            `;
        });
});
</script>
