<div class="bg-surface border border-denim/10 rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-[880px] w-full text-sm">
            <thead class="bg-denim text-white font-sans">
                <tr class="text-left">
                    <th class="py-3.5 px-5 font-medium">Kode</th>
                    <th class="py-3.5 px-5 font-medium">Nama</th>
                    <th class="py-3.5 px-5 font-medium">Kategori</th>
                    <th class="py-3.5 px-5 font-medium">Ukuran</th>
                    <th class="py-3.5 px-5 font-medium text-right">Harga</th>
                    <th class="py-3.5 px-5 font-medium text-right">Stok</th>
                    <th class="py-3.5 px-5 font-medium">Label</th>
                    <th class="py-3.5 px-5 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-mono text-sm text-ink">
                @forelse ($products as $product)
                    <tr class="odd:bg-cream even:bg-surface border-b border-denim/10 hover:bg-denim/5 transition-colors">
                        <td class="py-3.5 px-5 whitespace-nowrap">{{ $product->code }}</td>
                        <td class="py-3.5 px-5 font-sans">{{ $product->name }}</td>
                        <td class="py-3.5 px-5 font-sans text-ink/60 whitespace-nowrap">{{ $product->category }}</td>
                        <td class="py-3.5 px-5 font-sans text-ink/60 whitespace-nowrap">{{ $product->size }}</td>
                        <td class="py-3.5 px-5 text-right whitespace-nowrap">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="py-3.5 px-5 text-right whitespace-nowrap">{{ $product->stock }} {{ $product->unit }}</td>
                        <td class="py-3.5 px-5">
                            @if ($product->predicted_label === 'Laris')
                                <span class="relative inline-flex items-center gap-2 pl-3.5 pr-3 py-1.5 bg-moss/10 text-moss text-xs font-semibold rounded-r-md border border-moss/20 before:content-[''] before:w-2 before:h-2 before:rounded-full before:bg-moss">
                                    {{ $product->predicted_label }}
                                </span>
                            @elseif ($product->predicted_label)
                                <span class="relative inline-flex items-center gap-2 pl-3.5 pr-3 py-1.5 bg-brick/10 text-brick text-xs font-semibold rounded-r-md border border-brick/20 before:content-[''] before:w-2 before:h-2 before:rounded-full before:bg-brick">
                                    {{ $product->predicted_label }}
                                </span>
                            @else
                                <span class="text-xs text-ink/40">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-right whitespace-nowrap font-sans">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('products.edit', $product) }}" class="text-denim hover:text-denim-light hover:underline focus:outline-none focus-visible:underline transition-colors">Edit</a>
                                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-brick hover:text-brick/80 hover:underline focus:outline-none focus-visible:underline transition-colors">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-14 text-center font-sans">
                            @if ($search)
                                <div class="mx-auto w-11 h-11 rounded-full bg-denim/5 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5 text-denim/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                                <p class="text-ink/70">Tidak ada produk yang cocok dengan pencarian "{{ $search }}".</p>
                                <p class="text-ink/40 text-xs mt-1">Coba kata kunci lain atau reset pencarian.</p>
                            @else
                                <div class="mx-auto w-11 h-11 rounded-full bg-thread/10 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5 text-thread" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-8.25-4.5L3.75 7.5m16.5 0l-8.25 4.5m8.25-4.5v9l-8.25 4.5M3.75 7.5l8.25 4.5m-8.25-4.5v9l8.25 4.5m0-9v9" />
                                    </svg>
                                </div>
                                <p class="text-ink/70">Belum ada data produk.</p>
                                <p class="text-ink/40 text-xs mt-1">Klik "Tambah Produk" untuk menambahkan data pertama.</p>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($products->hasPages())
        <div class="px-5 py-3 border-t border-denim/10">
            {{ $products->links() }}
        </div>
    @endif
</div>
