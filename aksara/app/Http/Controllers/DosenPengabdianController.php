<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengabdian;
use App\Models\PengabdianDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DosenPengabdianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengabdian = Auth::user()->pengabdian()->latest()->get();
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2020|max:2030',
            'tim_pelaksana' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'mitra' => 'required|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['tim_pelaksana'] = json_encode(explode(',', $validated['tim_pelaksana']));
        $validated['status'] = 'draft';

        Pengabdian::create($validated);

        return redirect()->route('dosen.pengabdian.index')->with('success', 'Pengabdian berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengabdian $pengabdian)
    {
        if ($pengabdian->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $pengabdian->load('documents');
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
    public function update(Request $request, Pengabdian $pengabdian)
    {
        if ($pengabdian->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2020|max:2030',
            'tim_pelaksana' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'mitra' => 'required|string|max:255',
        ]);

        $validated['tim_pelaksana'] = json_encode(explode(',', $validated['tim_pelaksana']));

        $pengabdian->update($validated);

        return redirect()->route('dosen.pengabdian.index')->with('success', 'Pengabdian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengabdian $pengabdian)
    {
        if ($pengabdian->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Hapus dokumen terkait
        foreach ($pengabdian->documents as $document) {
            Storage::disk('public')->delete($document->path_file);
        }
        
        $pengabdian->delete();

        return redirect()->route('dosen.pengabdian.index')->with('success', 'Pengabdian berhasil dihapus.');
    }

    /**
     * Upload document for pengabdian
     */
    public function uploadDocument(Request $request, Pengabdian $pengabdian)
    {
        if ($pengabdian->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'jenis_dokumen' => 'required|in:proposal,laporan_akhir,sertifikat,dokumen_pendukung',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('pengabdian/documents', 'public');

        PengabdianDocument::create([
            'pengabdian_id' => $pengabdian->id,
            'jenis_dokumen' => $validated['jenis_dokumen'],
            'nama_file' => $file->getClientOriginalName(),
            'path_file' => $path,
            'uploaded_at' => now(),
        ]);

        // Update status pengabdian ke menunggu_verifikasi
        $pengabdian->update(['status' => 'menunggu_verifikasi']);

        return back()->with('success', 'Dokumen berhasil diunggah dan pengabdian siap untuk diverifikasi.');
    }
}
