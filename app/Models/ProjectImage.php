<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
