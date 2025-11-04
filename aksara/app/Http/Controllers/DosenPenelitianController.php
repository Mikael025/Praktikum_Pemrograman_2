<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penelitian;
use App\Models\PenelitianDocument;
use App\Http\Requests\StorePenelitianRequest;
use App\Http\Requests\UpdatePenelitianRequest;
use App\Exceptions\WorkflowException;
use App\Exceptions\UnauthorizedActionException;
use App\Exceptions\DocumentUploadException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenPenelitianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $penelitian = $user->penelitian()->latest()->get();
        return view('dosen.penelitian.index', compact('penelitian'));
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
            $validated['tim_peneliti'] = json_encode(explode(',', $validated['tim_peneliti']));
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
        $penelitian->load('documents');
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
                throw new UnauthorizedActionException('update', $penelitian->status);
            }

            if (!$penelitian->canBeEditedByDosen()) {
                throw new UnauthorizedActionException('update', $penelitian->status);
            }

            DB::beginTransaction();

            $validated = $request->validated();
            $validated['tim_peneliti'] = json_encode(explode(',', $validated['tim_peneliti']));

            $penelitian->update($validated);

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

        // Upload sertifikat if provided
        if ($request->hasFile('sertifikat_file')) {
            $file = $request->file('sertifikat_file');
            $path = $file->store('penelitian/documents', 'public');

            PenelitianDocument::create([
                'penelitian_id' => $penelitian->id,
                'jenis_dokumen' => 'sertifikat',
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
                throw new UnauthorizedActionException('delete', $penelitian->status);
            }

            if (!$penelitian->canBeDeleted()) {
                throw new UnauthorizedActionException('delete', $penelitian->status);
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

}
