<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Asgard - PC Gaming RGB Ultimate',
                'description' => 'Configuration haut de gamme avec refroidissement liquide et synchronisation RGB complète. Conçue pour le gaming 4K et le streaming simultané.',
                'specs' => "CPU: Intel Core i9-13900K\nGPU: NVIDIA RTX 4090\nRAM: 64GB DDR5-6000\nStorage: 2TB NVMe Gen4 + 4TB SSD\nCooling: Custom Loop\nCase: Lian Li O11 Dynamic XL\nPrix: 4999.99€",
                'image_path' => 'projects/asgard-main.jpg',
                'is_featured' => true,
                'order' => 1,
                'is_visible' => true,
            ],
            [
                'title' => 'Valhalla - Station de Travail Créative',
                'description' => 'Configuration équilibrée pour création de contenu, modélisation 3D et gaming modéré. Silencieuse et performante.',
                'specs' => "CPU: AMD Ryzen 9 7950X\nGPU: NVIDIA RTX 4080\nRAM: 32GB DDR5-5600\nStorage: 1TB NVMe Gen4 + 2TB SSD\nCooling: Noctua NH-D15\nCase: Fractal Design Meshify 2\nPrix: 3499.99€",
                'image_path' => 'projects/valhalla-main.jpg',
                'is_featured' => true,
                'order' => 2,
                'is_visible' => true,
            ],
            [
                'title' => 'Midgard - PC Gaming Compact',
                'description' => 'Configuration Mini-ITX haute performance sans compromis sur la puissance. Idéale pour les espaces restreints.',
                'specs' => "CPU: Intel Core i7-13700K\nGPU: NVIDIA RTX 4070 Ti\nRAM: 32GB DDR5-5200\nStorage: 1TB NVMe Gen4\nCooling: NZXT Kraken X53\nCase: NZXT H1 V2\nPrix: 2299.99€",
                'image_path' => 'projects/midgard-main.jpg',
                'is_featured' => false,
                'order' => 3,
                'is_visible' => true,
            ],
        ];

        foreach ($projects as $projectData) {
            Project::create($projectData);
        }
    }
}
