<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Product;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Tampilkan ringkasan dashboard.
     */
    public function index()
    {
        $totalProducts = Product::count();
        $totalDistributions = Distribution::count();
        $todayDistributions = Distribution::whereDate('distribution_date', today())->count();

        $monthlyTotals = Distribution::selectRaw("DATE_FORMAT(distribution_date, '%Y-%m') as month, SUM(quantity) as total")
            ->where('distribution_date', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->pluck('total', 'month');

        $chartData = collect(range(5, 0))->map(function (int $monthsAgo) use ($monthlyTotals) {
            $period = now()->subMonths($monthsAgo);

            return [
                'label' => $period->translatedFormat('M Y'),
                'total' => (int) ($monthlyTotals[$period->format('Y-m')] ?? 0),
            ];
        });

        $topProducts = Product::withSum('distributions', 'quantity')
            ->orderByDesc('distributions_sum_quantity')
            ->take(5)
            ->get();

        $latestPredictions = Product::whereNotNull('predicted_label')
            ->orderByDesc('last_classified_at')
            ->take(5)
            ->get();

        $distributionTrend = $this->calculateMonthlyDistributionTrend();

        $averageStock = round(Product::avg('stock') ?? 0);

        $restockRecommendations = Product::where('predicted_label', 'Laris')
            ->where('stock', '<', $averageStock)
            ->orderBy('stock')
            ->take(5)
            ->get();

        $lastAnalysis = $this->buildLastAnalysisSummary();

        return view('dashboard', compact(
            'totalProducts',
            'totalDistributions',
            'todayDistributions',
            'chartData',
            'topProducts',
            'latestPredictions',
            'distributionTrend',
            'restockRecommendations',
            'lastAnalysis'
        ));
    }

    /**
     * Hitung persentase perubahan jumlah transaksi distribusi bulan ini
     * dibanding bulan sebelumnya.
     */
    private function calculateMonthlyDistributionTrend(): ?float
    {
        $currentMonthCount = Distribution::whereYear('distribution_date', now()->year)
            ->whereMonth('distribution_date', now()->month)
            ->count();

        $previousMonth = now()->subMonthNoOverflow();

        $previousMonthCount = Distribution::whereYear('distribution_date', $previousMonth->year)
            ->whereMonth('distribution_date', $previousMonth->month)
            ->count();

        if ($previousMonthCount > 0) {
            return round((($currentMonthCount - $previousMonthCount) / $previousMonthCount) * 100, 1);
        }

        return $currentMonthCount > 0 ? 100.0 : null;
    }

    /**
     * Ringkasan analisis C4.5 terakhir: tanggal, jumlah produk, dan akurasi.
     *
     * Akurasi dihitung ulang dari data saat ini (bukan disimpan ke database),
     * dengan formula label yang sama seperti pada proses klasifikasi
     * (total kuantitas produk vs rata-rata seluruh produk).
     */
    private function buildLastAnalysisSummary(): ?array
    {
        $analyzedProducts = Product::whereNotNull('predicted_label')->get();

        if ($analyzedProducts->isEmpty()) {
            return null;
        }

        return [
            'date' => $analyzedProducts->max('last_classified_at'),
            'total' => $analyzedProducts->count(),
            'accuracy' => $this->calculateAccuracy($analyzedProducts),
        ];
    }

    private function calculateAccuracy(Collection $analyzedProducts): float
    {
        $productsWithQuantity = Product::whereIn('id', $analyzedProducts->pluck('id'))
            ->withSum('distributions', 'quantity')
            ->get();

        $averageQuantity = $productsWithQuantity->avg(fn (Product $product) => (int) ($product->distributions_sum_quantity ?? 0));

        $correct = $productsWithQuantity->filter(function (Product $product) use ($averageQuantity) {
            $actualLabel = ((int) ($product->distributions_sum_quantity ?? 0)) >= $averageQuantity ? 'Laris' : 'Tidak Laris';

            return $actualLabel === $product->predicted_label;
        })->count();

        return round(($correct / $productsWithQuantity->count()) * 100, 1);
    }
}
