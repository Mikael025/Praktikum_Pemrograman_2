<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penelitian extends Model
{
    protected $table = 'penelitian';
    
    protected $fillable = [
        'user_id',
        'judul',
        'tahun',
        'tim_peneliti',
        'sumber_dana',
        'status',
        'catatan_verifikasi',
    ];

    protected $casts = [
        'tim_peneliti' => 'array',
        'tahun' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PenelitianDocument::class);
    }

    /**
     * Check if penelitian can be edited by dosen
     */
    public function canBeEditedByDosen(): bool
    {
        return in_array($this->status, ['diusulkan', 'lolos_perlu_revisi', 'revisi_pra_final']);
    }

    /**
     * Check if penelitian can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->status === 'diusulkan';
    }

    /**
     * Check if proposal is required
     */
    public function requiresProposal(): bool
    {
        return in_array($this->status, ['diusulkan', 'lolos_perlu_revisi', 'revisi_pra_final']);
    }

    /**
     * Check if final documents are required
     */
    public function requiresFinalDocuments(): bool
    {
        return in_array($this->status, ['lolos', 'revisi_pra_final']);
    }

    /**
     * Get required documents based on status
     */
    public function getRequiredDocuments(): array
    {
        $required = [];
        
        if ($this->requiresProposal()) {
            $required[] = 'proposal';
        }
        
        if ($this->requiresFinalDocuments()) {
            $required[] = 'laporan_akhir';
            $required[] = 'sertifikat';
        }
        
        return $required;
    }

    /**
     * Check if status transition is valid
     */
    public function canTransitionTo(string $newStatus): bool
    {
        $validTransitions = [
            'diusulkan' => ['tidak_lolos', 'lolos_perlu_revisi', 'lolos'],
            'lolos_perlu_revisi' => ['lolos'],
            'lolos' => ['revisi_pra_final', 'selesai'],
            'revisi_pra_final' => ['selesai'],
            'tidak_lolos' => [],
            'selesai' => [],
        ];

        return in_array($newStatus, $validTransitions[$this->status] ?? []);
    }

    /**
     * Check if has required documents
     */
    public function hasRequiredDocuments(): bool
    {
        $required = $this->getRequiredDocuments();
        $existing = $this->documents()->pluck('jenis_dokumen')->toArray();
        
        return empty(array_diff($required, $existing));
    }
}
