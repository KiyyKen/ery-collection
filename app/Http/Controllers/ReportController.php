<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Tampilkan laporan distribusi pada rentang tanggal tertentu.
     *
     * Laporan dicetak lewat fitur print bawaan browser (tanpa package PDF tambahan).
     */
    public function index(Request $request)
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now();

        $distributions = Distribution::with('product')
            ->whereBetween('distribution_date', [$from, $to])
            ->orderBy('distribution_date')
            ->get();

        return view('reports.index', compact('distributions', 'from', 'to'));
    }
}
