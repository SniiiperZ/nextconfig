<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Combien de temps faut-il pour assembler un PC gaming sur mesure ?',
                'answer' => 'Le temps d\'assemblage varie entre 1 et 3 jours ouvrables selon la complexité du build et la disponibilité des composants. Nous vous tenons informé à chaque étape du processus.'
            ],
            [
                'question' => 'Proposez-vous un service après-vente ?',
                'answer' => 'Oui, tous nos PC bénéficient d\'une garantie d\'un an sur l\'assemblage. Nous restons disponibles pour tout conseil ou dépannage relatif à votre configuration.'
            ],
            [
                'question' => 'Puis-je faire évoluer mon PC dans le futur ?',
                'answer' => 'Absolument ! Nous concevons nos PC avec une vision d\'évolutivité. Nous pouvons vous conseiller sur les futurs upgrades en fonction de votre configuration initiale.'
            ],
            [
                'question' => 'Quels modes de paiement acceptez-vous ?',
                'answer' => 'Nous acceptons les paiements par carte bancaire, virement bancaire et PayPal. Des facilités de paiement peuvent être proposées pour les configurations haut de gamme.'
            ],
            [
                'question' => 'Fournissez-vous des périphériques avec le PC ?',
                'answer' => 'Nous pouvons inclure tous les périphériques nécessaires (écran, clavier, souris, casque) à votre setup gaming. Précisez vos besoins lors de votre demande de devis.'
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
