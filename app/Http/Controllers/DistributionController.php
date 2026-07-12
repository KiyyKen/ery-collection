<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDistributionRequest;
use App\Http\Requests\UpdateDistributionRequest;
use App\Models\Distribution;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->has('search') ? $request->query('search') : session('distribution_search');

        session(['distribution_search' => $search]);

        $distributions = Distribution::with('product')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('category', 'like', "%{$search}%");
                    });

                    if ($date = $this->parseSearchDate($search)) {
                        $q->orWhereDate('distribution_date', $date);
                    }
                });
            })
            ->latest('distribution_date')
            ->paginate(10)
            ->appends(['search' => $search]);

        if ($request->ajax()) {
            return view('distributions._table', compact('distributions', 'search'));
        }

        return view('distributions.index', compact('distributions', 'search'));
    }

    /**
     * Coba pecah string pencarian jadi tanggal yang valid (mis. "2026-02-01", "01/02/2026").
     * Hanya dicoba kalau polanya memang menyerupai tanggal, biar kata kunci non-tanggal
     * tidak salah tafsir jadi tanggal oleh Carbon::parse().
     */
    private function parseSearchDate(string $value): ?string
    {
        if (! preg_match('/\d{1,4}[-\/]\d{1,2}([-\/]\d{1,4})?/', $value)) {
            return null;
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('distributions.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * Stok produk berkurang sebesar quantity yang didistribusikan.
     */
    public function store(StoreDistributionRequest $request)
    {
        DB::transaction(function () use ($request) {
            $product = Product::findOrFail($request->product_id);

            Distribution::create($request->validated());

            $product->decrement('stock', $request->quantity);
        });

        return redirect()->route('distributions.index')->with('success', 'Data distribusi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Distribution $distribution)
    {
        $distribution->load('product');

        return view('distributions.edit', compact('distribution'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Stok lama dikembalikan lebih dulu, lalu dikurangi sesuai quantity baru.
     * Produk tidak bisa diganti saat edit.
     */
    public function update(UpdateDistributionRequest $request, Distribution $distribution)
    {
        DB::transaction(function () use ($request, $distribution) {
            $product = $distribution->product;
            $oldQuantity = $distribution->quantity;

            $distribution->update($request->validated());

            $product->increment('stock', $oldQuantity);
            $product->decrement('stock', $request->quantity);
        });

        return redirect()->route('distributions.index')->with('success', 'Data distribusi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * Stok produk dikembalikan sebesar quantity distribusi yang dihapus.
     */
    public function destroy(Distribution $distribution)
    {
        DB::transaction(function () use ($distribution) {
            $distribution->product->increment('stock', $distribution->quantity);
            $distribution->delete();
        });

        return redirect()->route('distributions.index')->with('success', 'Data distribusi berhasil dihapus.');
    }
}
