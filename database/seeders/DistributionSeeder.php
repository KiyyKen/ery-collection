<?php

namespace Database\Seeders;

use App\Models\Distribution;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DistributionSeeder extends Seeder
{
    /**
     * Profil pergerakan per produk, urutannya mengikuti urutan produk pada ProductSeeder:
     * [jumlah_transaksi, qty_min, qty_max] dalam satuan lusin.
     * Rentang sengaja dijaga tidak tumpang tindih antar tingkat pergerakan
     * (Tinggi/Sedang/Rendah) agar label Laris/Tidak Laris tetap konsisten
     * berapa pun hasil acak kuantitas per transaksi. Dipakai hanya untuk
     * membangkitkan data dummy, bukan disimpan ke database.
     */
    private array $movementProfiles = [
        [10, 4, 6], // 1. Celana Kolor Cargo Twill Printing - Tinggi
        [9, 4, 6],  // 2. Celana Kolor Polos Twill - Tinggi
        [10, 4, 6], // 3. Celana Kolor Polos Despo Hitam - Tinggi
        [5, 1, 2],  // 4. Celana Training Panjang Lotto - Sedang
        [4, 1, 2],  // 5. Celana Training Panjang Sogo Polos - Sedang
        [9, 4, 6],  // 6. Celana Pendek Kolor Kodorai - Tinggi
        [2, 1, 2],  // 7. Celana Training Panjang Scuba Resleting Hitam - Rendah
        [2, 1, 2],  // 8. Celana Training Panjang Lotto Printing - Rendah
        [8, 4, 6],  // 9. Celana Anak Cotton Stretch - Tinggi
        [3, 1, 2],  // 10. Celana Levis Pendek Anak - Rendah
        [5, 1, 2],  // 11. Celana Anak Embos Hitam - Sedang
        [4, 1, 2],  // 12. Celana Pendek Anak Katun Printing - Sedang
        [2, 1, 2],  // 13. Celana Panjang Chino Anak Cotton Stretch - Rendah
        [5, 1, 2],  // 14. Celana Levis Anak Badjatex Hitam - Sedang
        [3, 1, 2],  // 15. Celana Panjang Levis Anak Badjatex - Rendah
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed tetap supaya hasil migrate:fresh --seed selalu identik dan penelitian
        // dapat direproduksi (kuantitas & tanggal per transaksi tidak lagi berbeda-beda antar run).
        fake()->seed(12345);

        $periodStart = Carbon::create(2026, 1, 1);
        $periodEnd = Carbon::create(2026, 6, 30);

        Product::orderBy('id')->get()->each(function (Product $product, int $index) use ($periodStart, $periodEnd) {
            [$transactionCount, $qtyMin, $qtyMax] = $this->movementProfiles[$index] ?? [4, 1, 2];

            for ($i = 0; $i < $transactionCount; $i++) {
                $randomDays = fake()->numberBetween(0, $periodStart->diffInDays($periodEnd));

                Distribution::create([
                    'product_id' => $product->id,
                    'distribution_date' => $periodStart->copy()->addDays($randomDays),
                    'quantity' => fake()->numberBetween($qtyMin, $qtyMax),
                ]);
            }
        });
    }
}
