<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\PenelitianDocument;
use App\Models\PengabdianDocument;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

/**
 * Controller untuk halaman publik yang dapat diakses tanpa autentikasi
 * 
 * Menyediakan fitur browsing informasi, berita, penelitian, dan pengabdian
 * yang sudah selesai untuk masyarakat umum.
 */
class PublicController extends Controller
{
    /**
     * Menampilkan halaman home publik dengan featured berita dan statistik
     * 
     * @return View View homepage dengan berita terbaru dan total kegiatan
     */
    public function home(): View
    {
        // Get 3 most recent published and visible berita
        $featuredBerita = Informasi::published()
            ->visibleTo('guest')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        // Get total research and community service counts
        $penelitian = Penelitian::all();
        $pengabdian = Pengabdian::all();

        // Build stats array matching admin laporan structure
        $stats = [
            'total_penelitian' => $penelitian->count(),
            'total_pengabdian' => $pengabdian->count(),
            'penelitian_selesai' => $penelitian->where('status', 'selesai')->count(),
            'pengabdian_selesai' => $pengabdian->where('status', 'selesai')->count(),
        ];

        // Get download statistics (for future use)
        $totalDocuments = PenelitianDocument::query()
            ->where('jenis_dokumen', 'laporan_akhir')
            ->whereHas('penelitian', fn($q) => $q->where('status', 'selesai'))
            ->count();

        $penelitianCount = PenelitianDocument::query()
            ->where('jenis_dokumen', 'laporan_akhir')
            ->whereHas('penelitian', fn($q) => $q->where('status', 'selesai'))
            ->count();

        $pengabdianCount = PengabdianDocument::query()
            ->where('jenis_dokumen', 'laporan_akhir')
            ->whereHas('pengabdian', fn($q) => $q->where('status', 'selesai'))
            ->count();

        return view('welcome', compact('featuredBerita', 'stats', 'totalDocuments', 'penelitianCount', 'pengabdianCount'));
    }

    /**
     * Menampilkan berita berdasarkan kategori dengan pagination
     * 
     * @param string $category Kategori berita: 'umum', 'penelitian', 'pengabdian', atau 'semua'
     * @return View View daftar berita berdasarkan kategori
     */
    public function newsByCategory(string $category): View
    {
        $validCategories = ['semua', 'umum', 'penelitian', 'pengabdian'];
        
        if (!in_array($category, $validCategories)) {
            abort(404);
        }

        $query = Informasi::published()
            ->visibleTo('guest');

        // Only filter by category if not 'semua'
        if ($category !== 'semua') {
            $query->where('category', $category);
        }

        $informasi = $query->orderByDesc('published_at')
            ->paginate(10);

        $categoryLabel = match($category) {
            'penelitian' => 'Penelitian',
            'pengabdian' => 'Pengabdian Masyarakat',
            'semua' => 'Semua Berita',
            default => 'Umum'
        };

        return view('public.news-category', compact('informasi', 'category', 'categoryLabel'));
    }

    /**
     * Menampilkan detail artikel berita berdasarkan slug
     * 
     * @param string $category Kategori berita untuk validasi dan breadcrumb
     * @param string $slug Slug unik artikel
     * @return View View detail artikel
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException Jika kategori invalid atau artikel tidak ditemukan
     */
    public function newsDetail(string $category, string $slug): View
    {
        $validCategories = ['umum', 'penelitian', 'pengabdian'];
        if (!in_array($category, $validCategories)) {
            abort(404);
        }


        $berita = Informasi::published()
            ->visibleTo('guest')
            ->where('category', $category)
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.news-detail', compact('berita'));
    }

    /**
     * List final reports (laporan akhir) dari penelitian & pengabdian dengan status selesai
     */
    public function downloads(Request $request): View
    {
        // Build query for final reports
        $query = PenelitianDocument::query()
            ->where('jenis_dokumen', 'laporan_akhir')
            ->whereHas('penelitian', function ($q) {
                $q->where('status', 'selesai');
            })
            ->with(['penelitian.user']);

        // Add pengabdian documents
        $pengabdianDocs = PengabdianDocument::query()
            ->where('jenis_dokumen', 'laporan_akhir')
            ->whereHas('pengabdian', function ($q) {
                $q->where('status', 'selesai');
            })
            ->with(['pengabdian.user']);

        // Filter by category
        $category = $request->get('category', 'semua');
        if ($category === 'penelitian') {
            $documents = $query->orderByDesc('uploaded_at')->paginate(15);
        } elseif ($category === 'pengabdian') {
            $documents = $pengabdianDocs->orderByDesc('uploaded_at')->paginate(15);
        } else {
            // Merge penelitian and pengabdian documents for 'semua'
            $penelitianDocs = $query->orderByDesc('uploaded_at')->get();
            $pengabdianDocs = $pengabdianDocs->orderByDesc('uploaded_at')->get();
            
            // Combine and paginate manually
            $allDocs = collect()
                ->merge($penelitianDocs->map(function($doc) {
                    $doc->type = 'penelitian';
                    $doc->parent = $doc->penelitian;
                    return $doc;
                }))
                ->merge($pengabdianDocs->map(function($doc) {
                    $doc->type = 'pengabdian';
                    $doc->parent = $doc->pengabdian;
                    return $doc;
                }))
                ->sortByDesc('uploaded_at');

            $documents = new \Illuminate\Pagination\Paginator(
                $allDocs->forPage($request->get('page', 1), 15),
                15,
                $request->get('page', 1),
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );
        }

        // Filter by year
        $year = $request->get('year');
        if ($year) {
            $documents = $documents->where(function($doc) use ($year) {
                return $doc->parent->tahun == $year;
            });
        }

        // Search by title or lecturer name
        $search = $request->get('search');
        if ($search) {
            $documents = $documents->filter(function($doc) use ($search) {
                $judul = $doc->parent->judul ?? '';
                $namaLecturer = $doc->parent->user->name ?? '';
                return stripos($judul, $search) !== false || stripos($namaLecturer, $search) !== false;
            });
        }

        // Get year range for filter
        $yearRange = [];
        $penelitianYears = Penelitian::where('status', 'selesai')->distinct()->pluck('tahun');
        $pengabdianYears = Pengabdian::where('status', 'selesai')->distinct()->pluck('tahun');
        $yearRange = collect($penelitianYears)->merge($pengabdianYears)->unique()->sort()->reverse()->toArray();

        // Count by category
        $penelitianCount = PenelitianDocument::where('jenis_dokumen', 'laporan_akhir')
            ->whereHas('penelitian', function ($q) { $q->where('status', 'selesai'); })->count();
        $pengabdianCount = PengabdianDocument::where('jenis_dokumen', 'laporan_akhir')
            ->whereHas('pengabdian', function ($q) { $q->where('status', 'selesai'); })->count();
        $totalDocuments = $penelitianCount + $pengabdianCount;

        return view('public.downloads', compact('documents', 'category', 'year', 'search', 'yearRange', 'totalDocuments', 'penelitianCount', 'pengabdianCount'));
    }

    /**
     * Download dokumen laporan akhir penelitian atau pengabdian
     * 
     * @param Request $request HTTP request
     * @param string $type Tipe dokumen: 'penelitian' atau 'pengabdian'
     * @param int $id ID dokumen yang akan didownload
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse Download response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException Jika tipe invalid atau dokumen tidak ditemukan
     */
    public function downloadDocument(Request $request, string $type, int $id)
    {
        if (!in_array($type, ['penelitian', 'pengabdian'])) {
            abort(404);
        }

        if ($type === 'penelitian') {
            $document = PenelitianDocument::where('id', $id)
                ->where('jenis_dokumen', 'laporan_akhir')
                ->whereHas('penelitian', function ($q) {
                    $q->where('status', 'selesai');
                })
                ->firstOrFail();
        } else {
            $document = PengabdianDocument::where('id', $id)
                ->where('jenis_dokumen', 'laporan_akhir')
                ->whereHas('pengabdian', function ($q) {
                    $q->where('status', 'selesai');
                })
                ->firstOrFail();
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($document->path_file)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(
            Storage::disk('public')->path($document->path_file),
            $document->nama_file
        );
    }
}


