<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display font-semibold text-xl text-denim leading-tight">
                {{ __('Data Produk') }}
            </h2>
            <a href="{{ route('products.create') }}">
                <x-primary-button>{{ __('Tambah Produk') }}</x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-flash-messages />

            <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap items-center gap-3">
                <x-text-input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari kode, nama, kategori, atau ukuran..."
                    class="flex-1 min-w-[240px]"
                />
                <x-secondary-button type="submit">{{ __('Cari') }}</x-secondary-button>
                @if ($search)
                    <a href="{{ route('products.index') }}" class="font-sans text-sm text-ink/50 hover:text-denim hover:underline focus:outline-none focus-visible:underline transition-colors">Reset</a>
                @endif
            </form>

            <div class="bg-surface border border-dashed border-denim/30 rounded-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-[880px] w-full text-sm">
                        <thead class="bg-denim text-white font-sans">
                            <tr class="text-left">
                                <th class="py-3 px-4 font-medium">Kode</th>
                                <th class="py-3 px-4 font-medium">Nama</th>
                                <th class="py-3 px-4 font-medium">Kategori</th>
                                <th class="py-3 px-4 font-medium">Ukuran</th>
                                <th class="py-3 px-4 font-medium text-right">Harga</th>
                                <th class="py-3 px-4 font-medium text-right">Stok</th>
                                <th class="py-3 px-4 font-medium">Label</th>
                                <th class="py-3 px-4 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="font-mono text-sm text-ink">
                            @forelse ($products as $product)
                                <tr class="odd:bg-cream even:bg-surface border-b border-denim/10">
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $product->code }}</td>
                                    <td class="py-3 px-4 font-sans">{{ $product->name }}</td>
                                    <td class="py-3 px-4 font-sans text-ink/60 whitespace-nowrap">{{ $product->category }}</td>
                                    <td class="py-3 px-4 font-sans text-ink/60 whitespace-nowrap">{{ $product->size }}</td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">{{ $product->stock }} {{ $product->unit }}</td>
                                    <td class="py-3 px-4">
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
                                    <td class="py-3 px-4 text-right whitespace-nowrap font-sans">
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
                                    <td colspan="8" class="py-6 text-center text-ink/40 font-sans">
                                        {{ $search ? 'Tidak ada produk yang cocok dengan pencarian "'.$search.'".' : 'Belum ada data produk.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($products->hasPages())
                    <div class="px-4 py-3 border-t border-denim/10">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
