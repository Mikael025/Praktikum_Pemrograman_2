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
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function pengabdian(): BelongsTo
    {
        return $this->belongsTo(Pengabdian::class);
    }
}
