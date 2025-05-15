<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
     * Boot the model.
     */
    protected static function booted()
    {
        // Vider le cache quand un commentaire est modifié
        static::saved(function ($comment) {
            Cache::forget('blog_post_' . $comment->blogPost->slug);
            Cache::forget('pending_comments_count');
            Cache::forget('approved_comments');
            Cache::forget('pending_comments');
        });

        static::deleted(function ($comment) {
            if ($comment->blogPost) {
                Cache::forget('blog_post_' . $comment->blogPost->slug);
            }
            Cache::forget('pending_comments_count');
            Cache::forget('approved_comments');
            Cache::forget('pending_comments');
        });
    }

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

    /**
     * Récupère le nombre de commentaires en attente avec mise en cache
     */
    public static function getPendingCount()
    {
        return Cache::remember('pending_comments_count', 60 * 5, function () {
            return self::pending()->count();
        });
    }

    /**
     * Récupère les commentaires approuvés avec mise en cache
     */
    public static function getApprovedComments($limit = 10)
    {
        return Cache::remember('approved_comments', 60 * 15, function () use ($limit) {
            return self::with('blogPost')
                ->approved()
                ->orderBy('created_at', 'desc')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Récupère les commentaires en attente avec mise en cache (pour admin)
     */
    public static function getPendingComments()
    {
        return Cache::remember('pending_comments', 60 * 5, function () {
            return self::with('blogPost')
                ->pending()
                ->orderBy('created_at', 'desc')
                ->get();
        });
    }
}
