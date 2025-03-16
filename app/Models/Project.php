<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'specs',
        'image_path',
        'is_featured',
        'order',
        'is_visible'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_visible' => 'boolean'
    ];
}
