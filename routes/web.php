<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ProjectController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Routes publiques
Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        // 'canRegister' => Route::has('register'),
    ]);
})->name('home');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/services', function () {
    return Inertia::render('Services');
})->name('services');

Route::get('/portfolio', [ProjectController::class, 'index'])->name('portfolio');

Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

// Route d'accueil avec les avis 
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Routes publiques pour les avis
Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

// Routes administrateur
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Routes pour la gestion des FAQs
    Route::get('/admin/faq', [FaqController::class, 'admin'])->name('admin.faq');
    Route::post('/admin/faq', [FaqController::class, 'store'])->name('admin.faq.store');
    Route::put('/admin/faq/{faq}', [FaqController::class, 'update'])->name('admin.faq.update');
    Route::delete('/admin/faq/{faq}', [FaqController::class, 'destroy'])->name('admin.faq.destroy');

    // Routes pour la gestion du portfolio
    Route::get('/admin/portfolio', [ProjectController::class, 'admin'])->name('admin.portfolio');
    Route::post('/admin/portfolio', [ProjectController::class, 'store'])->name('admin.portfolio.store');
    Route::post('/admin/portfolio/{project}', [ProjectController::class, 'update'])->name('admin.portfolio.update');
    Route::delete('/admin/portfolio/{project}', [ProjectController::class, 'destroy'])->name('admin.portfolio.destroy');
    Route::post('/admin/portfolio/{project}/images/order', [ProjectController::class, 'updateImageOrder'])->name('admin.portfolio.images.order');

    // Routes du blog
    Route::get('/admin/blog', [App\Http\Controllers\BlogController::class, 'admin'])->name('admin.blog');
    Route::post('/admin/blog', [App\Http\Controllers\BlogController::class, 'store'])->name('admin.blog.store');
    Route::post('/admin/blog/{post}', [App\Http\Controllers\BlogController::class, 'update'])->name('admin.blog.update');
    Route::delete('/admin/blog/{post}', [App\Http\Controllers\BlogController::class, 'destroy'])->name('admin.blog.destroy');

    // Routes des tags
    Route::post('/admin/tags', [App\Http\Controllers\TagController::class, 'store'])->name('admin.tags.store');
    Route::delete('/admin/tags/{tag}', [App\Http\Controllers\TagController::class, 'destroy'])->name('admin.tags.destroy');

    // Routes pour la gestion des avis
    Route::get('/admin/reviews', [App\Http\Controllers\ReviewController::class, 'admin'])->name('admin.reviews');
    Route::post('/admin/reviews', [App\Http\Controllers\ReviewController::class, 'adminStore'])->name('admin.reviews.store');
    Route::put('/admin/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'update'])->name('admin.reviews.update');
    Route::patch('/admin/reviews/{review}/approval', [App\Http\Controllers\ReviewController::class, 'updateApproval'])->name('admin.reviews.approval');
    Route::delete('/admin/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
});
