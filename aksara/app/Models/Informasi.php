<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informasi extends Model
{
    use HasFactory;

    protected $table = 'informasi';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category',
        'visibility',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeVisibleTo(Builder $query, string $role): Builder
    {
        if ($role === 'admin') {
            return $query->whereIn('visibility', ['admin', 'semua']);
        }
        if ($role === 'dosen') {
            return $query->whereIn('visibility', ['dosen', 'semua']);
        }
        return $query->where('visibility', 'semua');
    }

    public function scopeCategory(Builder $query, ?string $category): Builder
    {
        if (!$category) {
            return $query;
        }
        return $query->where('category', $category);
    }
}


