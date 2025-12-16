<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penelitian;
use App\Models\PenelitianDocument;
use App\Http\Requests\StorePenelitianRequest;
use App\Http\Requests\UpdatePenelitianRequest;
use App\Exceptions\WorkflowException;
use App\Exceptions\DocumentUploadException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk manajemen penelitian oleh dosen
 * 
 * Menyediakan CRUD operations untuk penelitian termasuk upload dokumen,
 * tracking status verifikasi, dan view history perubahan status.
 */
class PenelitianController extends Controller
{
    /**
     * Menampilkan daftar penelitian milik dosen yang sedang login
     * 
     * Mendukung filter berdasarkan tahun, status, dan search query.
     * Menampilkan statistik penelitian berdasarkan status.
     * 
     * @param Request $request HTTP request dengan optional parameters: year, status, search
     * @return \Illuminate\View\View View daftar penelitian dengan statistik
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $year = $request->get('year');
        $query = $user->penelitian();
        if ($year) {
            $query->where('tahun', $year);
        }
        
        // Filter tahun
        if ($request->filled('year')) {
            $query->where('tahun', $request->year);
        }
        
        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('tim_peneliti', 'like', "%{$search}%")
                  ->orWhere('sumber_dana', 'like', "%{$search}%");
            });
        }

        // Statistik Penelitian dengan status baru
        $penelitianStats = [
            'diusulkan' => (clone $query)->where('status', 'diusulkan')->count(),
            'tidak_lolos' => (clone $query)->where('status', 'tidak_lolos')->count(),
            'lolos' => (clone $query)->whereIn('status', ['lolos_perlu_revisi', 'lolos', 'revisi_pra_final'])->count(),
            'selesai' => (clone $query)->where('status', 'selesai')->count(),
        ];

        $penelitian = $query->latest()->paginate(15);
        return view('dosen.penelitian.index', compact('penelitian', 'penelitianStats'));

    }

    /**
     * Menampilkan form untuk membuat penelitian baru
     * 
     * @return \Illuminate\View\View View form create penelitian
     */
    public function create()
    {
        return view('dosen.penelitian.create');
    }

    /**
     * Menyimpan penelitian baru ke database dengan dokumen pendukung
     * 
     * Menggunakan database transaction untuk memastikan data konsisten.
     * Upload multiple files dan create records di tabel penelitian_documents.
     * 
     * @param StorePenelitianRequest $request Validated request berisi data penelitian dan files
     * @return \Illuminate\Http\RedirectResponse Redirect ke index dengan status message
     * @throws \Exception Jika terjadi error saat transaction
     */
    public function store(StorePenelitianRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'diusulkan';

            $penelitian = Penelitian::create($validated);

            // Upload proposal file
            if ($request->hasFile('proposal_file')) {
                $file = $request->file('proposal_file');
                $path = $file->store('penelitian/documents', 'public');

                PenelitianDocument::create([
                    'penelitian_id' => $penelitian->id,
                    'jenis_dokumen' => 'proposal',
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'uploaded_at' => now(),
                ]);
            }

            // Upload optional supporting documents
            if ($request->hasFile('dokumen_pendukung')) {
                foreach ($request->file('dokumen_pendukung') as $file) {
                    $path = $file->store('penelitian/documents', 'public');

                    PenelitianDocument::create([
                        'penelitian_id' => $penelitian->id,
                        'jenis_dokumen' => 'dokumen_pendukung',
                        'nama_file' => $file->getClientOriginalName(),
                        'path_file' => $path,
                        'uploaded_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('dosen.penelitian.index')
                ->with('success', 'Penelitian berhasil ditambahkan dan siap untuk diverifikasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            throw new DocumentUploadException('Gagal menyimpan penelitian: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail penelitian dengan dokumen dan status history
     * 
     * @param Penelitian $penelitian Model penelitian (route model binding)
     * @return \Illuminate\View\View View detail penelitian
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Jika penelitian bukan milik user yang login
     */
    public function show(Penelitian $penelitian)
    {
        if ($penelitian->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $penelitian->load(['documents', 'statusHistory.changedBy']);
        return view('dosen.penelitian.show', compact('penelitian'));
    }

    /**
     * Menampilkan form edit penelitian
     * 
     * Hanya penelitian dengan status tertentu yang dapat diedit.
     * 
     * @param Penelitian $penelitian Model penelitian (route model binding)
     * @return \Illuminate\View\View View form edit penelitian
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Jika penelitian bukan milik user atau status tidak valid
     */
    public function edit(Penelitian $penelitian)
    {
        if ($penelitian->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('dosen.penelitian.edit', compact('penelitian'));
    }

    /**
     * Update data penelitian yang sudah ada
     * 
     * Mendukung update dokumen pendukung. Menggunakan transaction untuk konsistensi data.
     * 
     * @param UpdatePenelitianRequest $request Validated request berisi data update
     * @param Penelitian $penelitian Model penelitian (route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirect ke index dengan status message
     * @throws \Exception Jika terjadi error saat transaction
     */
    public function update(UpdatePenelitianRequest $request, Penelitian $penelitian)
    {
        try {
            if ($penelitian->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            if (!$penelitian->canBeEditedByDosen()) {
                return back()->withErrors(['error' => 'Penelitian tidak dapat diedit pada status saat ini.']);
            }

            DB::beginTransaction();

            $penelitian->update($request->validated());

            // Handle file uploads
            $this->handleFileUploads($request, $penelitian);

            DB::commit();

            return redirect()->route('dosen.penelitian.index')
                ->with('success', 'Penelitian berhasil diperbarui.');

        } catch (WorkflowException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle file uploads for penelitian
     */
    private function handleFileUploads($request, Penelitian $penelitian)
    {
        // Upload proposal if provided
        if ($request->hasFile('proposal_file')) {
            $file = $request->file('proposal_file');
            $path = $file->store('penelitian/documents', 'public');

            PenelitianDocument::create([
                'penelitian_id' => $penelitian->id,
                'jenis_dokumen' => 'proposal',
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'uploaded_at' => now(),
            ]);
        }

        // Upload laporan akhir if provided
        if ($request->hasFile('laporan_akhir_file')) {
            $file = $request->file('laporan_akhir_file');
            $path = $file->store('penelitian/documents', 'public');

            PenelitianDocument::create([
                'penelitian_id' => $penelitian->id,
                'jenis_dokumen' => 'laporan_akhir',
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'uploaded_at' => now(),
            ]);
        }

        // Upload supporting documents if provided
        if ($request->hasFile('dokumen_pendukung')) {
            foreach ($request->file('dokumen_pendukung') as $file) {
                $path = $file->store('penelitian/documents', 'public');

                PenelitianDocument::create([
                    'penelitian_id' => $penelitian->id,
                    'jenis_dokumen' => 'dokumen_pendukung',
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'uploaded_at' => now(),
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penelitian $penelitian)
    {
        try {
            if ($penelitian->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            if (!$penelitian->canBeDeleted()) {
                return back()->withErrors(['error' => 'Penelitian tidak dapat dihapus pada status saat ini.']);
            }

            DB::beginTransaction();

            // Hapus dokumen terkait
            foreach ($penelitian->documents as $document) {
                Storage::disk('public')->delete($document->path_file);
            }
            
            $penelitian->delete();

            DB::commit();

            return redirect()->route('dosen.penelitian.index')
                ->with('success', 'Penelitian berhasil dihapus.');

        } catch (WorkflowException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete a specific document
     */
    public function deleteDocument(Penelitian $penelitian, PenelitianDocument $document)
    {
        try {
            // Check authorization: must be owner
            if ($penelitian->user_id !== Auth::id()) {
                return back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menghapus dokumen ini.']);
            }

            // Check if penelitian is not selesai
            if ($penelitian->status === 'selesai') {
                return back()->withErrors(['error' => 'Tidak dapat menghapus dokumen pada penelitian yang sudah selesai.']);
            }

            // Check if document belongs to this penelitian
            if ($document->penelitian_id !== $penelitian->id) {
                return back()->withErrors(['error' => 'Dokumen tidak ditemukan.']);
            }

            DB::beginTransaction();

            // Delete file from storage
            Storage::disk('public')->delete($document->path_file);

            // Delete database record
            $document->delete();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Dokumen berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

}
