<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::where('is_visible', true)
            ->orderBy('order')
            ->get();

        return Inertia::render('Faq/Index', [
            'faqs' => $faqs
        ]);
    }

    public function admin()
    {
        $faqs = Faq::orderBy('order')->get();

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

        return redirect()->back();
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

        return redirect()->back();
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->back();
    }
}
