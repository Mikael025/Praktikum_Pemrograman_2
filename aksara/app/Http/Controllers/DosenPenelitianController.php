<?php

namespace App\Http\Controllers;

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


class DosenPenelitianController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dosen.penelitian.create');
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
     */
    public function edit(Penelitian $penelitian)
    {
        if ($penelitian->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('dosen.penelitian.edit', compact('penelitian'));
    }

    /**
     * Update the specified resource in storage.
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
