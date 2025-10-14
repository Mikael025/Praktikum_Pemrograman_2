<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penelitian;
use App\Models\PenelitianDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DosenPenelitianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penelitian = Auth::user()->penelitian()->latest()->get();
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2020|max:2030',
            'tim_peneliti' => 'required|string',
            'sumber_dana' => 'required|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['tim_peneliti'] = json_encode(explode(',', $validated['tim_peneliti']));
        $validated['status'] = 'draft';

        Penelitian::create($validated);

        return redirect()->route('dosen.penelitian.index')->with('success', 'Penelitian berhasil ditambahkan.');
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
    public function update(Request $request, Penelitian $penelitian)
    {
        if ($penelitian->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2020|max:2030',
            'tim_peneliti' => 'required|string',
            'sumber_dana' => 'required|string|max:255',
        ]);

        $validated['tim_peneliti'] = json_encode(explode(',', $validated['tim_peneliti']));

        $penelitian->update($validated);

        return redirect()->route('dosen.penelitian.index')->with('success', 'Penelitian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penelitian $penelitian)
    {
        if ($penelitian->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Hapus dokumen terkait
        foreach ($penelitian->documents as $document) {
            Storage::disk('public')->delete($document->path_file);
        }
        
        $penelitian->delete();

        return redirect()->route('dosen.penelitian.index')->with('success', 'Penelitian berhasil dihapus.');
    }

    /**
     * Upload document for penelitian
     */
    public function uploadDocument(Request $request, Penelitian $penelitian)
    {
        if ($penelitian->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'jenis_dokumen' => 'required|in:proposal,laporan_akhir,sertifikat,dokumen_pendukung',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('penelitian/documents', 'public');

        PenelitianDocument::create([
            'penelitian_id' => $penelitian->id,
            'jenis_dokumen' => $validated['jenis_dokumen'],
            'nama_file' => $file->getClientOriginalName(),
            'path_file' => $path,
            'uploaded_at' => now(),
        ]);

        // Update status penelitian ke menunggu_verifikasi
        $penelitian->update(['status' => 'menunggu_verifikasi']);

        return back()->with('success', 'Dokumen berhasil diunggah dan penelitian siap untuk diverifikasi.');
    }
}
