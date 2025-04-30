<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Envoi de l'email (à implémenter plus tard)
        // Mail::to('contact@nextconfig.be')->send(new ContactFormMail($validated));

        return back()->with('success', 'Votre message a été envoyé avec succès ! Nous vous contacterons bientôt.');
    }
}
