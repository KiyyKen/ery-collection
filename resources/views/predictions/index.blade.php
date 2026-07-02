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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-moss/10 text-moss font-sans text-sm px-4 py-3 rounded-md border border-dashed border-moss/40">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-brick/10 text-brick font-sans text-sm px-4 py-3 rounded-md border border-dashed border-brick/40">
                    {{ session('error') }}
                </div>
            @endif

            @if ($tree)
                <div class="bg-surface border border-dashed border-denim/30 rounded-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-display font-semibold text-denim">Pohon Keputusan Hasil Analisis</h3>
                        @if (!is_null($accuracy))
                            <span class="font-mono text-sm text-ink/70">Akurasi training: <span class="text-denim font-medium">{{ $accuracy }}%</span> ({{ $totalTraining }} produk)</span>
                        @endif
                    </div>
                    @include('predictions._tree', ['node' => $tree])
                </div>
            @endif

            <div class="bg-surface border border-dashed border-denim/30 rounded-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
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
                                            <span class="relative inline-flex items-center gap-1.5 pl-3 pr-2.5 py-1 bg-moss/10 text-moss text-xs rounded-r-md before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-moss">
                                                {{ $product->predicted_label }}
                                            </span>
                                        @elseif ($product->predicted_label)
                                            <span class="relative inline-flex items-center gap-1.5 pl-3 pr-2.5 py-1 bg-brick/10 text-brick text-xs rounded-r-md before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-brick">
                                                {{ $product->predicted_label }}
                                            </span>
                                        @else
                                            <span class="font-sans text-xs text-ink/40">Belum diklasifikasikan</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 font-sans">
                                        @if ($isRestockRecommended)
                                            <span class="relative inline-flex items-center gap-1.5 pl-3 pr-2.5 py-1 bg-thread/10 text-thread text-xs rounded-r-md before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-thread">
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
