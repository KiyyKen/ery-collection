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
        $formatThreshold = fn ($value) => is_null($value) ? '—' : rtrim(rtrim(number_format($value, 2, ',', '.'), '0'), ',');
        $attributeLabels = [
            'category' => 'Kategori Produk',
            'size' => 'Ukuran Produk',
            'frequency_level' => 'Frekuensi Distribusi',
        ];
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-flash-messages />

            @if (! $hasResult)
                {{-- Empty state --}}
                <div class="bg-surface border border-denim/10 rounded-xl shadow-sm p-12 text-center">
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
                    <div class="bg-surface border border-denim/10 rounded-xl shadow-sm p-5">
                        <p class="font-sans text-sm text-ink/60">Total Produk</p>
                        <p class="font-mono text-3xl font-medium text-denim mt-1">{{ $products->count() }}</p>
                    </div>
                    <div class="bg-surface border border-denim/10 rounded-xl shadow-sm p-5">
                        <p class="font-sans text-sm text-ink/60">Produk Laris</p>
                        <p class="font-mono text-3xl font-medium text-moss mt-1">{{ $larisCount }}</p>
                    </div>
                    <div class="bg-surface border border-denim/10 rounded-xl shadow-sm p-5">
                        <p class="font-sans text-sm text-ink/60">Produk Tidak Laris</p>
                        <p class="font-mono text-3xl font-medium text-brick mt-1">{{ $tidakLarisCount }}</p>
                    </div>
                    <div class="bg-surface border border-denim/10 rounded-xl shadow-sm p-5">
                        <p class="font-sans text-sm text-ink/60">Akurasi Model</p>
                        <p class="font-mono text-3xl font-medium text-denim mt-1">
                            {{ ! is_null($accuracy) ? $accuracy.'%' : '—' }}
                        </p>
                    </div>
                    <div class="bg-surface border border-denim/10 rounded-xl shadow-sm p-5">
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
                <div class="bg-surface border border-denim/10 rounded-xl shadow-sm p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                        <h3 class="font-display font-semibold text-denim">Pohon Keputusan Hasil Analisis</h3>
                        <div class="flex items-center gap-4 font-sans text-xs text-ink/60">
                            <span class="inline-flex items-center gap-1.5">🟢 Laris</span>
                            <span class="inline-flex items-center gap-1.5">🔴 Tidak Laris</span>
                        </div>
                    </div>

                    @if ($tree)
                        <div class="decision-tree overflow-x-auto pb-4">
                            @include('predictions._tree', ['node' => $tree])
                        </div>
                    @else
                        <p class="font-sans text-sm text-ink/50 italic">
                            Belum ada hasil analisis.
                            Jalankan analisis untuk menampilkan Decision Tree terbaru.
                        </p>
                    @endif
                </div>

                @if ($tree)
                    {{-- Decision Rules --}}
                    <div class="bg-surface border border-denim/10 rounded-xl shadow-sm p-6">
                        <div class="flex items-center gap-2.5 mb-4">
                            <span class="w-8 h-8 rounded-lg bg-thread/10 text-thread flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </span>
                            <h3 class="font-display font-semibold text-denim">Decision Rules</h3>
                        </div>
                        <ul class="space-y-2">
                            @include('predictions._rules', ['node' => $tree, 'attributeLabels' => $attributeLabels])
                        </ul>
                    </div>

                    {{-- Detail Analisis (collapsible) --}}
                    <div x-data="{ open: false }" class="bg-surface border border-denim/10 rounded-xl shadow-sm overflow-hidden">
                        <button
                            type="button"
                            x-on:click="open = ! open"
                            class="w-full flex items-center justify-between gap-3 px-6 py-4 text-left focus:outline-none"
                        >
                            <span class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-lg bg-denim/5 text-denim flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                                    </svg>
                                </span>
                                <span class="font-display font-semibold text-denim">Detail Analisis</span>
                            </span>
                            <svg
                                :class="open ? 'rotate-180' : ''"
                                class="w-4 h-4 text-ink/40 transition-transform duration-200 shrink-0"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="px-6 pb-6 border-t border-denim/10" style="display: none;">
                            <div class="grid grid-cols-2 gap-3 mb-4 mt-4">
                                <div class="bg-cream/60 rounded-lg px-3 py-2.5 text-center">
                                    <p class="font-mono text-xs text-ink/50">P33</p>
                                    <p class="font-mono text-lg font-semibold text-denim">{{ $formatThreshold($p33) }}</p>
                                </div>
                                <div class="bg-cream/60 rounded-lg px-3 py-2.5 text-center">
                                    <p class="font-mono text-xs text-ink/50">P66</p>
                                    <p class="font-mono text-lg font-semibold text-denim">{{ $formatThreshold($p66) }}</p>
                                </div>
                            </div>
                            <p class="font-sans text-xs text-ink/50 uppercase tracking-wide mb-2">Kategori Frekuensi</p>
                            <ul class="space-y-1.5 font-mono text-xs text-ink/70 mb-3">
                                <li><span class="inline-block w-16 font-semibold text-ink">Rendah</span> : &le; P33</li>
                                <li><span class="inline-block w-16 font-semibold text-ink">Sedang</span> : &gt; P33 sampai &le; P66</li>
                                <li><span class="inline-block w-16 font-semibold text-ink">Tinggi</span> : &gt; P66</li>
                            </ul>
                            <p class="font-sans text-xs text-ink/40">
                                Nilai P33 dan P66 merupakan threshold hasil perhitungan persentil yang digunakan untuk mengelompokkan frekuensi distribusi produk.
                            </p>
                        </div>
                    </div>
                @endif
            @endif

            {{-- Tabel hasil klasifikasi produk --}}
            <div class="bg-surface border border-denim/10 rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-denim/10">
                    <h3 class="font-display font-semibold text-denim">Hasil Klasifikasi per Produk</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-[1020px] w-full text-sm">
                        <thead class="bg-denim text-white font-sans">
                            <tr class="text-left">
                                <th class="py-3 px-4 font-medium">Produk</th>
                                <th class="py-3 px-4 font-medium">Kategori</th>
                                <th class="py-3 px-4 font-medium">Ukuran</th>
                                <th class="py-3 px-4 font-medium">Frekuensi Distribusi</th>
                                <th class="py-3 px-4 font-medium">Label Prediksi</th>
                                <th class="py-3 px-4 font-medium text-right">Stok</th>
                                <th class="py-3 px-4 font-medium">Rekomendasi</th>
                                <th class="py-3 px-4 font-medium">Terakhir Diklasifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="font-mono text-sm text-ink">
                            @forelse ($products as $product)
                                @php
                                    $isRestockRecommended = $product->predicted_label === 'Laris' && $product->stock < $averageStock;
                                    $frequencyLevel = $frequencyLevels[$product->id] ?? null;
                                @endphp
                                <tr class="odd:bg-cream even:bg-surface border-b border-denim/10 hover:bg-denim/5 transition-colors">
                                    <td class="py-3 px-4 font-sans">{{ $product->name }}</td>
                                    <td class="py-3 px-4 font-sans text-ink/60 whitespace-nowrap">{{ $product->category }}</td>
                                    <td class="py-3 px-4 font-sans text-ink/60 whitespace-nowrap">{{ $product->size }}</td>
                                    <td class="py-3 px-4">
                                        @if ($frequencyLevel === 'Tinggi')
                                            <span class="relative inline-flex items-center gap-2 pl-3.5 pr-3 py-1.5 bg-moss/10 text-moss text-xs font-semibold rounded-r-md border border-moss/20 before:content-[''] before:w-2 before:h-2 before:rounded-full before:bg-moss">
                                                {{ $frequencyLevel }}
                                            </span>
                                        @elseif ($frequencyLevel === 'Sedang')
                                            <span class="relative inline-flex items-center gap-2 pl-3.5 pr-3 py-1.5 bg-amber-500/10 text-amber-600 text-xs font-semibold rounded-r-md border border-amber-500/20 before:content-[''] before:w-2 before:h-2 before:rounded-full before:bg-amber-500">
                                                {{ $frequencyLevel }}
                                            </span>
                                        @elseif ($frequencyLevel === 'Rendah')
                                            <span class="relative inline-flex items-center gap-2 pl-3.5 pr-3 py-1.5 bg-blue-500/10 text-blue-600 text-xs font-semibold rounded-r-md border border-blue-500/20 before:content-[''] before:w-2 before:h-2 before:rounded-full before:bg-blue-500">
                                                {{ $frequencyLevel }}
                                            </span>
                                        @else
                                            <span class="text-xs text-ink/40">-</span>
                                        @endif
                                    </td>
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
                                    <td class="py-3 px-4 text-right whitespace-nowrap">{{ $product->stock }} {{ $product->unit }}</td>
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
                                    <td colspan="8" class="py-6 text-center text-ink/40 font-sans">Belum ada data produk.</td>
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
