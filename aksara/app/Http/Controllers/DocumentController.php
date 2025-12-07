<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\PenelitianDocument;
use App\Models\PengabdianDocument;
use App\Models\DocumentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class DocumentController extends Controller
{
    /**
     * Verify a document (Admin only)
     */
    public function verify(Request $request, $type, $documentId)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $documentClass = $type === 'penelitian' ? PenelitianDocument::class : PengabdianDocument::class;
        $document = $documentClass::findOrFail($documentId);

        $document->update([
            'verification_status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Dokumen berhasil diverifikasi.');
    }

    /**
     * Reject a document (Admin only)
     */
    public function reject(Request $request, $type, $documentId)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $documentClass = $type === 'penelitian' ? PenelitianDocument::class : PengabdianDocument::class;
        $document = $documentClass::findOrFail($documentId);

        $document->update([
            'verification_status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Dokumen ditolak.');
    }

    /**
     * Upload new version of document
     */
    public function uploadVersion(Request $request, $type, $documentId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'change_notes' => 'nullable|string|max:500',
        ]);

        $documentClass = $type === 'penelitian' ? PenelitianDocument::class : PengabdianDocument::class;
        $relation = $type === 'penelitian' ? 'penelitian' : 'pengabdian';
        $document = $documentClass::with($relation)->findOrFail($documentId);

        // Check authorization
        $owner = $document->$relation->user_id;
        if ($owner !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Save current version to history
            DocumentVersion::create([
                'document_type' => $type,
                'document_id' => $document->id,
                'version_number' => $document->version,
                'nama_file' => $document->nama_file,
                'path_file' => $document->path_file,
                'change_notes' => 'Previous version',
                'uploaded_by' => Auth::id(),
                'uploaded_at' => $document->uploaded_at,
            ]);

            // Upload new version
            $file = $request->file('file');
            $path = $file->store($type . '/documents', 'public');

            // Update document with new version
            $document->update([
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'version' => $document->version + 1,
                'uploaded_at' => now(),
                'verification_status' => 'pending', // Reset verification
                'verified_by' => null,
                'verified_at' => null,
                'rejection_reason' => null,
            ]);

            // Save change notes in version history
            if ($request->filled('change_notes')) {
                DocumentVersion::create([
                    'document_type' => $type,
                    'document_id' => $document->id,
                    'version_number' => $document->version,
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'change_notes' => $request->change_notes,
                    'uploaded_by' => Auth::id(),
                    'uploaded_at' => now(),
                ]);
            }

            DB::commit();
            return back()->with('success', 'Versi baru dokumen berhasil diupload (v' . $document->version . ').');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal upload versi baru: ' . $e->getMessage()]);
        }
    }

    /**
     * Get version history
     */
    public function getVersionHistory($type, $documentId)
    {
        try {
            $documentClass = $type === 'penelitian' ? PenelitianDocument::class : PengabdianDocument::class;
            $relation = $type === 'penelitian' ? 'penelitian' : 'pengabdian';
            $document = $documentClass::with($relation)->findOrFail($documentId);

            $versions = DocumentVersion::where('document_type', $type)
                ->where('document_id', $documentId)
                ->whereNull('deleted_at')
                ->with('uploader')
                ->orderByDesc('version_number')
                ->get();

            return response()->json([
                'current' => [
                    'version' => $document->version,
                    'nama_file' => $document->nama_file,
                    'path_file' => asset('storage/' . $document->path_file),
                    'uploaded_at' => $document->uploaded_at->format('d M Y H:i'),
                    'verification_status' => $document->verification_status,
                ],
                'versions' => $versions->map(function($v) {
                    return [
                        'version' => $v->version_number,
                        'nama_file' => $v->nama_file,
                        'path_file' => asset('storage/' . $v->path_file),
                        'uploaded_at' => $v->uploaded_at->format('d M Y H:i'),
                        'uploaded_by' => $v->uploader->name ?? 'Unknown',
                        'change_notes' => $v->change_notes,
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Table document_versions belum ada. Jalankan: php artisan migrate',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download all documents as ZIP
     */
    public function downloadAllAsZip(Request $request, $id)
    {
        // Determine type from route name or request
        $routeName = $request->route()->getName();
        $type = str_contains($routeName, 'penelitian') ? 'penelitian' : 'pengabdian';
        
        $model = $type === 'penelitian' ? Penelitian::class : Pengabdian::class;
        $item = $model::with('documents')->findOrFail($id);

        // Check authorization
        if ($item->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($item->documents->isEmpty()) {
            return back()->withErrors(['error' => 'Tidak ada dokumen untuk didownload.']);
        }

        // Create temporary ZIP file
        $zipFileName = $type . '_' . $id . '_documents_' . time() . '.zip';
        $zipFilePath = storage_path('app/temp/' . $zipFileName);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return back()->withErrors(['error' => 'Gagal membuat file ZIP.']);
        }

        foreach ($item->documents as $document) {
            $filePath = storage_path('app/public/' . $document->path_file);
            if (file_exists($filePath)) {
                $zip->addFile($filePath, $document->nama_file);
            }
        }

        $zip->close();

        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
    }
}
