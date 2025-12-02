<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengabdianDocument extends Model
{
    protected $table = 'pengabdian_documents';
    
    protected $fillable = [
        'pengabdian_id',
        'jenis_dokumen',
        'nama_file',
        'path_file',
        'uploaded_at',
        'tags',
        'category',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'tags' => 'array',
    ];

    public function pengabdian(): BelongsTo
    {
        return $this->belongsTo(Pengabdian::class);
    }
}
