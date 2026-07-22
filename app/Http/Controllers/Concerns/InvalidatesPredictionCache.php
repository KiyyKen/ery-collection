<?php

namespace App\Http\Controllers\Concerns;

trait InvalidatesPredictionCache
{
    /**
     * Hapus hasil analisis C4.5 yang tersimpan di session karena data Produk/Distribusi
     * berubah, supaya pengguna tidak melihat Decision Tree/akurasi yang sudah usang.
     */
    private function forgetPredictionCache(): void
    {
        session()->forget([
            'c45_tree',
            'c45_accuracy',
            'c45_total',
            'c45_p33',
            'c45_p66',
            'c45_average_quantity',
            'c45_frequency_levels',
            'c45_analyzed_at',
        ]);
    }
}
