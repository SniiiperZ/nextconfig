<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'is_published',
        'order'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Définir les attributs qui doivent être rejetés des réponses JSON
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Attributs à ajouter aux réponses JSON
     */
    protected $appends = [
        'formatted_date',
        'reading_time'
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
     * Formater la date pour affichage
     */
    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at->locale('fr')->isoFormat('LL')
        );
    }

    /**
     * Calculer le temps de lecture approximatif
     */
    protected function readingTime(): Attribute
    {
        return Attribute::make(
            get: function () {
                $wordsPerMinute = 200;
                $wordCount = str_word_count(strip_tags($this->content));
                return ceil($wordCount / $wordsPerMinute);
            }
        );
    }

    /**
     * Relation avec les tags
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Relation avec les commentaires
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Récupérer uniquement les commentaires approuvés
     */
    public function approvedComments()
    {
        return $this->comments()->approved()->orderBy('created_at', 'desc');
    }

    /**
     * Scope pour récupérer uniquement les articles publiés
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
