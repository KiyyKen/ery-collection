<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-denim leading-tight">
            {{ __('Laporan Distribusi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="bg-surface border border-dashed border-denim/30 rounded-md p-6 print:hidden">
                <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-end gap-4">
                    <div>
                        <x-input-label for="from" value="Tanggal Awal" />
                        <x-text-input id="from" name="from" type="date" class="mt-1 font-mono" :value="$from->toDateString()" />
                    </div>
                    <div>
                        <x-input-label for="to" value="Tanggal Akhir" />
                        <x-text-input id="to" name="to" type="date" class="mt-1 font-mono" :value="$to->toDateString()" />
                    </div>
                    <x-primary-button type="submit">{{ __('Tampilkan') }}</x-primary-button>
                    <x-secondary-button type="button" onclick="window.print()">
                        {{ __('Cetak') }}
                    </x-secondary-button>
                </form>
            </div>

            <div class="bg-surface border border-dashed border-denim/30 rounded-md p-6">
                <div class="mb-4">
                    <h3 class="font-display font-semibold text-denim">Laporan Distribusi Toko Ery Collection</h3>
                    <p class="font-sans text-sm text-ink/60">
                        Periode: <span class="font-mono">{{ $from->translatedFormat('d M Y') }}</span> &mdash; <span class="font-mono">{{ $to->translatedFormat('d M Y') }}</span>
                    </p>
                </div>

                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-ink/50 border-b border-denim/10 font-sans">
                            <th class="py-2 pr-4 font-medium">Tanggal</th>
                            <th class="py-2 pr-4 font-medium">Produk</th>
                            <th class="py-2 pr-4 font-medium">Kategori</th>
                            <th class="py-2 pr-4 font-medium">Ukuran</th>
                            <th class="py-2 font-medium text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono text-ink">
                        @forelse ($distributions as $distribution)
                            <tr class="border-b last:border-0 border-denim/10">
                                <td class="py-2 pr-4 whitespace-nowrap">{{ $distribution->distribution_date->translatedFormat('d M Y') }}</td>
                                <td class="py-2 pr-4 font-sans">{{ $distribution->product->name }}</td>
                                <td class="py-2 pr-4 font-sans text-ink/60">{{ $distribution->product->category }}</td>
                                <td class="py-2 pr-4 font-sans text-ink/60">{{ $distribution->product->size }}</td>
                                <td class="py-2 text-right">{{ $distribution->quantity }} {{ $distribution->product->unit }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-ink/40 font-sans">Tidak ada data distribusi pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($distributions->isNotEmpty())
                        <tfoot>
                            <tr class="border-t border-denim/20 font-sans font-semibold text-ink">
                                <td colspan="4" class="py-2 pr-4 text-right">Total</td>
                                <td class="py-2 text-right font-mono">{{ $distributions->sum('quantity') }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
