<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'blog_post_id',
        'name',
        'email',
        'content',
        'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Relation avec l'article de blog
     */
    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }

    /**
     * Scope pour récupérer uniquement les commentaires approuvés
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope pour récupérer uniquement les commentaires en attente
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }
}
