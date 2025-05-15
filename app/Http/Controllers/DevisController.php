<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
// use App\Mail\DevisRequestMail; // À implémenter plus tard

class DevisController extends Controller
{
    /**
     * Affiche le formulaire de demande de devis
     */
    public function index()
    {
        // Récupérer les marques disponibles depuis le cache
        $brands = Cache::remember('available_brands', 60 * 60 * 24, function () {
            return [
                ['id' => 'nvidia', 'name' => 'NVIDIA'],
                ['id' => 'amd', 'name' => 'AMD'],
                ['id' => 'intel', 'name' => 'Intel'],
                ['id' => 'asus', 'name' => 'ASUS'],
                ['id' => 'msi', 'name' => 'MSI'],
                ['id' => 'corsair', 'name' => 'Corsair'],
                ['id' => 'gigabyte', 'name' => 'Gigabyte'],
                ['id' => 'cooler_master', 'name' => 'Cooler Master'],
                ['id' => 'nzxt', 'name' => 'NZXT']
            ];
        });

        // Récupérer les types d'usage depuis le cache
        $usageTypes = Cache::remember('usage_types', 60 * 60 * 24, function () {
            return [
                ['id' => 'gaming', 'name' => 'Gaming', 'description' => 'PC optimisé pour les jeux vidéo'],
                ['id' => 'streaming', 'name' => 'Streaming', 'description' => 'Pour diffuser du contenu en direct'],
                ['id' => 'content_creation', 'name' => 'Création de contenu', 'description' => 'Montage vidéo, 3D, design graphique'],
                ['id' => 'office', 'name' => 'Bureautique', 'description' => 'Usage professionnel et productivité'],
                ['id' => 'other', 'name' => 'Autre', 'description' => 'Précisez dans les détails']
            ];
        });

        return Inertia::render('Devis', [
            'brands' => $brands,
            'usageTypes' => $usageTypes,
            'defaultBudget' => 1500, // Budget par défaut
        ]);
    }

    /**
     * Traite la soumission d'une demande de devis
     */
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

        // Horodater la demande
        $validated['created_at'] = now();

        // Générer un identifiant unique pour cette demande
        $requestId = uniqid('devis_');

        // Stocker la demande dans le cache pour référence ultérieure
        // (en production, enregistrer en base de données serait préférable)
        Cache::put('devis_request_' . $requestId, $validated, 60 * 60 * 24 * 30); // 30 jours

        // Incrémenter le compteur de demandes pour les statistiques
        Cache::increment('devis_requests_count');

        // Journaliser la demande (pour le débug et les statistiques)
        Log::info('Nouvelle demande de devis', [
            'request_id' => $requestId,
            'email' => $validated['email'],
            'budget' => $validated['budget'],
            'usage_type' => $validated['usage_type']
        ]);

        // Traitement et envoi d'email (à implémenter plus tard)
        // Mail::to('contact@nextconfig.be')->send(new DevisRequestMail($validated));

        return back()->with('success', 'Votre demande de configuration a été envoyée avec succès ! Nous vous contacterons bientôt pour discuter des détails.');
    }
}
