<?php

namespace Database\Seeders;

use App\Models\MonthlyRevenue;
use Illuminate\Database\Seeder;

class MonthlyRevenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Données de revenus pour 2024
        $revenues2024 = [
            1 => 5250.75,  // Janvier 2024
            2 => 4820.50,  // Février 2024
            3 => 6150.25,  // Mars 2024
            4 => 7200.00,  // Avril 2024
            5 => 6875.30,  // Mai 2024
            6 => 8125.50,  // Juin 2024
            7 => 7950.75,  // Juillet 2024
            8 => 5475.25,  // Août 2024
            9 => 9225.00,  // Septembre 2024
            10 => 10500.75, // Octobre 2024
            11 => 12750.50, // Novembre 2024
            12 => 15200.25, // Décembre 2024
        ];

        // Données de revenus pour 2025 (premiers mois)
        $revenues2025 = [
            1 => 8750.50,  // Janvier 2025
            2 => 9125.75,  // Février 2025
            3 => 11250.25, // Mars 2025
            4 => 12500.00, // Avril 2025
            5 => 10875.50, // Mai 2025
        ];

        // Insertion des données 2024
        foreach ($revenues2024 as $month => $amount) {
            MonthlyRevenue::create([
                'month' => $month,
                'year' => 2024,
                'amount' => $amount,
                'created_at' => now()->setDate(2024, $month, 15),
                'updated_at' => now()->setDate(2024, $month, 15),
            ]);
        }

        // Insertion des données 2025
        foreach ($revenues2025 as $month => $amount) {
            MonthlyRevenue::create([
                'month' => $month,
                'year' => 2025,
                'amount' => $amount,
                'created_at' => now()->setDate(2025, $month, 15),
                'updated_at' => now()->setDate(2025, $month, 15),
            ]);
        }
    }
}
