<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Celana Panjang', 'Celana Pendek', 'Celana Training', 'Celana Kolor'];
        $sizes = ['1-2 Tahun', '3-4 Tahun', '5-6 Tahun', '7-8 Tahun', '9-10 Tahun'];

        $prefixes = [
            'Celana Panjang' => 'CLP',
            'Celana Pendek' => 'CPD',
            'Celana Training' => 'CTR',
            'Celana Kolor' => 'CKL',
        ];

        $basePrices = [
            'Celana Panjang' => 35000,
            'Celana Pendek' => 25000,
            'Celana Training' => 28000,
            'Celana Kolor' => 20000,
        ];

        $baseStocks = [
            'Celana Panjang' => 150,
            'Celana Pendek' => 180,
            'Celana Training' => 200,
            'Celana Kolor' => 220,
        ];

        foreach ($categories as $category) {
            foreach ($sizes as $index => $size) {
                $sequence = $index + 1;

                Product::create([
                    'code' => sprintf('%s-%03d', $prefixes[$category], $sequence),
                    'name' => "{$category} Anak {$size}",
                    'category' => $category,
                    'size' => $size,
                    'price' => $basePrices[$category] + ($index * 2000),
                    'stock' => $baseStocks[$category] - ($index * 10),
                    'unit' => 'pcs',
                ]);
            }
        }
    }
}
