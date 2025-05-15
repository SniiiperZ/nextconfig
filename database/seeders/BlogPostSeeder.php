<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogPosts = [
            [
                'title' => 'Comment choisir sa carte graphique en 2025',
                'slug' => 'comment-choisir-sa-carte-graphique-2025',
                'content' => '<h1>Guide complet pour choisir sa carte graphique en 2025</h1>
<p>Avec l\'explosion des besoins en termes de performance graphique, choisir la bonne carte GPU est devenu crucial. Voici les critères essentiels :</p>

<h2>Budget et performances</h2>
<p>La première question à se poser est celle de votre budget. Le marché propose des options allant de 200€ à plus de 2000€. Voici une classification simple :</p>
<ul>
    <li><strong>Budget (200-400€)</strong> : NVIDIA RTX 4060, AMD RX 7600</li>
    <li><strong>Milieu de gamme (400-800€)</strong> : NVIDIA RTX 4070, AMD RX 7700 XT</li>
    <li><strong>Haut de gamme (800-1500€)</strong> : NVIDIA RTX 4080, AMD RX 7900 XT</li>
    <li><strong>Ultra premium (1500€+)</strong> : NVIDIA RTX 4090</li>
</ul>

<h2>Résolution ciblée</h2>
<ul>
    <li><strong>1080p</strong> : Une RTX 4060 ou RX 7600 seront largement suffisantes</li>
    <li><strong>1440p</strong> : Visez au minimum une RTX 4070 ou RX 7700 XT</li>
    <li><strong>4K</strong> : Optez pour une RTX 4080 ou supérieure</li>
</ul>

<h2>Autres considérations</h2>
<ul>
    <li><strong>DLSS/FSR</strong> : Ces technologies d\'upscaling sont essentielles pour maximiser les performances</li>
    <li><strong>Ray Tracing</strong> : Si c\'est important pour vous, les cartes NVIDIA conservent l\'avantage</li>
    <li><strong>VRAM</strong> : Pour le gaming moderne, 8 Go est un minimum, 12-16 Go est recommandé</li>
</ul>

<p>N\'oubliez pas de considérer la compatibilité avec votre alimentation et le format de votre boîtier !</p>',
                'excerpt' => 'Un guide complet pour vous aider à choisir la carte graphique idéale selon votre budget et vos besoins en 2025.',
                'featured_image' => '',
                'is_published' => true,
                'order' => 1,
            ],
            [
                'title' => 'Les meilleurs boîtiers pour PC en 2025',
                'slug' => 'meilleurs-boitiers-pc-2025',
                'content' => '<h1>Les meilleurs boîtiers PC en 2025 - Guide complet</h1>
<p>Le choix du boîtier est souvent négligé, mais il impacte grandement les performances et l\'esthétique de votre configuration.</p>

<h2>Critères de sélection essentiels</h2>

<h3>Flux d\'air</h3>
<p>Un bon flux d\'air est crucial pour maintenir des températures optimales. Recherchez :</p>
<ul>
    <li>Un front mesh ou perforé</li>
    <li>Support pour plusieurs ventilateurs (minimum 3)</li>
    <li>Espaces d\'évacuation d\'air suffisants</li>
</ul>

<h3>Compatibilité des composants</h3>
<p>Vérifiez que le boîtier supporte :</p>
<ul>
    <li>La taille de votre carte mère (ATX, mATX, ITX)</li>
    <li>La longueur de votre carte graphique</li>
    <li>Les dimensions de votre ventirad CPU</li>
    <li>L\'espace pour les radiateurs de watercooling</li>
</ul>

<h3>Gestion des câbles</h3>
<p>Une bonne gestion des câbles améliore non seulement l\'esthétique mais aussi le flux d\'air.</p>

<h2>Recommandations par catégorie</h2>

<h3>Format standard ATX</h3>
<ul>
    <li><strong>Lian Li Lancool III</strong> : Airflow exceptionnel, facilité de montage</li>
    <li><strong>Fractal Design Meshify 2</strong> : Polyvalent et élégant</li>
    <li><strong>Corsair 5000D Airflow</strong> : Spacieux avec excellent refroidissement</li>
</ul>

<h3>Format compact</h3>
<ul>
    <li><strong>NZXT H7 Flow</strong> : Design minimaliste avec bon airflow</li>
    <li><strong>Lian Li O11 Dynamic Mini</strong> : Support watercooling impressionnant</li>
</ul>

<h3>Mini-ITX</h3>
<ul>
    <li><strong>SSUPD Meshroom S</strong> : Révolutionnaire en termes de refroidissement</li>
    <li><strong>Cooler Master NR200P</strong> : Le meilleur rapport qualité/prix</li>
</ul>',
                'excerpt' => 'Découvrez notre sélection des meilleurs boîtiers PC pour tous les budgets et besoins en 2025.',
                'featured_image' => '',
                'is_published' => true,
                'order' => 2,
            ],
        ];

        foreach ($blogPosts as $postData) {
            BlogPost::create($postData);
        }
    }
}
