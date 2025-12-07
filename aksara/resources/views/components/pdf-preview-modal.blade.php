<!-- PDF Preview Modal -->
<div id="pdfPreviewModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closePdfPreview()"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
            <div class="bg-white">
                <!-- Header -->
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900" id="pdfFileName">
                        Preview Dokumen
                    </h3>
                    <div class="flex items-center space-x-2">
                        <button onclick="downloadCurrentPdf()" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </button>
                        <button onclick="closePdfPreview()" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- PDF Viewer Container -->
                <div class="relative" style="height: 80vh;">
                    <!-- Loading State -->
                    <div id="pdfLoading" class="absolute inset-0 flex items-center justify-center bg-gray-100">
                        <div class="text-center">
                            <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-gray-600">Memuat dokumen...</p>
                        </div>
                    </div>
                    
                    <!-- PDF Canvas -->
                    <div id="pdfViewer" class="hidden overflow-auto h-full bg-gray-100 p-4">
                        <div id="pdfControls" class="sticky top-0 z-10 bg-white border border-gray-300 rounded-lg shadow-sm p-2 mb-4 flex items-center justify-center space-x-4">
                            <button onclick="previousPage()" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <span class="text-sm text-gray-700">
                                Halaman <span id="currentPage">1</span> / <span id="totalPages">1</span>
                            </span>
                            <button onclick="nextPage()" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            <div class="border-l border-gray-300 h-6 mx-2"></div>
                            <button onclick="zoomOut()" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">-</button>
                            <span class="text-sm text-gray-700"><span id="zoomLevel">100</span>%</span>
                            <button onclick="zoomIn()" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">+</button>
                        </div>
                        <canvas id="pdfCanvas" class="mx-auto border border-gray-300 shadow-lg bg-white"></canvas>
                    </div>
                    
                    <!-- Error State -->
                    <div id="pdfError" class="hidden absolute inset-0 flex items-center justify-center bg-gray-100">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-gray-700 mb-2">Tidak dapat memuat dokumen</p>
                            <p class="text-sm text-gray-500">File mungkin tidak tersedia atau bukan format PDF</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    let pdfDoc = null;
    let pageNum = 1;
    let pageRendering = false;
    let pageNumPending = null;
    let scale = 1.5;
    let currentPdfUrl = '';
    
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    
    function previewDocument(url, filename) {
        currentPdfUrl = url;
        document.getElementById('pdfFileName').textContent = filename;
        document.getElementById('pdfPreviewModal').classList.remove('hidden');
        document.getElementById('pdfLoading').classList.remove('hidden');
        document.getElementById('pdfViewer').classList.add('hidden');
        document.getElementById('pdfError').classList.add('hidden');
        
        // Check if PDF
        if (!url.toLowerCase().endsWith('.pdf')) {
            showPdfError();
            return;
        }
        
        loadPdf(url);
    }
    
    function loadPdf(url) {
        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            pdfDoc = pdf;
            document.getElementById('totalPages').textContent = pdf.numPages;
            pageNum = 1;
            renderPage(pageNum);
            document.getElementById('pdfLoading').classList.add('hidden');
            document.getElementById('pdfViewer').classList.remove('hidden');
        }).catch(function(error) {
            console.error('Error loading PDF:', error);
            showPdfError();
        });
    }
    
    function renderPage(num) {
        pageRendering = true;
        pdfDoc.getPage(num).then(function(page) {
            const canvas = document.getElementById('pdfCanvas');
            const ctx = canvas.getContext('2d');
            const viewport = page.getViewport({scale: scale});
            
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            
            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            
            const renderTask = page.render(renderContext);
            renderTask.promise.then(function() {
                pageRendering = false;
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            });
        });
        
        document.getElementById('currentPage').textContent = num;
    }
    
    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }
    
    function previousPage() {
        if (pageNum <= 1) return;
        pageNum--;
        queueRenderPage(pageNum);
    }
    
    function nextPage() {
        if (pageNum >= pdfDoc.numPages) return;
        pageNum++;
        queueRenderPage(pageNum);
    }
    
    function zoomIn() {
        scale += 0.25;
        document.getElementById('zoomLevel').textContent = Math.round(scale * 100);
        queueRenderPage(pageNum);
    }
    
    function zoomOut() {
        if (scale <= 0.5) return;
        scale -= 0.25;
        document.getElementById('zoomLevel').textContent = Math.round(scale * 100);
        queueRenderPage(pageNum);
    }
    
    function closePdfPreview() {
        document.getElementById('pdfPreviewModal').classList.add('hidden');
        pdfDoc = null;
        pageNum = 1;
        scale = 1.5;
    }
    
    function downloadCurrentPdf() {
        if (currentPdfUrl) {
            window.open(currentPdfUrl, '_blank');
        }
    }
    
    function showPdfError() {
        document.getElementById('pdfLoading').classList.add('hidden');
        document.getElementById('pdfViewer').classList.add('hidden');
        document.getElementById('pdfError').classList.remove('hidden');
    }
    
    function showVersionHistory(documentId, type) {
        // This will be implemented in next step
        alert('Version history for document ' + documentId);
    }
</script>
