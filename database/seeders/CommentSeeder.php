<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si nous avons des articles de blog
        $blogPosts = BlogPost::all();

        if ($blogPosts->isEmpty()) {
            echo "Aucun article de blog trouvé. Veuillez d'abord exécuter le BlogPostSeeder.\n";
            return;
        }

        $comments = [
            // Commentaires pour le premier article (Carte graphique)
            [
                'blog_post_id' => 1,
                'name' => 'Alexandre Dupont',
                'email' => 'alex.dupont@example.com',
                'content' => "Merci pour cet article très complet ! J'hésite entre une RTX 4070 et une RX 7700 XT pour jouer en 1440p. Laquelle recommanderiez-vous si le ray tracing n'est pas ma priorité ?",
                'is_approved' => true,
            ],
            [
                'blog_post_id' => 1,
                'name' => 'Marie Laurent',
                'email' => 'marie.l@example.com',
                'content' => "Super article ! Une question : est-ce que les 8 Go de VRAM de la RTX 4060 sont vraiment suffisants pour les jeux récents en 1080p ? J'ai entendu dire que certains jeux dépassent déjà cette limite.",
                'is_approved' => true,
            ],
            [
                'blog_post_id' => 1,
                'name' => 'Julien Ricard',
                'email' => 'julien.r@example.com',
                'content' => "Vous ne parlez pas des cartes Intel Arc. Elles sont pourtant très intéressantes en termes de rapport qualité/prix, notamment l'Arc A770 qui se positionne face à la RTX 4060.",
                'is_approved' => true,
            ],
            [
                'blog_post_id' => 1,
                'name' => 'Laurent Girard',
                'email' => 'l.girard@example.com',
                'content' => "J'ai récemment acheté une RTX 4090 et j'ai été surpris par sa consommation électrique ! N'oubliez pas de mentionner ce point important pour les utilisateurs qui envisagent un achat haut de gamme.",
                'is_approved' => false,
            ],

            // Commentaires pour le deuxième article (Boîtiers)
            [
                'blog_post_id' => 2,
                'name' => 'Sarah Benoit',
                'email' => 'sarah.b@example.com',
                'content' => "J'utilise le Fractal Design Meshify 2 depuis 6 mois et je ne peux que confirmer votre recommandation. L'airflow est excellent et le montage est un vrai plaisir !",
                'is_approved' => true,
            ],
            [
                'blog_post_id' => 2,
                'name' => 'Nicolas Martin',
                'email' => 'nico.martin@example.com',
                'content' => "Avez-vous testé le Corsair 7000D ? Je cherche un boîtier vraiment spacieux pour un watercooling custom avec plusieurs radiateurs.",
                'is_approved' => true,
            ],
            [
                'blog_post_id' => 2,
                'name' => 'Émilie Rousseau',
                'email' => 'emilie.r@example.com',
                'content' => "Excellente sélection ! Pour le format ITX, j'ajouterais le Lian Li Dan A4-H2O qui est parfait pour les builds compacts avec AIO.",
                'is_approved' => true,
            ],
            [
                'blog_post_id' => 2,
                'name' => 'Thomas Leroy',
                'email' => 'thomas.l@example.com',
                'content' => "Comment gérez-vous les problèmes de poussière avec ces boîtiers à fort airflow ? Avez-vous des recommandations pour l'entretien à long terme ?",
                'is_approved' => true,
            ],
            [
                'blog_post_id' => 2,
                'name' => 'Lucie Moreau',
                'email' => 'lucie.m@example.com',
                'content' => "Je cherche un boîtier élégant pour mon bureau, plutôt minimaliste. Le NZXT H7 Flow semble intéressant mais avez-vous d'autres suggestions qui privilégient le design ?",
                'is_approved' => true,
            ],
            [
                'blog_post_id' => 2,
                'name' => 'Pierre Lefebvre',
                'email' => 'p.lefebvre@example.com',
                'content' => "C'est quoi ce site de m**** ? Allez voir ailleurs pour des conseils de vrais pros !",
                'is_approved' => false,
            ],
        ];

        foreach ($comments as $commentData) {
            // Vérifier si l'article existe avant de créer le commentaire
            if (BlogPost::find($commentData['blog_post_id'])) {
                Comment::create($commentData);
            }
        }
    }
}
