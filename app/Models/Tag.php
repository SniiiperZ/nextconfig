<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement un slug à partir du nom si non fourni
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && !$tag->isDirty('slug')) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        // Vider le cache quand un tag est modifié
        static::saved(function ($tag) {
            Cache::forget('all_tags');
            Cache::forget('blog_posts_index');
        });

        static::deleted(function ($tag) {
            Cache::forget('all_tags');
            Cache::forget('blog_posts_index');
        });
    }

    /**
     * Relation avec les articles de blog
     */
    public function blogPosts()
    {
        return $this->belongsToMany(BlogPost::class);
    }

    /**
     * Récupérer tous les tags avec mise en cache
     */
    public static function getAllTags()
    {
        return Cache::remember('all_tags', 60 * 30, function () {
            return self::orderBy('name')->get();
        });
    }

    /**
     * Récupérer un tag par son slug avec mise en cache
     */
    public static function getBySlug($slug)
    {
        return Cache::remember('tag_' . $slug, 60 * 30, function () use ($slug) {
            return self::where('slug', $slug)->firstOrFail();
        });
    }
}
