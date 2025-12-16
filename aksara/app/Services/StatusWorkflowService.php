<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\StatusHistory;
use App\Exceptions\InvalidStatusTransitionException;
use App\Exceptions\WorkflowException;

/**
 * Service untuk menangani workflow status transition
 * Centralized logic untuk status change penelitian dan pengabdian
 * 
 * @package App\Services
 */
class StatusWorkflowService
{
    /**
     * Transition model status dengan validation dan audit trail
     * 
     * @param Model $model Model yang akan diubah statusnya (Penelitian atau Pengabdian)
     * @param string $targetStatus Status tujuan
     * @param string|null $notes Catatan verifikasi
     * @param int|null $changedBy User ID yang mengubah status (default: Auth user)
     * @return void
     * @throws InvalidStatusTransitionException Jika transisi tidak valid
     * @throws WorkflowException Jika terjadi error workflow lainnya
     */
    public function transitionStatus(
        Model $model,
        string $targetStatus,
        ?string $notes = null,
        ?int $changedBy = null
    ): void {
        // Validate status transition menggunakan model's business logic
        if (!method_exists($model, 'canTransitionTo') || !$model->canTransitionTo($targetStatus)) {
            throw new InvalidStatusTransitionException($model->status, $targetStatus);
        }

        DB::beginTransaction();
        try {
            $oldStatus = $model->status;
            $changedByUserId = $changedBy ?? Auth::id();

            // Update model status
            $model->update([
                'status' => $targetStatus,
                'catatan_verifikasi' => $notes ?? $this->getDefaultNotes($targetStatus),
            ]);

            // Record status history untuk audit trail
            StatusHistory::create([
                'statusable_type' => get_class($model),
                'statusable_id' => $model->id,
                'old_status' => $oldStatus,
                'new_status' => $targetStatus,
                'changed_by_user_id' => $changedByUserId,
                'notes' => $notes ?? $this->getDefaultNotes($targetStatus),
            ]);

            // Log event untuk monitoring
            Log::info('Status transitioned', [
                'model_type' => class_basename($model),
                'model_id' => $model->id,
                'from_status' => $oldStatus,
                'to_status' => $targetStatus,
                'changed_by' => $changedByUserId,
                'notes' => $notes,
                'timestamp' => now(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error untuk debugging
            Log::error('Status transition failed', [
                'model_type' => class_basename($model),
                'model_id' => $model->id,
                'target_status' => $targetStatus,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Update catatan verifikasi tanpa mengubah status
     * Untuk memberikan feedback tambahan pada status yang sama
     * 
     * @param Model $model Model yang akan diupdate catatannya
     * @param string $notes Catatan verifikasi baru
     * @return void
     */
    public function updateNotes(Model $model, string $notes): void
    {
        DB::beginTransaction();
        try {
            $oldNotes = $model->catatan_verifikasi ?? '';

            $model->update([
                'catatan_verifikasi' => $notes,
            ]);

            // Log untuk audit trail
            Log::info('Catatan verifikasi updated', [
                'model_type' => class_basename($model),
                'model_id' => $model->id,
                'status' => $model->status,
                'old_notes' => $oldNotes,
                'new_notes' => $notes,
                'changed_by' => Auth::id(),
                'timestamp' => now(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Update notes failed', [
                'model_type' => class_basename($model),
                'model_id' => $model->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get default notes untuk status tertentu jika tidak disediakan
     * 
     * @param string $status Status yang akan diset
     * @return string Default notes
     */
    private function getDefaultNotes(string $status): string
    {
        $defaultNotes = [
            'tidak_lolos' => 'Tidak lolos verifikasi',
            'lolos_perlu_revisi' => 'Lolos dengan revisi',
            'lolos' => 'Lolos',
            'revisi_pra_final' => 'Perlu revisi pra-final',
            'selesai' => 'Selesai',
        ];

        return $defaultNotes[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Get success message berdasarkan status
     * Helper untuk standardize success messages di controllers
     * 
     * @param string $status Status yang baru diset
     * @param string $modelType Tipe model (Penelitian/Pengabdian)
     * @return string Success message
     */
    public function getSuccessMessage(string $status, string $modelType = 'Penelitian'): string
    {
        $messages = [
            'tidak_lolos' => "$modelType berhasil ditolak.",
            'lolos_perlu_revisi' => "$modelType lolos dengan revisi.",
            'lolos' => "$modelType berhasil disetujui.",
            'revisi_pra_final' => "$modelType diminta revisi pra-final.",
            'selesai' => "$modelType berhasil diselesaikan.",
        ];

        return $messages[$status] ?? "$modelType berhasil diubah ke status " . str_replace('_', ' ', $status) . ".";
    }

    /**
     * Batch transition untuk multiple models sekaligus
     * Useful untuk bulk operations di admin panel
     * 
     * @param array $models Array of models
     * @param string $targetStatus Status tujuan
     * @param string|null $notes Catatan verifikasi
     * @return array ['success' => int, 'failed' => int, 'errors' => array]
     */
    public function batchTransition(array $models, string $targetStatus, ?string $notes = null): array
    {
        $success = 0;
        $failed = 0;
        $errors = [];

        foreach ($models as $model) {
            try {
                $this->transitionStatus($model, $targetStatus, $notes);
                $success++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = [
                    'model_id' => $model->id,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'success' => $success,
            'failed' => $failed,
            'errors' => $errors,
        ];
    }
}
