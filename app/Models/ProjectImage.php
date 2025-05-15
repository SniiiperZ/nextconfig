<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ProjectImage extends Model
{
    protected $fillable = [
        'project_id',
        'image_path',
        'order',
        'is_main'
    ];

    protected $casts = [
        'is_main' => 'boolean'
    ];

    /**
     * Get the project that owns this image.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        // Vider le cache quand une image de projet est modifiée
        static::saved(function ($image) {
            Cache::forget('project_' . $image->project_id);
            Cache::forget('featured_projects');
            Cache::forget('projects_with_images');
        });

        static::deleted(function ($image) {
            Cache::forget('project_' . $image->project_id);
            Cache::forget('featured_projects');
            Cache::forget('projects_with_images');
        });
    }
}
