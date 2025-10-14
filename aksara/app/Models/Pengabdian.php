<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengabdian extends Model
{
    protected $table = 'pengabdian';
    
    protected $fillable = [
        'user_id',
        'judul',
        'tahun',
        'tim_pelaksana',
        'lokasi',
        'mitra',
        'status',
        'catatan_verifikasi',
    ];

    protected $casts = [
        'tim_pelaksana' => 'array',
        'tahun' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PengabdianDocument::class);
    }
}
