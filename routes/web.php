<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ProjectController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Routes publiques
Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/services', function () {
    return Inertia::render('Services');
})->name('services');

Route::get('/portfolio', [ProjectController::class, 'index'])->name('portfolio');

Route::get('/blog', function () {
    return Inertia::render('Blog');
})->name('blog');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

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
});
