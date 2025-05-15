<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MonthlyRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'year',
        'amount'
    ];

    protected static function booted()
    {
        static::saved(function ($revenue) {
            // Vider les caches concernés lors d'une modification
            Cache::forget('monthly_revenues_' . $revenue->year);
            Cache::forget('total_revenue_' . $revenue->year);
            Cache::forget('available_years');
        });

        static::deleted(function ($revenue) {
            // Vider les caches concernés lors d'une suppression
            Cache::forget('monthly_revenues_' . $revenue->year);
            Cache::forget('total_revenue_' . $revenue->year);
            Cache::forget('available_years');
        });
    }

    /**
     * Récupérer les revenus pour une année spécifique avec mise en cache
     */
    public static function getYearlyRevenue($year = null)
    {
        $year = $year ?? now()->year;

        return Cache::remember('monthly_revenues_' . $year, 60 * 60 * 24, function () use ($year) {
            return self::where('year', $year)
                ->orderBy('month')
                ->get();
        });
    }

    /**
     * Récupérer toutes les années disponibles avec mise en cache
     */
    public static function getAvailableYears()
    {
        return Cache::remember('available_years', 60 * 60 * 24, function () {
            return self::distinct('year')
                ->pluck('year')
                ->toArray();
        });
    }
}
