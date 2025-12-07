<!-- Version History Modal -->
<div id="versionHistoryModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeVersionHistory()"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Riwayat Versi Dokumen</h3>
                    <button onclick="closeVersionHistory()" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Loading State -->
                <div id="versionLoading" class="text-center py-8">
                    <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-2 text-gray-600">Memuat riwayat versi...</p>
                </div>

                <!-- Version List -->
                <div id="versionList" class="hidden">
                    <!-- Current Version -->
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Versi Terkini
                        </h4>
                        <div id="currentVersion" class="bg-green-50 border border-green-200 rounded-lg p-4"></div>
                    </div>

                    <!-- Previous Versions -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Versi Sebelumnya</h4>
                        <div id="previousVersions" class="space-y-3"></div>
                    </div>

                    <!-- Empty State -->
                    <div id="noVersions" class="hidden text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500">Belum ada versi sebelumnya</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showVersionHistory(documentId, type) {
        document.getElementById('versionHistoryModal').classList.remove('hidden');
        document.getElementById('versionLoading').classList.remove('hidden');
        document.getElementById('versionList').classList.add('hidden');
        
        // Fetch version history
        fetch(`/documents/${type}/${documentId}/versions`)
            .then(response => response.json())
            .then(data => {
                renderVersionHistory(data);
            })
            .catch(error => {
                console.error('Error fetching version history:', error);
                alert('Gagal memuat riwayat versi');
                closeVersionHistory();
            });
    }
    
    function renderVersionHistory(data) {
        document.getElementById('versionLoading').classList.add('hidden');
        document.getElementById('versionList').classList.remove('hidden');
        
        // Render current version
        const currentHTML = `
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">${data.current.nama_file}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Version ${data.current.version} • ${data.current.uploaded_at}
                    </p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mt-2 ${getStatusClass(data.current.verification_status)}">
                        ${getStatusLabel(data.current.verification_status)}
                    </span>
                </div>
                <div class="flex space-x-2">
                    <button onclick="previewDocument('${data.current.path_file}', '${data.current.nama_file}')" 
                        class="px-3 py-1.5 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                        Preview
                    </button>
                    <a href="${data.current.path_file}" download 
                        class="px-3 py-1.5 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                        Download
                    </a>
                </div>
            </div>
        `;
        document.getElementById('currentVersion').innerHTML = currentHTML;
        
        // Render previous versions
        const previousVersionsEl = document.getElementById('previousVersions');
        const noVersionsEl = document.getElementById('noVersions');
        
        if (data.versions && data.versions.length > 0) {
            previousVersionsEl.innerHTML = data.versions.map(v => `
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">${v.nama_file}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Version ${v.version} • ${v.uploaded_at} • by ${v.uploaded_by}
                            </p>
                            ${v.change_notes ? `
                                <p class="text-xs text-gray-600 mt-2 italic">
                                    <strong>Catatan:</strong> ${v.change_notes}
                                </p>
                            ` : ''}
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="previewDocument('${v.path_file}', '${v.nama_file}')" 
                                class="px-3 py-1.5 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                Preview
                            </button>
                            <a href="${v.path_file}" download 
                                class="px-3 py-1.5 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            `).join('');
            noVersionsEl.classList.add('hidden');
        } else {
            previousVersionsEl.innerHTML = '';
            noVersionsEl.classList.remove('hidden');
        }
    }
    
    function closeVersionHistory() {
        document.getElementById('versionHistoryModal').classList.add('hidden');
    }
    
    function getStatusClass(status) {
        const classes = {
            'verified': 'bg-green-100 text-green-800',
            'pending': 'bg-yellow-100 text-yellow-800',
            'rejected': 'bg-red-100 text-red-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    }
    
    function getStatusLabel(status) {
        const labels = {
            'verified': 'Terverifikasi',
            'pending': 'Menunggu Verifikasi',
            'rejected': 'Ditolak'
        };
        return labels[status] || status;
    }
</script>
