<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
