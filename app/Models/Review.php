<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'rating',
        'comment',
        'is_approved',
        'is_featured',
        'order'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'rating' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(function ($review) {
            // Vider les caches concernés
            self::clearReviewCaches();
        });

        static::deleted(function ($review) {
            // Vider les caches concernés
            self::clearReviewCaches();
        });
    }

    /**
     * Vider les caches liés aux reviews
     */
    private static function clearReviewCaches()
    {
        Cache::forget('featured_reviews');
        Cache::forget('latest_reviews');
        Cache::forget('reviews_count');
        Cache::forget('reviews_rating_distribution');
    }

    // Scope pour ne récupérer que les avis approuvés
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Scope pour récupérer les avis mis en avant
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Récupérer les avis en vedette avec mise en cache
     */
    public static function getFeaturedReviews($limit = 9)
    {
        return Cache::remember('featured_reviews', 60 * 30, function () use ($limit) {
            $featuredReviews = self::approved()
                ->featured()
                ->orderBy('order')
                ->take($limit)
                ->get();

            $regularReviews = self::approved()
                ->where('is_featured', false)
                ->orderBy('created_at', 'desc')
                ->take($limit)
                ->get();

            return $featuredReviews->merge($regularReviews)->take($limit);
        });
    }
}
