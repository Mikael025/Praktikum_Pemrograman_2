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
}
