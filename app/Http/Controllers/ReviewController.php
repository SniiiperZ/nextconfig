<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class ReviewController extends Controller
{
    // Afficher la page d'administration des avis
    public function admin()
    {
        $reviews = Review::orderBy('created_at', 'desc')->get();

        return Inertia::render('Reviews/Admin', [
            'reviews' => $reviews,
            'pendingCount' => Review::where('is_approved', false)->count()
        ]);
    }

    // Soumettre un nouvel avis (côté public)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:150',
        ]);

        // Pas de besoin d'approuver les avis envoyés par l'admin
        $isAdmin = $request->user() && $request->user()->can('manage reviews');

        $validated['is_approved'] = $isAdmin;

        Review::create($validated);

        // Vider les caches concernés
        $this->clearReviewCaches();

        return redirect()->back()->with('success', 'Votre avis a été soumis avec succès et sera affiché après modération.');
    }

    // Ajouter un avis (côté admin)
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:150',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'integer',
        ]);

        Review::create($validated);

        // Vider les caches concernés
        $this->clearReviewCaches();

        return redirect()->back()->with('success', 'Avis ajouté avec succès');
    }

    // Mettre à jour un avis
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:150',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'integer',
        ]);

        $review->update($validated);

        // Vider les caches concernés
        $this->clearReviewCaches();

        return redirect()->back()->with('success', 'Avis mis à jour avec succès');
    }

    // Approuver ou rejeter un avis rapidement
    public function updateApproval(Request $request, Review $review)
    {
        $validated = $request->validate([
            'is_approved' => 'required|boolean',
        ]);

        $review->update($validated);

        // Vider les caches concernés
        $this->clearReviewCaches();

        return redirect()->back()->with('success', 'Statut de l\'avis mis à jour avec succès');
    }

    // Supprimer un avis
    public function destroy(Review $review)
    {
        $review->delete();

        // Vider les caches concernés
        $this->clearReviewCaches();

        return redirect()->back()->with('success', 'Avis supprimé avec succès');
    }

    /**
     * Vider les caches liés aux avis
     */
    private function clearReviewCaches()
    {
        Cache::forget('featured_reviews');
        Cache::forget('latest_reviews');
        Cache::forget('reviews_count');
        Cache::forget('reviews_rating_distribution');
    }
}
