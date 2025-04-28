<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // Récupérer les avis approuvés
        $featuredReviews = Review::approved()
            ->featured()
            ->orderBy('order')
            ->take(6)
            ->get();

        $regularReviews = Review::approved()
            ->where('is_featured', false)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Combiner les deux collections et s'assurer que nous avons au maximum 6 avis
        $reviews = $featuredReviews->merge($regularReviews)->take(6);

        return Inertia::render('Home', [
            'reviews' => $reviews,
            'canLogin' => false, // Modifier selon vos besoins
            'canRegister' => false, // Modifier selon vos besoins
        ]);
    }
}
