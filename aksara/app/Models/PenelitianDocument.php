<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenelitianDocument extends Model
{
    protected $table = 'penelitian_documents';
    
    protected $fillable = [
        'penelitian_id',
        'jenis_dokumen',
        'nama_file',
        'path_file',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function penelitian(): BelongsTo
    {
        return $this->belongsTo(Penelitian::class);
    }
}
