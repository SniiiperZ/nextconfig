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
            ->take(9)
            ->get();

        $regularReviews = Review::approved()
            ->where('is_featured', false)
            ->orderBy('created_at', 'desc')
            ->take(9)
            ->get();

        // Combiner les deux collections et s'assurer que nous avons au maximum 9 avis
        $reviews = $featuredReviews->merge($regularReviews)->take(9);

        return Inertia::render('Home', [
            'reviews' => $reviews,
            'canLogin' => false, // Modifier selon vos besoins
            'canRegister' => false, // Modifier selon vos besoins
        ]);
    }
}
