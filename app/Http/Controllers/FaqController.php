<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::getVisibleFaqs();

        return Inertia::render('Faq/Index', [
            'faqs' => $faqs
        ]);
    }

    public function admin()
    {
        $faqs = Faq::getAllFaqs();

        return Inertia::render('Faq/Admin', [
            'faqs' => $faqs
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'required|integer',
            'is_visible' => 'boolean'
        ]);

        Faq::create($validated);

        // Vider les caches concernés
        Cache::forget('visible_faqs');
        Cache::forget('all_faqs');

        return redirect()->back()->with('success', 'FAQ ajoutée avec succès');
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'required|integer',
            'is_visible' => 'boolean'
        ]);

        $faq->update($validated);

        // Vider les caches concernés
        Cache::forget('visible_faqs');
        Cache::forget('all_faqs');

        return redirect()->back()->with('success', 'FAQ mise à jour avec succès');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        // Vider les caches concernés
        Cache::forget('visible_faqs');
        Cache::forget('all_faqs');

        return redirect()->back()->with('success', 'FAQ supprimée avec succès');
    }
}
