<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'year',
        'amount'
    ];

    /**
     * Récupérer les revenus pour une année spécifique
     */
    public static function getYearlyRevenue($year = null)
    {
        $year = $year ?? now()->year;

        return self::where('year', $year)
            ->orderBy('month')
            ->get();
    }
}
