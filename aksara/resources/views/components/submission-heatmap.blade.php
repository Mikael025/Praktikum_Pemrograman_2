@props(['year' => null])

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Submission Activity Heatmap</h3>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-600">Year:</span>
            <span class="text-sm font-bold text-indigo-600">{{ $year ?? date('Y') }}</span>
        </div>
    </div>

    <!-- Legend -->
    <div class="flex items-center justify-end space-x-4 mb-4 text-xs text-gray-600">
        <span>Less</span>
        <div class="flex space-x-1">
            <div class="w-4 h-4 bg-gray-100 border border-gray-300 rounded"></div>
            <div class="w-4 h-4 bg-green-200 border border-green-300 rounded"></div>
            <div class="w-4 h-4 bg-green-400 border border-green-500 rounded"></div>
            <div class="w-4 h-4 bg-green-600 border border-green-700 rounded"></div>
            <div class="w-4 h-4 bg-green-800 border border-green-900 rounded"></div>
        </div>
        <span>More</span>
    </div>

    <div id="heatmap-container" class="overflow-x-auto">
        <!-- Loading state -->
        <div class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            <p class="mt-2 text-sm text-gray-500">Loading activity data...</p>
        </div>
    </div>

    <!-- Stats Summary -->
    <div id="heatmap-stats" class="mt-4 pt-4 border-t border-gray-200">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <p class="text-xs text-gray-500">Total Submissions</p>
                <p id="total-submissions" class="text-lg font-bold text-indigo-600">-</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Peak Day</p>
                <p id="peak-day" class="text-sm font-semibold text-gray-900">-</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Avg per Day</p>
                <p id="avg-per-day" class="text-lg font-bold text-gray-700">-</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const year = {{ $year ?? 'null' }} || new Date().getFullYear();
    const container = document.getElementById('heatmap-container');
    
    fetch(`/api/submission-heatmap?year=${year}`)
        .then(response => response.json())
        .then(result => {
            const data = result.data;
            
            if (data.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p>No submission data for ${year}</p>
                    </div>
                `;
                return;
            }

            // Create date map
            const dataMap = {};
            data.forEach(item => {
                dataMap[item.date] = item.count;
            });

            // Generate calendar grid
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            let html = '<div class="grid grid-cols-12 gap-2">';
            
            const maxCount = Math.max(...data.map(d => d.count));
            
            months.forEach((month, monthIndex) => {
                const daysInMonth = new Date(year, monthIndex + 1, 0).getDate();
                
                html += `<div class="text-center">
                    <div class="text-xs font-medium text-gray-600 mb-2">${month}</div>
                    <div class="grid grid-rows-5 gap-1">`;
                
                for (let day = 1; day <= daysInMonth; day++) {
                    const date = `${year}-${String(monthIndex + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const count = dataMap[date] || 0;
                    const intensity = count === 0 ? 0 : Math.ceil((count / maxCount) * 4);
                    
                    const colors = [
                        'bg-gray-100 border-gray-300',
                        'bg-green-200 border-green-300',
                        'bg-green-400 border-green-500',
                        'bg-green-600 border-green-700',
                        'bg-green-800 border-green-900'
                    ];
                    
                    html += `<div class="w-3 h-3 ${colors[intensity]} border rounded cursor-pointer hover:scale-150 transition-transform" 
                                  title="${date}: ${count} submission(s)" 
                                  data-date="${date}" 
                                  data-count="${count}"></div>`;
                }
                
                html += `</div></div>`;
            });
            
            html += '</div>';
            container.innerHTML = html;

            // Update stats
            const totalSubmissions = data.reduce((sum, item) => sum + item.count, 0);
            const peakItem = data.reduce((max, item) => item.count > max.count ? item : max, data[0]);
            const avgPerDay = (totalSubmissions / 365).toFixed(2);

            document.getElementById('total-submissions').textContent = totalSubmissions;
            document.getElementById('peak-day').textContent = peakItem ? 
                `${new Date(peakItem.date).toLocaleDateString('id-ID', { month: 'short', day: 'numeric' })} (${peakItem.count})` : '-';
            document.getElementById('avg-per-day').textContent = avgPerDay;
        })
        .catch(error => {
            console.error('Error loading heatmap:', error);
            container.innerHTML = `
                <div class="text-center py-12 text-red-500">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>Failed to load heatmap data</p>
                </div>
            `;
        });
});
</script>
