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
                'comment' => 'Service exceptionnel ! Dylan a assemblé un PC parfaitement adapté à mes besoins de streaming et de gaming. Rapport qualité/prix imbattable et livraison ultra rapide.',
                'is_approved' => true,
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'Sophie Martin',
                'email' => 'sophie.m@example.com',
                'rating' => 5,
                'comment' => 'Je cherchais un PC pour mon fils passionné de jeux vidéo sans m\'y connaître. Dylan a su m\'orienter et proposer une configuration dans mon budget. Mon fils est ravi de ses performances !',
                'is_approved' => true,
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'Maxime Lefort',
                'email' => 'max.lefort@example.com',
                'rating' => 4,
                'comment' => 'Très bon service et PC de qualité. Le cable management est impeccable. J\'aurais aimé un peu plus d\'options de personnalisation esthétique, mais je suis très satisfait des performances.',
                'is_approved' => true,
                'is_featured' => false,
                'order' => 3,
            ],
            [
                'name' => 'Élise Dumont',
                'email' => 'elise.dumont@example.com',
                'rating' => 5,
                'comment' => 'En tant que graphiste 3D, j\'avais besoin d\'une machine puissante mais silencieuse. Dylan a relevé le défi haut la main. La configuration est parfaite pour mon usage pro et mes sessions gaming occasionnelles.',
                'is_approved' => true,
                'is_featured' => true,
                'order' => 4,
            ],
            [
                'name' => 'Hugo Blanc',
                'email' => 'h.blanc@example.com',
                'rating' => 4,
                'comment' => 'PC reçu rapidement et bien protégé. Il fonctionne parfaitement pour les jeux récents. Le suivi après-vente est également appréciable.',
                'is_approved' => true,
                'is_featured' => false,
                'order' => 5,
            ],
            [
                'name' => 'Camille Rousseau',
                'email' => 'camille.r@example.com',
                'rating' => 5,
                'comment' => 'Quelle merveille ! Mon setup est magnifique avec ce PC sur mesure. Les finitions sont soignées et les performances au rendez-vous. Je recommande à 100% !',
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
                'comment' => 'Je stream régulièrement et mon ancien PC ne suivait plus. Dylan m\'a proposé une config parfaite pour mon budget. Désormais, je stream en 1080p sans aucun problème !',
                'is_approved' => true,
                'is_featured' => false,
                'order' => 8,
            ],
            [
                'name' => 'Julien Moreau',
                'email' => 'julien.moreau@example.com',
                'rating' => 4,
                'comment' => 'Très bonne expérience d\'achat. Dylan est disponible et compétent. La configuration proposée correspond parfaitement à mon utilisation.',
                'is_approved' => true,
                'is_featured' => false,
                'order' => 9,
            ],
            [
                'name' => 'Claire Dubois',
                'email' => 'claire.d@example.com',
                'rating' => 5,
                'comment' => 'Service impeccable du début à la fin ! La personnalisation RGB est sublime et les performances sont exceptionnelles. Merci pour ce travail de qualité.',
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
