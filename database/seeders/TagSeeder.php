<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Gaming PC', 'slug' => 'gaming-pc'],
            ['name' => 'PC Bureautique', 'slug' => 'pc-bureautique'],
            ['name' => 'Watercooling', 'slug' => 'watercooling'],
            ['name' => 'High-End', 'slug' => 'high-end'],
            ['name' => 'Budget', 'slug' => 'budget'],
            ['name' => 'RGB', 'slug' => 'rgb'],
            ['name' => 'Overclocking', 'slug' => 'overclocking'],
            ['name' => 'Mini-ITX', 'slug' => 'mini-itx'],
            ['name' => 'Streaming', 'slug' => 'streaming'],
            ['name' => 'Design personnalisé', 'slug' => 'design-personnalise'],
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(['slug' => $tag['slug']], $tag);
        }
    }
}
