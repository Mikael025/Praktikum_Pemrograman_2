<?php

namespace App\Services;

use App\Exceptions\DocumentUploadException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Service untuk menangani document upload dan management
 * Centralized logic untuk file handling penelitian dan pengabdian
 *
 * TODO: Implement CDN integration untuk production (Amazon S3, Cloudflare, dll)
 *       untuk optimasi storage dan delivery dokumen berukuran besar
 *
 * TODO: Tambahkan virus scanning untuk uploaded files (ClamAV integration)
 *       untuk meningkatkan security
 */
class DocumentService
{
    /**
     * Upload document dan create database record
     *
     * @param  UploadedFile  $file  File yang akan diupload
     * @param  Model  $parent  Parent model (Penelitian atau Pengabdian)
     * @param  string  $documentType  Jenis dokumen (proposal, laporan_akhir, dokumen_pendukung)
     * @param  string  $disk  Storage disk (default: public)
     * @return Model Document model yang telah dibuat
     *
     * @throws DocumentUploadException Jika upload gagal
     */
    public function uploadDocument(
        UploadedFile $file,
        Model $parent,
        string $documentType,
        string $disk = 'public'
    ): Model {
        try {
            // Determine storage path berdasarkan parent model type
            $modelType = strtolower(class_basename($parent));
            $storagePath = "{$modelType}/documents";

            // Store file ke storage
            $path = $file->store($storagePath, $disk);

            if (! $path) {
                throw new DocumentUploadException('Gagal menyimpan file ke storage.');
            }

            // Determine document model class
            $documentClass = $this->getDocumentModelClass($parent);

            // Create document record
            $document = $documentClass::create([
                $this->getForeignKeyName($parent) => $parent->id,
                'jenis_dokumen' => $documentType,
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_at' => now(),
            ]);

            // Log successful upload
            Log::info('Document uploaded', [
                'document_id' => $document->id,
                'parent_type' => get_class($parent),
                'parent_id' => $parent->id,
                'document_type' => $documentType,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
            ]);

            return $document;

        } catch (\Exception $e) {
            Log::error('Document upload failed', [
                'parent_type' => get_class($parent),
                'parent_id' => $parent->id,
                'document_type' => $documentType,
                'error' => $e->getMessage(),
            ]);

            throw new DocumentUploadException(
                'Gagal mengupload dokumen: '.$e->getMessage()
            );
        }
    }

    /**
     * Upload multiple documents sekaligus
     *
     * @param  array  $files  Array of UploadedFile
     * @param  Model  $parent  Parent model
     * @param  string  $documentType  Jenis dokumen
     * @return array Array of uploaded document models
     */
    public function uploadMultipleDocuments(
        array $files,
        Model $parent,
        string $documentType
    ): array {
        $uploadedDocuments = [];

        foreach ($files as $file) {
            try {
                $uploadedDocuments[] = $this->uploadDocument($file, $parent, $documentType);
            } catch (DocumentUploadException $e) {
                // Log error but continue with other files
                Log::warning('Failed to upload one of multiple documents', [
                    'parent_id' => $parent->id,
                    'file_name' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $uploadedDocuments;
    }

    /**
     * Delete document dari storage dan database
     *
     * @param  Model  $document  Document model yang akan dihapus
     * @param  string  $disk  Storage disk (default: public)
     * @return bool Success status
     */
    public function deleteDocument(Model $document, string $disk = 'public'): bool
    {
        try {
            // Delete file dari storage jika exists
            if ($document->path_file && Storage::disk($disk)->exists($document->path_file)) {
                Storage::disk($disk)->delete($document->path_file);
            }

            // Delete document record dari database
            $document->delete();

            Log::info('Document deleted', [
                'document_id' => $document->id,
                'file_name' => $document->nama_file,
                'path' => $document->path_file,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Document deletion failed', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Replace existing document dengan file baru
     *
     * @param  Model  $document  Existing document model
     * @param  UploadedFile  $newFile  New file yang akan diupload
     * @param  string  $disk  Storage disk
     * @return Model Updated document model
     *
     * @throws DocumentUploadException
     */
    public function replaceDocument(
        Model $document,
        UploadedFile $newFile,
        string $disk = 'public'
    ): Model {
        try {
            // Delete old file dari storage
            if ($document->path_file && Storage::disk($disk)->exists($document->path_file)) {
                Storage::disk($disk)->delete($document->path_file);
            }

            // Determine storage path
            $parentId = $this->getParentIdFromDocument($document);
            $modelType = strtolower($this->getModelTypeFromDocument($document));
            $storagePath = "{$modelType}/documents";

            // Upload new file
            $path = $newFile->store($storagePath, $disk);

            if (! $path) {
                throw new DocumentUploadException('Gagal menyimpan file baru ke storage.');
            }

            // Update document record
            $document->update([
                'nama_file' => $newFile->getClientOriginalName(),
                'path_file' => $path,
                'file_size' => $newFile->getSize(),
                'mime_type' => $newFile->getMimeType(),
                'uploaded_at' => now(),
            ]);

            Log::info('Document replaced', [
                'document_id' => $document->id,
                'old_file' => $document->nama_file,
                'new_file' => $newFile->getClientOriginalName(),
            ]);

            return $document;

        } catch (\Exception $e) {
            Log::error('Document replacement failed', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);

            throw new DocumentUploadException(
                'Gagal mengganti dokumen: '.$e->getMessage()
            );
        }
    }

    /**
     * Get document download URL
     *
     * @param  Model  $document  Document model
     * @param  string  $disk  Storage disk
     * @return string|null Download URL or null if file not exists
     */
    public function getDocumentUrl(Model $document, string $disk = 'public'): ?string
    {
        if (! $document->path_file) {
            return null;
        }

        if (! Storage::disk($disk)->exists($document->path_file)) {
            Log::warning('Document file not found', [
                'document_id' => $document->id,
                'path' => $document->path_file,
            ]);

            return null;
        }

        return Storage::url($document->path_file);
    }

    /**
     * Get document model class berdasarkan parent model
     *
     * @param  Model  $parent  Parent model
     * @return string Document model class name
     */
    private function getDocumentModelClass(Model $parent): string
    {
        $parentClass = class_basename($parent);

        $documentClasses = [
            'Penelitian' => \App\Models\PenelitianDocument::class,
            'Pengabdian' => \App\Models\PengabdianDocument::class,
        ];

        if (! isset($documentClasses[$parentClass])) {
            throw new \InvalidArgumentException(
                "Unsupported parent model type: {$parentClass}"
            );
        }

        return $documentClasses[$parentClass];
    }

    /**
     * Get foreign key name berdasarkan parent model
     *
     * @param  Model  $parent  Parent model
     * @return string Foreign key name
     */
    private function getForeignKeyName(Model $parent): string
    {
        $parentClass = strtolower(class_basename($parent));

        return "{$parentClass}_id";
    }

    /**
     * Get parent ID dari document model
     *
     * @param  Model  $document  Document model
     * @return int Parent ID
     */
    private function getParentIdFromDocument(Model $document): int
    {
        if (isset($document->penelitian_id)) {
            return $document->penelitian_id;
        }

        if (isset($document->pengabdian_id)) {
            return $document->pengabdian_id;
        }

        throw new \InvalidArgumentException('Cannot determine parent ID from document');
    }

    /**
     * Get model type dari document model
     *
     * @param  Model  $document  Document model
     * @return string Model type (penelitian/pengabdian)
     */
    private function getModelTypeFromDocument(Model $document): string
    {
        if (isset($document->penelitian_id)) {
            return 'penelitian';
        }

        if (isset($document->pengabdian_id)) {
            return 'pengabdian';
        }

        throw new \InvalidArgumentException('Cannot determine model type from document');
    }

    /**
     * Get file size in human readable format
     *
     * @param  int  $bytes  File size in bytes
     * @return string Formatted file size
     */
    public function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf('%.2f %s', $bytes / pow(1024, $factor), $units[$factor]);
    }

    /**
     * Validate file type
     *
     * @param  UploadedFile  $file  File yang akan divalidasi
     * @param  array  $allowedMimes  Allowed MIME types
     * @return bool Validation result
     */
    public function validateFileType(UploadedFile $file, array $allowedMimes): bool
    {
        return in_array($file->getMimeType(), $allowedMimes);
    }

    /**
     * Get allowed MIME types untuk dokumen akademik
     *
     * @return array Allowed MIME types
     */
    public function getAllowedMimeTypes(): array
    {
        return [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
    }
}
