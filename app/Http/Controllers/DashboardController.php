<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Product;

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

        return view('dashboard', compact(
            'totalProducts',
            'totalDistributions',
            'todayDistributions',
            'chartData',
            'topProducts',
            'latestPredictions'
        ));
    }
}
