<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    }

    /**
     * Relation avec les articles de blog
     */
    public function blogPosts()
    {
        return $this->belongsToMany(BlogPost::class);
    }
}
