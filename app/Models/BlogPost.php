<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'is_published',
        'published_at',
        'order'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement un slug à partir du titre si non fourni
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && !$post->isDirty('slug')) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    /**
     * Relation avec les tags
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Scope pour récupérer uniquement les articles publiés
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
