<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Data produk aktual Toko Yery Collection (hasil wawancara pemilik toko).
     * Seluruh stok dicatat dalam satuan lusin.
     */
    private array $products = [
        ['name' => 'Celana Kolor Cargo Twill Printing', 'category' => 'Celana Kolor Cargo', 'size' => '62', 'price' => 312000, 'stock' => 15],
        ['name' => 'Celana Kolor Polos Twill', 'category' => 'Celana Kolor', 'size' => '62', 'price' => 288000, 'stock' => 18],
        ['name' => 'Celana Kolor Polos Despo Hitam', 'category' => 'Celana Kolor', 'size' => '62', 'price' => 276000, 'stock' => 12],
        ['name' => 'Celana Training Panjang Lotto', 'category' => 'Celana Training Panjang', 'size' => '110', 'price' => 300000, 'stock' => 10],
        ['name' => 'Celana Training Panjang Sogo Polos', 'category' => 'Celana Training Panjang', 'size' => '72', 'price' => 336000, 'stock' => 14],
        ['name' => 'Celana Pendek Kolor Kodorai', 'category' => 'Celana Pendek Kolor', 'size' => '62', 'price' => 360000, 'stock' => 20],
        ['name' => 'Celana Training Panjang Scuba Resleting Hitam', 'category' => 'Celana Training Panjang', 'size' => '62', 'price' => 540000, 'stock' => 8],
        ['name' => 'Celana Training Panjang Lotto Printing', 'category' => 'Celana Training Panjang', 'size' => 'Dewasa', 'price' => 720000, 'stock' => 6],
        ['name' => 'Celana Anak Cotton Stretch', 'category' => 'Celana Anak', 'size' => 'All Size', 'price' => 660000, 'stock' => 16],
        ['name' => 'Celana Levis Pendek Anak', 'category' => 'Celana Levis Pendek Anak', 'size' => 'All Size', 'price' => 720000, 'stock' => 9],
        ['name' => 'Celana Anak Embos Hitam', 'category' => 'Celana Anak', 'size' => 'All Size', 'price' => 480000, 'stock' => 11],
        ['name' => 'Celana Pendek Anak Katun Printing', 'category' => 'Celana Pendek Anak', 'size' => 'All Size', 'price' => 420000, 'stock' => 17],
        ['name' => 'Celana Panjang Chino Anak Cotton Stretch', 'category' => 'Celana Chino Anak', 'size' => '112', 'price' => 900000, 'stock' => 5],
        ['name' => 'Celana Levis Anak Badjatex Hitam', 'category' => 'Celana Levis Anak', 'size' => '1-6 Tahun', 'price' => 720000, 'stock' => 7],
        ['name' => 'Celana Panjang Levis Anak Badjatex', 'category' => 'Celana Levis Anak', 'size' => '112', 'price' => 1080000, 'stock' => 4],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->products as $index => $product) {
            Product::create([
                'code' => sprintf('ERY-%03d', $index + 1),
                'name' => $product['name'],
                'category' => $product['category'],
                'size' => $product['size'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'unit' => 'lusin',
            ]);
        }
    }
}
