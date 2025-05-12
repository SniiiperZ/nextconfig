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
        // Récupérer l'année sélectionnée depuis la requête, sinon utiliser l'année courante
        $selectedYear = $request->input('year', now()->year);

        // Récupérer toutes les années disponibles avec des revenus
        $availableYears = MonthlyRevenue::distinct('year')
            ->pluck('year')
            ->toArray();

        // Toujours inclure l'année courante et les deux années adjacentes
        $currentYear = now()->year;
        $availableYears[] = $currentYear;
        $availableYears[] = $currentYear - 1;
        $availableYears[] = $currentYear + 1;

        // Dédupliquer et trier
        $availableYears = array_unique($availableYears);
        sort($availableYears);

        // Récupérer spécifiquement le revenu du mois actuel pour la section "Période actuelle"
        $currentMonthRevenue = MonthlyRevenue::where('month', now()->month)
            ->where('year', now()->year)
            ->value('amount') ?? 0;

        // Statistiques générales
        $stats = [
            'projects_count' => Project::count(),
            'reviews_count' => Review::count(),
            'blog_posts_count' => BlogPost::count(),
            'comments_count' => Comment::count(),
            'pending_comments_count' => Comment::where('is_approved', false)->count(),
            'pending_reviews_count' => Review::where('is_approved', false)->count(),

            // Distribution des notes des avis
            'reviews_rating_distribution' => [
                Review::where('rating', 5)->count(),
                Review::where('rating', 4)->count(),
                Review::where('rating', 3)->count(),
                Review::where('rating', 2)->count(),
                Review::where('rating', 1)->count(),
            ],

            // Revenus mensuels pour l'année sélectionnée
            'monthly_revenue' => $this->getMonthlyRevenue($selectedYear),
            'total_revenue' => $this->calculateTotalRevenue($selectedYear),
            'selected_year' => (int)$selectedYear,
            'available_years' => $availableYears,

            // Informations sur la période actuelle (indépendantes de l'année sélectionnée)
            'current_month_revenue' => $currentMonthRevenue,
            'current_month' => now()->month,
            'current_year' => now()->year,
        ];

        // Dernières activités
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
     */
    private function getMonthlyRevenue($year)
    {
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
    }

    /**
     * Calculer le revenu total d'une année spécifique
     */
    private function calculateTotalRevenue($year)
    {
        return MonthlyRevenue::where('year', $year)->sum('amount');
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

        // Afficher le debug pour s'assurer que les données sont correctes
        Log::info('Mise à jour revenus', [
            'mois' => $validated['month'],
            'année' => $validated['year'],
            'montant' => $validated['amount']
        ]);

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

        // Vider les caches potentiels qui pourraient affecter les données
        Cache::forget('monthly_revenues_' . $validated['year']);

        // Message de confirmation avec le mois en français
        $monthName = Carbon::createFromDate($validated['year'], $validated['month'], 1)
            ->locale('fr')->monthName;

        return redirect()->route('dashboard', ['year' => $validated['year']])
            ->with('success', "Revenus du mois de {$monthName} {$validated['year']} mis à jour avec succès");
    }
}
