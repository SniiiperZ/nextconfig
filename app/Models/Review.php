<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'rating',
        'comment',
        'is_approved',
        'is_featured',
        'order'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Scope pour ne récupérer que les avis approuvés
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Scope pour récupérer les avis mis en avant
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
