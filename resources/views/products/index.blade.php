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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-moss/10 text-moss font-sans text-sm px-4 py-3 rounded-md border border-dashed border-moss/40">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-surface border border-dashed border-denim/30 rounded-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
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
                                            <span class="relative inline-flex items-center gap-1.5 pl-3 pr-2.5 py-1 bg-moss/10 text-moss text-xs rounded-r-md before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-moss">
                                                {{ $product->predicted_label }}
                                            </span>
                                        @elseif ($product->predicted_label)
                                            <span class="relative inline-flex items-center gap-1.5 pl-3 pr-2.5 py-1 bg-brick/10 text-brick text-xs rounded-r-md before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-brick">
                                                {{ $product->predicted_label }}
                                            </span>
                                        @else
                                            <span class="text-xs text-ink/40">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap font-sans">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('products.edit', $product) }}" class="text-denim hover:text-denim-light">Edit</a>
                                            <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-brick hover:text-brick/80">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-6 text-center text-ink/40 font-sans">Belum ada data produk.</td>
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
