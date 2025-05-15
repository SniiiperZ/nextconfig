<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'specs',
        'image_path', // Conservé pour compatibilité avec le code existant
        'is_featured',
        'order',
        'is_visible'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_visible' => 'boolean'
    ];

    /**
     * Get all images for this project.
     */
    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order');
    }

    /**
     * Get the main image for this project.
     */
    public function mainImage()
    {
        return $this->hasOne(ProjectImage::class)->where('is_main', true);
    }

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        // Vider le cache quand un projet est modifié
        static::saved(function ($project) {
            Cache::forget('project_' . $project->id);
            Cache::forget('featured_projects');
            Cache::forget('projects_count');
            Cache::forget('projects_with_images');
        });

        static::deleted(function ($project) {
            Cache::forget('project_' . $project->id);
            Cache::forget('featured_projects');
            Cache::forget('projects_count');
            Cache::forget('projects_with_images');
        });
    }

    /**
     * Scope pour les projets visibles
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope pour les projets en vedette
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Récupérer les projets en vedette avec mise en cache
     */
    public static function getFeaturedProjects($limit = 6)
    {
        return Cache::remember('featured_projects', 60 * 30, function () use ($limit) {
            return self::with(['images' => function ($query) {
                $query->orderBy('order');
            }])
                ->visible()
                ->featured()
                ->orderBy('order')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Récupérer tous les projets avec leurs images, optimisé avec cache
     */
    public static function getAllWithImages()
    {
        return Cache::remember('projects_with_images', 60 * 30, function () {
            return self::with(['images' => function ($query) {
                $query->orderBy('order');
            }])
                ->visible()
                ->orderBy('order')
                ->get();
        });
    }
}
