<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // Récupérer les avis mis en cache
        $reviews = Review::getFeaturedReviews();

        return Inertia::render('Home', [
            'reviews' => $reviews,
            'canLogin' => false,
            'canRegister' => false,
        ]);
    }
}
