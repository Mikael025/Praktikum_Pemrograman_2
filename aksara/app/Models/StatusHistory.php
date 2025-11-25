<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StatusHistory extends Model
{
    use HasFactory;

    protected $table = 'status_history';

    protected $fillable = [
        'statusable_type',
        'statusable_id',
        'old_status',
        'new_status',
        'changed_by_user_id',
        'notes',
    ];

    /**
     * Get the owning statusable model (Penelitian or Pengabdian)
     */
    public function statusable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who made the status change
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
