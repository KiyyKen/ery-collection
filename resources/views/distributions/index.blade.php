<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display font-semibold text-xl text-denim leading-tight">
                {{ __('Barang Keluar') }}
            </h2>
            <a href="{{ route('distributions.create') }}">
                <x-primary-button>{{ __('Tambah Distribusi') }}</x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-flash-messages />

            <form method="GET" action="{{ route('distributions.index') }}" class="flex flex-wrap items-center gap-3">
                <x-text-input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama produk, kategori, atau tanggal (mis. 2026-02-01)..."
                    class="flex-1 min-w-[240px]"
                />
                <x-secondary-button type="submit">{{ __('Cari') }}</x-secondary-button>
                @if ($search)
                    <a href="{{ route('distributions.index') }}" class="font-sans text-sm text-ink/50 hover:text-denim hover:underline focus:outline-none focus-visible:underline transition-colors">Reset</a>
                @endif
            </form>

            <div class="bg-surface border border-dashed border-denim/30 rounded-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-[840px] w-full text-sm">
                        <thead class="bg-denim text-white font-sans">
                            <tr class="text-left">
                                <th class="py-3 px-4 font-medium">Tanggal</th>
                                <th class="py-3 px-4 font-medium">Produk</th>
                                <th class="py-3 px-4 font-medium">Kategori</th>
                                <th class="py-3 px-4 font-medium">Ukuran</th>
                                <th class="py-3 px-4 font-medium text-right">Jumlah</th>
                                <th class="py-3 px-4 font-medium">Catatan</th>
                                <th class="py-3 px-4 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="font-mono text-sm text-ink">
                            @forelse ($distributions as $distribution)
                                <tr class="odd:bg-cream even:bg-surface border-b border-denim/10">
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $distribution->distribution_date->translatedFormat('d M Y') }}</td>
                                    <td class="py-3 px-4 font-sans">{{ $distribution->product->name }}</td>
                                    <td class="py-3 px-4 font-sans text-ink/60 whitespace-nowrap">{{ $distribution->product->category }}</td>
                                    <td class="py-3 px-4 font-sans text-ink/60 whitespace-nowrap">{{ $distribution->product->size }}</td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">{{ $distribution->quantity }} {{ $distribution->product->unit }}</td>
                                    <td class="py-3 px-4 font-sans text-ink/60">{{ $distribution->notes ?: '-' }}</td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap font-sans">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('distributions.edit', $distribution) }}" class="text-denim hover:text-denim-light hover:underline focus:outline-none focus-visible:underline transition-colors">Edit</a>
                                            <form method="POST" action="{{ route('distributions.destroy', $distribution) }}" onsubmit="return confirm('Hapus data distribusi ini? Stok produk akan dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-brick hover:text-brick/80 hover:underline focus:outline-none focus-visible:underline transition-colors">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-ink/40 font-sans">
                                        {{ $search ? 'Tidak ada distribusi yang cocok dengan pencarian "'.$search.'".' : 'Belum ada data distribusi.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($distributions->hasPages())
                    <div class="px-4 py-3 border-t border-denim/10">
                        {{ $distributions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
