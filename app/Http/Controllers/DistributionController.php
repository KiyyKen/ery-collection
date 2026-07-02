<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDistributionRequest;
use App\Http\Requests\UpdateDistributionRequest;
use App\Models\Distribution;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $distributions = Distribution::with('product')
            ->latest('distribution_date')
            ->paginate(15);

        return view('distributions.index', compact('distributions'));
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
