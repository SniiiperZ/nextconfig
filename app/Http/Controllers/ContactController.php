<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;
use Inertia\Inertia;

class ContactController extends Controller
{
    /**
     * Affiche le formulaire de contact
     */
    public function index()
    {
        // Récupérer les sujets de contact prédéfinis depuis le cache
        $subjects = Cache::remember('contact_subjects', 60 * 60 * 24, function () {
            return [
                ['id' => 'general', 'name' => 'Question générale'],
                ['id' => 'order', 'name' => 'Suivi de commande'],
                ['id' => 'support', 'name' => 'Support technique'],
                ['id' => 'partnership', 'name' => 'Partenariat'],
                ['id' => 'other', 'name' => 'Autre']
            ];
        });

        return Inertia::render('Contact', [
            'subjects' => $subjects,
            'recentlyContacted' => Cache::get('contact_' . request()->ip(), false)
        ]);
    }

    /**
     * Envoie le formulaire de contact
     */
    public function send(Request $request)
    {
        // Vérifier si l'utilisateur a déjà envoyé un message récemment
        // pour limiter les spams
        if (Cache::get('contact_' . $request->ip())) {
            return back()->with('error', 'Vous avez déjà envoyé un message récemment. Merci de patienter avant d\'en envoyer un nouveau.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Horodater le message
        $validated['created_at'] = now();

        // Générer un identifiant unique pour ce message
        $messageId = uniqid('contact_');

        // Stocker le message dans le cache pour référence ultérieure
        // (en production, enregistrer en base de données serait préférable)
        Cache::put('contact_message_' . $messageId, $validated, 60 * 60 * 24 * 30); // 30 jours

        // Limiter les soumissions de formulaire (anti-spam)
        Cache::put('contact_' . $request->ip(), true, 60 * 10); // 10 minutes

        // Incrémenter le compteur de messages pour les statistiques
        Cache::increment('contact_messages_count');

        // Journaliser le message (pour le débug et les statistiques)
        Log::info('Nouveau message de contact', [
            'message_id' => $messageId,
            'email' => $validated['email'],
            'subject' => $validated['subject']
        ]);

        // Envoi de l'email (à implémenter plus tard)
        // Mail::to('contact@nextconfig.be')->send(new ContactFormMail($validated));

        return back()->with('success', 'Votre message a été envoyé avec succès ! Nous vous contacterons bientôt.');
    }
}
