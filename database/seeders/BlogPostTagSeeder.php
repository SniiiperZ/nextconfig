<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogPostTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Association des tags aux articles
        $relations = [
            // Article 1 (Carte graphique) a les tags 1 (Gaming PC), 4 (High-End) et 5 (Budget)
            ['blog_post_id' => 1, 'tag_id' => 1],
            ['blog_post_id' => 1, 'tag_id' => 4],
            ['blog_post_id' => 1, 'tag_id' => 5],

            // Article 2 (Boîtiers) a les tags 1 (Gaming PC), 8 (Mini-ITX) et 10 (Design personnalisé)
            ['blog_post_id' => 2, 'tag_id' => 1],
            ['blog_post_id' => 2, 'tag_id' => 8],
            ['blog_post_id' => 2, 'tag_id' => 10],
        ];

        foreach ($relations as $relation) {
            DB::table('blog_post_tag')->insert(array_merge(
                $relation,
                ['created_at' => now(), 'updated_at' => now()]
            ));
        }
    }
}
