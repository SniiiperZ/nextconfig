<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Review;
use App\Models\MonthlyRevenue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer l'année sélectionnée depuis la requête
        $selectedYear = $request->input('year', now()->year);

        // Récupérer les années disponibles avec mise en cache
        $availableYears = Cache::remember('available_years', 60 * 60 * 24, function () {
            $years = MonthlyRevenue::distinct('year')
                ->pluck('year')
                ->toArray();

            // Toujours inclure l'année courante et années adjacentes
            $currentYear = now()->year;
            $years[] = $currentYear;
            $years[] = $currentYear - 1;
            $years[] = $currentYear + 1;

            return array_unique($years);
        });

        sort($availableYears);

        // Statistiques avec mise en cache des données peu modifiées
        $stats = [
            'projects_count' => Cache::remember('projects_count', 60 * 10, function () {
                return Project::count();
            }),

            'reviews_count' => Cache::remember('reviews_count', 60 * 10, function () {
                return Review::count();
            }),

            'blog_posts_count' => Cache::remember('blog_posts_count', 60 * 10, function () {
                return BlogPost::count();
            }),

            'comments_count' => Comment::count(),
            'pending_comments_count' => Comment::where('is_approved', false)->count(),
            'pending_reviews_count' => Review::where('is_approved', false)->count(),

            'reviews_rating_distribution' => Cache::remember('reviews_rating_distribution', 60 * 30, function () {
                return [
                    Review::where('rating', 5)->count(),
                    Review::where('rating', 4)->count(),
                    Review::where('rating', 3)->count(),
                    Review::where('rating', 2)->count(),
                    Review::where('rating', 1)->count(),
                ];
            }),

            'monthly_revenue' => $this->getMonthlyRevenue($selectedYear),
            'total_revenue' => $this->calculateTotalRevenue($selectedYear),
            'selected_year' => (int)$selectedYear,
            'available_years' => $availableYears,

            'current_month_revenue' => MonthlyRevenue::where('month', now()->month)
                ->where('year', now()->year)
                ->value('amount') ?? 0,
            'current_month' => now()->month,
            'current_year' => now()->year,
        ];

        // Récupérer les données récentes
        $recent_reviews = Review::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recent_projects = Project::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recent_blog_posts = BlogPost::orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $recent_comments = Comment::with('blogPost')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recent_reviews' => $recent_reviews,
            'recent_projects' => $recent_projects,
            'recent_blog_posts' => $recent_blog_posts,
            'recent_comments' => $recent_comments,
        ]);
    }

    /**
     * Récupérer les revenus mensuels pour une année spécifique
     * avec mise en cache des résultats
     */
    private function getMonthlyRevenue($year)
    {
        return Cache::remember('monthly_revenues_' . $year, 60 * 60 * 24, function () use ($year) {
            $revenues = MonthlyRevenue::where('year', $year)
                ->orderBy('month')
                ->get()
                ->pluck('amount', 'month')
                ->toArray();

            // Assurer que nous avons des données pour tous les mois
            $result = [];
            for ($i = 1; $i <= 12; $i++) {
                $result[] = $revenues[$i] ?? 0;
            }

            return $result;
        });
    }

    /**
     * Calculer le revenu total d'une année spécifique
     * avec mise en cache des résultats
     */
    private function calculateTotalRevenue($year)
    {
        return Cache::remember('total_revenue_' . $year, 60 * 60 * 24, function () use ($year) {
            return MonthlyRevenue::where('year', $year)->sum('amount');
        });
    }

    /**
     * Mettre à jour les revenus mensuels
     */
    public function updateMonthlyRevenue(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'amount' => 'required|numeric|min:0',
        ]);

        // Log pour débogage
        Log::info('Mise à jour revenus', $validated);

        // Mise à jour ou création de l'entrée
        MonthlyRevenue::updateOrCreate(
            [
                'month' => $validated['month'],
                'year' => $validated['year'],
            ],
            [
                'amount' => $validated['amount'],
            ]
        );

        // Vider les caches concernés
        Cache::forget('monthly_revenues_' . $validated['year']);
        Cache::forget('total_revenue_' . $validated['year']);
        Cache::forget('available_years');

        // Message de confirmation
        $monthName = Carbon::createFromDate($validated['year'], $validated['month'], 1)
            ->locale('fr')->monthName;

        return redirect()->route('dashboard', ['year' => $validated['year']])
            ->with('success', "Revenus du mois de {$monthName} {$validated['year']} mis à jour avec succès");
    }
}
