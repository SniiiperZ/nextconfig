<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends Controller
{
    // Afficher la page d'administration des avis
    public function admin()
    {
        $reviews = Review::orderBy('created_at', 'desc')->get();

        return Inertia::render('Reviews/Admin', [
            'reviews' => $reviews
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

        return redirect()->back();
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

        return redirect()->back();
    }

    // Approuver ou rejeter un avis rapidement
    public function updateApproval(Request $request, Review $review)
    {
        $validated = $request->validate([
            'is_approved' => 'required|boolean',
        ]);

        $review->update($validated);

        return redirect()->back();
    }

    // Supprimer un avis
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->back();
    }
}
