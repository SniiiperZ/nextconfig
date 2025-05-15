<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'name' => 'Thomas Durand',
                'email' => 'thomas.durand@example.com',
                'rating' => 5,
                'comment' => 'Service exceptionnel ! PC parfaitement adapté à mes besoins de streaming et gaming. Rapport qualité/prix imbattable et livraison rapide.',
                'is_approved' => true,
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'Sophie Martin',
                'email' => 'sophie.m@example.com',
                'rating' => 5,
                'comment' => 'PC gaming pour mon fils sans m\'y connaître. Dylan m\'a bien conseillée dans mon budget. Mon fils est ravi des performances !',
                'is_approved' => true,
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'Maxime Lefort',
                'email' => 'max.lefort@example.com',
                'rating' => 4,
                'comment' => 'Très bon service et PC de qualité. Cable management impeccable. J\'aurais aimé plus d\'options esthétiques, mais performances au top.',
                'is_approved' => true,
                'is_featured' => false,
                'order' => 3,
            ],
            [
                'name' => 'Élise Dumont',
                'email' => 'elise.dumont@example.com',
                'rating' => 5,
                'comment' => 'Machine puissante et silencieuse parfaite pour mon travail de graphiste 3D. Idéale pour usage pro et gaming occasionnel.',
                'is_approved' => true,
                'is_featured' => true,
                'order' => 4,
            ],
            [
                'name' => 'Hugo Blanc',
                'email' => 'h.blanc@example.com',
                'rating' => 4,
                'comment' => 'PC reçu rapidement et bien protégé. Fonctionne parfaitement pour les jeux récents. Bon suivi après-vente.',
                'is_approved' => true,
                'is_featured' => false,
                'order' => 5,
            ],
            [
                'name' => 'Camille Rousseau',
                'email' => 'camille.r@example.com',
                'rating' => 5,
                'comment' => 'Setup magnifique avec ce PC sur mesure. Finitions soignées et performances au rendez-vous. Je recommande à 100% !',
                'is_approved' => true,
                'is_featured' => true,
                'order' => 6,
            ],
            [
                'name' => 'Antoine Mercier',
                'email' => 'a.mercier@example.com',
                'rating' => 3,
                'comment' => 'PC fonctionnel mais délai de livraison un peu long. Les performances sont conformes à mes attentes.',
                'is_approved' => true,
                'is_featured' => false,
                'order' => 7,
            ],
            [
                'name' => 'Emma Petit',
                'email' => 'emma.p@example.com',
                'rating' => 5,
                'comment' => 'Parfait pour mon streaming ! Mon ancien PC ne suivait plus, désormais je stream en 1080p sans aucun problème !',
                'is_approved' => true,
                'is_featured' => false,
                'order' => 8,
            ],
            [
                'name' => 'Julien Moreau',
                'email' => 'julien.moreau@example.com',
                'rating' => 4,
                'comment' => 'Très bonne expérience d\'achat. Dylan est disponible et compétent. Configuration idéale pour mon utilisation.',
                'is_approved' => true,
                'is_featured' => false,
                'order' => 9,
            ],
            [
                'name' => 'Claire Dubois',
                'email' => 'claire.d@example.com',
                'rating' => 5,
                'comment' => 'Service impeccable ! Personnalisation RGB sublime et performances exceptionnelles. Merci pour ce travail de qualité.',
                'is_approved' => true,
                'is_featured' => true,
                'order' => 10,
            ],
            // Exemple d'avis non approuvé
            [
                'name' => 'Pierre Lambert',
                'email' => 'p.lambert@example.com',
                'rating' => 2,
                'comment' => 'Contenu inapproprié ou en attente de modération.',
                'is_approved' => false,
                'is_featured' => false,
                'order' => 0,
            ],
        ];

        foreach ($reviews as $reviewData) {
            Review::create($reviewData);
        }
    }
}
