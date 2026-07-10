<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display font-semibold text-xl text-denim leading-tight">
                {{ __('Prediksi Produk Terlaris') }}
            </h2>
            <form method="POST" action="{{ route('predictions.process') }}">
                @csrf
                <x-primary-button>{{ __('Jalankan Analisis') }}</x-primary-button>
            </form>
        </div>
    </x-slot>

    @php
        $hasResult = $products->contains(fn ($product) => ! is_null($product->predicted_label));
        $larisCount = $products->where('predicted_label', 'Laris')->count();
        $tidakLarisCount = $products->where('predicted_label', 'Tidak Laris')->count();
        $lastClassifiedAt = $products->max('last_classified_at');
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-flash-messages />

            @if (! $hasResult)
                {{-- Empty state --}}
                <div class="bg-surface border border-dashed border-denim/30 rounded-md p-12 text-center">
                    <div class="mx-auto w-12 h-12 rounded-full bg-thread/10 flex items-center justify-center mb-4">
                        <span class="w-3 h-3 rounded-full bg-thread"></span>
                    </div>
                    <p class="font-display font-semibold text-denim text-lg">Belum ada hasil analisis</p>
                    <p class="font-sans text-sm text-ink/60 mt-1">
                        Klik tombol "Jalankan Analisis" untuk memulai klasifikasi.
                    </p>
                </div>
            @else
                {{-- Ringkasan hasil analisis --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div class="bg-surface border border-dashed border-denim/30 rounded-md p-5">
                        <p class="font-sans text-sm text-ink/60">Total Produk</p>
                        <p class="font-mono text-3xl font-medium text-denim mt-1">{{ $products->count() }}</p>
                    </div>
                    <div class="bg-surface border border-dashed border-denim/30 rounded-md p-5">
                        <p class="font-sans text-sm text-ink/60">Produk Laris</p>
                        <p class="font-mono text-3xl font-medium text-moss mt-1">{{ $larisCount }}</p>
                    </div>
                    <div class="bg-surface border border-dashed border-denim/30 rounded-md p-5">
                        <p class="font-sans text-sm text-ink/60">Produk Tidak Laris</p>
                        <p class="font-mono text-3xl font-medium text-brick mt-1">{{ $tidakLarisCount }}</p>
                    </div>
                    <div class="bg-surface border border-dashed border-denim/30 rounded-md p-5">
                        <p class="font-sans text-sm text-ink/60">Akurasi Model</p>
                        <p class="font-mono text-3xl font-medium text-denim mt-1">
                            {{ ! is_null($accuracy) ? $accuracy.'%' : '—' }}
                        </p>
                    </div>
                    <div class="bg-surface border border-dashed border-denim/30 rounded-md p-5">
                        <p class="font-sans text-sm text-ink/60">Analisis Terakhir</p>
                        <p class="font-mono text-sm font-medium text-denim mt-2">
                            {{ $lastClassifiedAt?->translatedFormat('d M Y') ?? '—' }}
                        </p>
                        <p class="font-mono text-xs text-ink/40">
                            {{ $lastClassifiedAt?->translatedFormat('H:i') }}
                        </p>
                    </div>
                </div>

                {{-- Pohon keputusan --}}
                <div class="bg-surface border border-dashed border-denim/30 rounded-md p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                        <h3 class="font-display font-semibold text-denim">Pohon Keputusan Hasil Analisis</h3>
                        <div class="flex items-center gap-4 font-sans text-xs text-ink/60">
                            <span class="inline-flex items-center gap-1.5">🟢 Laris</span>
                            <span class="inline-flex items-center gap-1.5">🔴 Tidak Laris</span>
                        </div>
                    </div>

                    @if ($tree)
                        <div class="overflow-x-auto pb-2">
                            @include('predictions._tree', ['node' => $tree])
                        </div>
                    @else
                        <p class="font-sans text-sm text-ink/50 italic">
                            Visualisasi pohon hanya ditampilkan sesaat setelah "Jalankan Analisis" diklik.
                            Klik tombol tersebut lagi untuk melihat pohon keputusan terbaru.
                        </p>
                    @endif
                </div>
            @endif

            {{-- Tabel hasil klasifikasi produk --}}
            <div class="bg-surface border border-dashed border-denim/30 rounded-md overflow-hidden">
                <div class="px-4 py-3 border-b border-denim/10">
                    <h3 class="font-display font-semibold text-denim">Hasil Klasifikasi per Produk</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-[900px] w-full text-sm">
                        <thead class="bg-denim text-white font-sans">
                            <tr class="text-left">
                                <th class="py-3 px-4 font-medium">Produk</th>
                                <th class="py-3 px-4 font-medium">Kategori</th>
                                <th class="py-3 px-4 font-medium">Ukuran</th>
                                <th class="py-3 px-4 font-medium text-right">Stok</th>
                                <th class="py-3 px-4 font-medium">Label Prediksi</th>
                                <th class="py-3 px-4 font-medium">Rekomendasi</th>
                                <th class="py-3 px-4 font-medium">Terakhir Diklasifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="font-mono text-sm text-ink">
                            @forelse ($products as $product)
                                @php
                                    $isRestockRecommended = $product->predicted_label === 'Laris' && $product->stock < $averageStock;
                                @endphp
                                <tr class="odd:bg-cream even:bg-surface border-b border-denim/10">
                                    <td class="py-3 px-4 font-sans">{{ $product->name }}</td>
                                    <td class="py-3 px-4 font-sans text-ink/60 whitespace-nowrap">{{ $product->category }}</td>
                                    <td class="py-3 px-4 font-sans text-ink/60 whitespace-nowrap">{{ $product->size }}</td>
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
                                            <span class="font-sans text-xs text-ink/40">Belum diklasifikasikan</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 font-sans">
                                        @if ($isRestockRecommended)
                                            <span class="relative inline-flex items-center gap-2 pl-3.5 pr-3 py-1.5 bg-thread/10 text-thread text-xs font-semibold rounded-r-md border border-thread/20 before:content-[''] before:w-2 before:h-2 before:rounded-full before:bg-thread">
                                                Perlu Restock
                                            </span>
                                        @else
                                            <span class="text-xs text-ink/40">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 font-sans text-ink/60 whitespace-nowrap">
                                        {{ $product->last_classified_at?->translatedFormat('d M Y H:i') ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-ink/40 font-sans">Belum ada data produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <p class="text-xs text-ink/40 font-sans">
                Label training (Laris/Tidak Laris) ditentukan dari total kuantitas distribusi tiap produk dibanding rata-rata seluruh produk.
                Atribut yang dipelajari pohon keputusan: kategori, ukuran, dan tingkat frekuensi distribusi (Rendah/Sedang/Tinggi).
                Rekomendasi restock ditampilkan untuk produk berlabel "Laris" dengan stok di bawah rata-rata ({{ $averageStock }} {{ $products->first()?->unit }}).
            </p>
        </div>
    </div>
</x-app-layout>
