<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenelitianDocument extends Model
{
    protected $table = 'penelitian_documents';
    
    protected $fillable = [
        'penelitian_id',
        'jenis_dokumen',
        'nama_file',
        'path_file',
        'uploaded_at',
        'tags',
        'category',
        'verification_status',
        'verified_by',
        'verified_at',
        'rejection_reason',
        'version',
        'parent_document_id',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
        'tags' => 'array',
    ];

    public function penelitian(): BelongsTo
    {
        return $this->belongsTo(Penelitian::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function parentDocument(): BelongsTo
    {
        return $this->belongsTo(PenelitianDocument::class, 'parent_document_id');
    }

    public function childDocuments(): HasMany
    {
        return $this->hasMany(PenelitianDocument::class, 'parent_document_id');
    }

    /**
     * Check if document is verified
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    /**
     * Check if document is rejected
     */
    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }

    /**
     * Check if document is pending
     */
    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    /**
     * Get all versions including current
     */
    public function getAllVersions()
    {
        return DocumentVersion::where('document_type', 'penelitian')
            ->where('document_id', $this->id)
            ->whereNull('deleted_at')
            ->orderByDesc('version_number')
            ->get();
    }

    /**
     * Get version count
     */
    public function getVersionCount()
    {
        return DocumentVersion::where('document_type', 'penelitian')
            ->where('document_id', $this->id)
            ->whereNull('deleted_at')
            ->count() + 1; // +1 for current version
    }
}
