<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
// use App\Mail\DevisRequestMail; // À implémenter plus tard

class DevisController extends Controller
{
    public function index()
    {
        return Inertia::render('Devis');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'usage_type' => 'required|string|in:gaming,streaming,content_creation,office,other',
            'usage_details' => 'required|string',
            'budget' => 'required|numeric|min:500|max:10000',
            'preferred_brands' => 'nullable|array',
            'rgb_lighting' => 'nullable|boolean',
            'timeframe' => 'required|string|in:asap,1month,3months,no_rush',
            'additional_notes' => 'nullable|string',
        ]);

        // Traitement et envoi d'email (à implémenter plus tard)
        // Mail::to('contact@nextconfig.be')->send(new DevisRequestMail($validated));

        return back()->with('success', 'Votre demande de configuration a été envoyée avec succès ! Nous vous contacterons bientôt pour discuter des détails.');
    }
}
