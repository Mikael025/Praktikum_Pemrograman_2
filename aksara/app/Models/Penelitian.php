<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function statusHistory(): MorphMany
    {
        return $this->morphMany(StatusHistory::class, 'statusable')->latest();
    }

    /**
     * Check if penelitian can be edited by dosen
     */
    public function canBeEditedByDosen(): bool
    {
        return in_array($this->status, ['diusulkan', 'lolos', 'lolos_perlu_revisi', 'revisi_pra_final']);
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

    /**
     * Calculate completion progress percentage
     */
    public function calculateProgress(): array
    {
        $weights = [
            'status' => 40,
            'documents' => 40,
            'feedback' => 20,
        ];

        $scores = [
            'status' => $this->getStatusScore(),
            'documents' => $this->getDocumentScore(),
            'feedback' => $this->getFeedbackScore(),
        ];

        $totalProgress = 0;
        foreach ($weights as $key => $weight) {
            $totalProgress += ($scores[$key] / 100) * $weight;
        }

        return [
            'total' => round($totalProgress),
            'status' => $scores['status'],
            'documents' => $scores['documents'],
            'feedback' => $scores['feedback'],
        ];
    }

    /**
     * Get status completion score (0-100)
     */
    private function getStatusScore(): int
    {
        $statusScores = [
            'diusulkan' => 20,
            'tidak_lolos' => 0,
            'lolos_perlu_revisi' => 40,
            'lolos' => 70,
            'revisi_pra_final' => 85,
            'selesai' => 100,
        ];

        return $statusScores[$this->status] ?? 0;
    }

    /**
     * Get document completion score (0-100)
     */
    private function getDocumentScore(): int
    {
        $required = $this->getRequiredDocuments();
        if (empty($required)) {
            return 100;
        }

        $existing = $this->documents()->pluck('jenis_dokumen')->toArray();
        $completed = count(array_intersect($required, $existing));

        return round(($completed / count($required)) * 100);
    }

    /**
     * Get feedback addressed score (0-100)
     */
    private function getFeedbackScore(): int
    {
        // If no feedback required, full score
        if (!in_array($this->status, ['lolos_perlu_revisi', 'revisi_pra_final'])) {
            return 100;
        }

        // Check if documents uploaded after feedback
        $lastStatusChange = $this->statusHistory()->first();
        if (!$lastStatusChange) {
            return 50; // No history, assume partially addressed
        }

        $documentsAfterFeedback = $this->documents()
            ->where('created_at', '>', $lastStatusChange->created_at)
            ->count();

        return $documentsAfterFeedback > 0 ? 100 : 50;
    }
}
