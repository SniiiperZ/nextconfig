<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/services', function () {
    return Inertia::render('Services');
})->name('services');

Route::get('/portfolio', [ProjectController::class, 'index'])->name('portfolio');

Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{slug}/comment', [CommentController::class, 'store'])->name('blog.comment');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

// Routes publiques pour les avis
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// Routes pour le devis PC
Route::get('/devis', [DevisController::class, 'index'])->name('devis');
Route::post('/devis/submit', [DevisController::class, 'submit'])->name('devis.submit');

// Route pour le calculateur de budget
Route::get('/calculateur-budget', function () {
    return Inertia::render('BudgetCalculator');
})->name('budget.calculator');

// Route pour politique de confidentialité
Route::get('/politique-confidentialite', function () {
    return Inertia::render('PrivacyPolicy');
})->name('privacy.policy');

// Routes administrateur
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard avec le nouveau contrôleur
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route pour mettre à jour les revenus mensuels
    Route::post('/dashboard/update-monthly-revenue', [DashboardController::class, 'updateMonthlyRevenue'])
        ->name('admin.update-monthly-revenue');

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
    Route::get('/admin/blog', [BlogController::class, 'admin'])->name('admin.blog');
    Route::post('/admin/blog', [BlogController::class, 'store'])->name('admin.blog.store');
    Route::post('/admin/blog/{post}', [BlogController::class, 'update'])->name('admin.blog.update');
    Route::delete('/admin/blog/{post}', [BlogController::class, 'destroy'])->name('admin.blog.destroy');

    // Routes pour la gestion des commentaires
    Route::get('/admin/comments', [CommentController::class, 'index'])->name('admin.comments');
    Route::patch('/admin/comments/{comment}/approve', [CommentController::class, 'approve'])->name('admin.comments.approve');
    Route::delete('/admin/comments/{comment}', [CommentController::class, 'destroy'])->name('admin.comments.destroy');

    // Routes des tags
    Route::post('/admin/tags', [TagController::class, 'store'])->name('admin.tags.store');
    Route::delete('/admin/tags/{tag}', [TagController::class, 'destroy'])->name('admin.tags.destroy');

    // Routes pour la gestion des avis
    Route::get('/admin/reviews', [ReviewController::class, 'admin'])->name('admin.reviews');
    Route::post('/admin/reviews', [ReviewController::class, 'adminStore'])->name('admin.reviews.store');
    Route::put('/admin/reviews/{review}', [ReviewController::class, 'update'])->name('admin.reviews.update');
    Route::patch('/admin/reviews/{review}/approval', [ReviewController::class, 'updateApproval'])->name('admin.reviews.approval');
    Route::delete('/admin/reviews/{review}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
});
