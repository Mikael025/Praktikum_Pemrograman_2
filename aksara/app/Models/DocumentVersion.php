<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVersion extends Model
{
    protected $fillable = [
        'document_type',
        'document_id',
        'version_number',
        'nama_file',
        'path_file',
        'change_notes',
        'uploaded_by',
        'uploaded_at',
        'deleted_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who uploaded this version
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the parent document (polymorphic-like access)
     */
    public function getParentDocument()
    {
        if ($this->document_type === 'penelitian') {
            return PenelitianDocument::find($this->document_id);
        } elseif ($this->document_type === 'pengabdian') {
            return PengabdianDocument::find($this->document_id);
        }
        return null;
    }

    /**
     * Scope to get non-deleted versions
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope to get old versions (eligible for deletion)
     */
    public function scopeOldVersions($query, $days = 30)
    {
        return $query->whereNull('deleted_at')
            ->where('uploaded_at', '<', now()->subDays($days));
    }
}
