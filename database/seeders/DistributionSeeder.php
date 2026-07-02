<?php

namespace Database\Seeders;

use App\Models\Distribution;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DistributionSeeder extends Seeder
{
    /**
     * Movement profile per product code: [jumlah_transaksi, qty_min, qty_max].
     * Dipakai hanya untuk membangkitkan data dummy yang polanya jelas,
     * bukan disimpan ke database.
     */
    private array $movementProfiles = [
        // Laris (pergerakan tinggi) - ukuran kecil biasanya paling sering dipesan ulang
        'CPD-001' => [9, 20, 40],
        'CPD-002' => [8, 20, 40],
        'CTR-001' => [10, 20, 40],
        'CTR-002' => [9, 20, 40],
        'CKL-001' => [8, 20, 40],
        'CKL-002' => [9, 20, 40],

        // Sedang
        'CLP-001' => [6, 10, 20],
        'CLP-002' => [5, 10, 20],
        'CPD-003' => [6, 10, 20],
        'CPD-004' => [5, 10, 20],
        'CTR-003' => [6, 10, 20],
        'CKL-003' => [5, 10, 20],
        'CKL-004' => [6, 10, 20],

        // Tidak laris (pergerakan rendah) - ukuran besar lebih jarang dipesan
        'CLP-003' => [3, 3, 10],
        'CLP-004' => [2, 3, 10],
        'CLP-005' => [2, 3, 10],
        'CPD-005' => [3, 3, 10],
        'CTR-004' => [2, 3, 10],
        'CTR-005' => [2, 3, 10],
        'CKL-005' => [3, 3, 10],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodStart = Carbon::create(2026, 1, 1);
        $periodEnd = Carbon::create(2026, 6, 30);

        Product::all()->each(function (Product $product) use ($periodStart, $periodEnd) {
            [$transactionCount, $qtyMin, $qtyMax] = $this->movementProfiles[$product->code] ?? [4, 5, 15];

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
