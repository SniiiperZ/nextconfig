<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'order',
        'is_visible'
    ];

    protected $casts = [
        'is_visible' => 'boolean'
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        // Vider le cache quand une FAQ est modifiée
        static::saved(function ($faq) {
            Cache::forget('visible_faqs');
            Cache::forget('all_faqs');
        });

        static::deleted(function ($faq) {
            Cache::forget('visible_faqs');
            Cache::forget('all_faqs');
        });
    }

    /**
     * Scope pour les FAQs visibles
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Récupérer toutes les FAQs visibles avec mise en cache
     */
    public static function getVisibleFaqs()
    {
        return Cache::remember('visible_faqs', 60 * 60, function () {
            return self::visible()
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Récupérer toutes les FAQs (admin) avec mise en cache
     */
    public static function getAllFaqs()
    {
        return Cache::remember('all_faqs', 60 * 15, function () {
            return self::orderBy('order')->get();
        });
    }
}
