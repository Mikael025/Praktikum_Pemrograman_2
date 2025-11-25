<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pengabdian;
use App\Models\PengabdianDocument;
use App\Http\Requests\StorePengabdianRequest;
use App\Http\Requests\UpdatePengabdianRequest;
use App\Exceptions\WorkflowException;
use App\Exceptions\DocumentUploadException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenPengabdianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $query = $user->pengabdian();
        
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
                  ->orWhere('tim_pelaksana', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('mitra', 'like', "%{$search}%");
            });
        }
        
        $pengabdian = $query->latest()->paginate(15);
        return view('dosen.pengabdian.index', compact('pengabdian'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dosen.pengabdian.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePengabdianRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'diusulkan';

            $pengabdian = Pengabdian::create($validated);

            // Upload proposal file
            if ($request->hasFile('proposal_file')) {
                $file = $request->file('proposal_file');
                $path = $file->store('pengabdian/documents', 'public');

                PengabdianDocument::create([
                    'pengabdian_id' => $pengabdian->id,
                    'jenis_dokumen' => 'proposal',
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'uploaded_at' => now(),
                ]);
            }

            // Upload optional supporting documents
            if ($request->hasFile('dokumen_pendukung')) {
                foreach ($request->file('dokumen_pendukung') as $file) {
                    $path = $file->store('pengabdian/documents', 'public');

                    PengabdianDocument::create([
                        'pengabdian_id' => $pengabdian->id,
                        'jenis_dokumen' => 'dokumen_pendukung',
                        'nama_file' => $file->getClientOriginalName(),
                        'path_file' => $path,
                        'uploaded_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('dosen.pengabdian.index')
                ->with('success', 'Pengabdian berhasil ditambahkan dan siap untuk diverifikasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            throw new DocumentUploadException('Gagal menyimpan pengabdian: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengabdian $pengabdian)
    {
        if ($pengabdian->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $pengabdian->load(['documents', 'statusHistory.changedBy']);
        return view('dosen.pengabdian.show', compact('pengabdian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengabdian $pengabdian)
    {
        if ($pengabdian->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('dosen.pengabdian.edit', compact('pengabdian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePengabdianRequest $request, Pengabdian $pengabdian)
    {
        try {
            if ($pengabdian->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            if (!$pengabdian->canBeEditedByDosen()) {
                return back()->withErrors(['error' => 'Pengabdian tidak dapat diedit pada status saat ini.']);
            }

            DB::beginTransaction();

            $pengabdian->update($request->validated());

            // Handle file uploads
            $this->handleFileUploads($request, $pengabdian);

            DB::commit();

            return redirect()->route('dosen.pengabdian.index')
                ->with('success', 'Pengabdian berhasil diperbarui.');

        } catch (WorkflowException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle file uploads for pengabdian
     */
    private function handleFileUploads($request, Pengabdian $pengabdian)
    {
        // Upload proposal if provided
        if ($request->hasFile('proposal_file')) {
            $file = $request->file('proposal_file');
            $path = $file->store('pengabdian/documents', 'public');

            PengabdianDocument::create([
                'pengabdian_id' => $pengabdian->id,
                'jenis_dokumen' => 'proposal',
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'uploaded_at' => now(),
            ]);
        }

        // Upload laporan akhir if provided
        if ($request->hasFile('laporan_akhir_file')) {
            $file = $request->file('laporan_akhir_file');
            $path = $file->store('pengabdian/documents', 'public');

            PengabdianDocument::create([
                'pengabdian_id' => $pengabdian->id,
                'jenis_dokumen' => 'laporan_akhir',
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'uploaded_at' => now(),
            ]);
        }

        // Upload supporting documents if provided
        if ($request->hasFile('dokumen_pendukung')) {
            foreach ($request->file('dokumen_pendukung') as $file) {
                $path = $file->store('pengabdian/documents', 'public');

                PengabdianDocument::create([
                    'pengabdian_id' => $pengabdian->id,
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
    public function destroy(Pengabdian $pengabdian)
    {
        try {
            if ($pengabdian->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            if (!$pengabdian->canBeDeleted()) {
                return back()->withErrors(['error' => 'Pengabdian tidak dapat dihapus pada status saat ini.']);
            }

            DB::beginTransaction();

            // Hapus dokumen terkait
            foreach ($pengabdian->documents as $document) {
                Storage::disk('public')->delete($document->path_file);
            }
            
            $pengabdian->delete();

            DB::commit();

            return redirect()->route('dosen.pengabdian.index')
                ->with('success', 'Pengabdian berhasil dihapus.');

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
    public function deleteDocument(Pengabdian $pengabdian, PengabdianDocument $document)
    {
        try {
            // Check authorization: must be owner
            if ($pengabdian->user_id !== Auth::id()) {
                return back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menghapus dokumen ini.']);
            }

            // Check if pengabdian is not selesai
            if ($pengabdian->status === 'selesai') {
                return back()->withErrors(['error' => 'Tidak dapat menghapus dokumen pada pengabdian yang sudah selesai.']);
            }

            // Check if document belongs to this pengabdian
            if ($document->pengabdian_id !== $pengabdian->id) {
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
