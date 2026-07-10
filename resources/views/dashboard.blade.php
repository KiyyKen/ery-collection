<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-denim leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-flash-messages />

            {{-- Ringkasan --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-surface border border-dashed border-denim/30 rounded-md p-5">
                    <p class="font-sans text-sm text-ink/60">Total Produk</p>
                    <p class="font-mono text-3xl font-medium text-denim mt-1">{{ $totalProducts }}</p>
                </div>
                <div class="bg-surface border border-dashed border-denim/30 rounded-md p-5">
                    <p class="font-sans text-sm text-ink/60">Total Distribusi</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <p class="font-mono text-3xl font-medium text-denim">{{ $totalDistributions }}</p>
                        @if (! is_null($distributionTrend))
                            <span class="inline-flex items-center gap-0.5 font-mono text-xs font-semibold {{ $distributionTrend >= 0 ? 'text-moss' : 'text-brick' }}">
                                {{ $distributionTrend >= 0 ? '▲' : '▼' }}{{ abs($distributionTrend) }}%
                            </span>
                        @endif
                    </div>
                    @if (! is_null($distributionTrend))
                        <p class="font-sans text-xs text-ink/40 mt-1">dibanding bulan lalu</p>
                    @endif
                </div>
                <div class="bg-surface border border-dashed border-denim/30 rounded-md p-5">
                    <p class="font-sans text-sm text-ink/60">Distribusi Hari Ini</p>
                    <p class="font-mono text-3xl font-medium text-denim mt-1">{{ $todayDistributions }}</p>
                </div>
            </div>

            {{-- Grafik --}}
            <div class="bg-surface border border-dashed border-denim/30 rounded-md p-6">
                <h3 class="font-display font-semibold text-denim mb-6">Grafik Distribusi 6 Bulan Terakhir</h3>

                @php $max = max($chartData->max('total'), 1); @endphp

                <div class="flex items-end gap-3 sm:gap-6 h-48">
                    @foreach ($chartData as $item)
                        <div class="flex-1 flex flex-col items-center justify-end h-full">
                            <span class="font-mono text-xs text-ink/60 mb-1">{{ $item['total'] }}</span>
                            <div
                                class="w-full max-w-12 bg-thread rounded-t transition-all"
                                style="height: {{ $item['total'] > 0 ? max(($item['total'] / $max) * 100, 4) : 0 }}%"
                            ></div>
                            <span class="font-sans text-xs text-ink/50 mt-2">{{ $item['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Analisis Terakhir --}}
                <div class="bg-surface border border-dashed border-denim/30 rounded-md p-6">
                    <h3 class="font-display font-semibold text-denim mb-4">Analisis Terakhir</h3>

                    @if ($lastAnalysis)
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <p class="font-sans text-xs text-ink/50">Tanggal</p>
                                <p class="font-mono text-sm font-medium text-denim mt-1.5">
                                    {{ $lastAnalysis['date']->translatedFormat('d M Y') }}
                                </p>
                                <p class="font-mono text-xs text-ink/40">{{ $lastAnalysis['date']->translatedFormat('H:i') }}</p>
                            </div>
                            <div>
                                <p class="font-sans text-xs text-ink/50">Produk Dianalisis</p>
                                <p class="font-mono text-2xl font-medium text-denim mt-1">{{ $lastAnalysis['total'] }}</p>
                            </div>
                            <div>
                                <p class="font-sans text-xs text-ink/50">Akurasi Model</p>
                                <p class="font-mono text-2xl font-medium text-moss mt-1">{{ $lastAnalysis['accuracy'] }}%</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="mx-auto w-10 h-10 rounded-full bg-thread/10 flex items-center justify-center mb-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-thread"></span>
                            </div>
                            <p class="font-sans text-sm text-ink/60">Belum ada analisis yang dijalankan.</p>
                            <a href="{{ route('predictions.index') }}" class="inline-block mt-2 font-sans text-sm font-medium text-thread hover:text-thread-light hover:underline focus:outline-none focus-visible:underline transition-colors">
                                Jalankan Analisis &rarr;
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Produk Perlu Restock --}}
                <div class="bg-surface border border-dashed border-denim/30 rounded-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-display font-semibold text-denim">Produk Perlu Restock</h3>
                        <a href="{{ route('predictions.index') }}" class="font-sans text-sm text-thread hover:text-thread-light hover:underline focus:outline-none focus-visible:underline transition-colors">Lihat semua</a>
                    </div>
                    <ul class="divide-y divide-denim/10">
                        @forelse ($restockRecommendations as $product)
                            <li class="py-2.5 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-sans text-sm text-ink truncate">{{ $product->name }}</p>
                                    <p class="font-mono text-xs text-ink/40">Stok: {{ $product->stock }} {{ $product->unit }}</p>
                                </div>
                                <span class="relative inline-flex items-center gap-2 pl-3.5 pr-3 py-1.5 bg-thread/10 text-thread text-xs font-semibold rounded-r-md border border-thread/20 whitespace-nowrap shrink-0 before:content-[''] before:w-2 before:h-2 before:rounded-full before:bg-thread">
                                    Perlu Restock
                                </span>
                            </li>
                        @empty
                            <li class="py-6 text-center text-ink/40 font-sans text-sm">
                                @if (! $lastAnalysis)
                                    Jalankan analisis dulu untuk melihat rekomendasi restock.
                                @else
                                    Semua produk "Laris" stoknya masih di atas rata-rata.
                                @endif
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Produk Terlaris --}}
                <div class="bg-surface border border-dashed border-denim/30 rounded-md p-6">
                    <h3 class="font-display font-semibold text-denim mb-4">Produk Terlaris (Top 5)</h3>
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-ink/50 border-b border-denim/10">
                                <th class="py-2 pr-4 font-sans font-medium">Produk</th>
                                <th class="py-2 pr-4 font-sans font-medium">Kategori</th>
                                <th class="py-2 font-sans font-medium text-right">Total Terdistribusi</th>
                            </tr>
                        </thead>
                        <tbody class="font-mono">
                            @forelse ($topProducts as $product)
                                <tr class="border-b last:border-0 border-denim/10">
                                    <td class="py-2 pr-4 text-ink">{{ $product->name }}</td>
                                    <td class="py-2 pr-4 text-ink/60 font-sans">{{ $product->category }}</td>
                                    <td class="py-2 text-right text-ink">{{ $product->distributions_sum_quantity ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-6 text-center text-ink/40 font-sans">Belum ada data distribusi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Hasil Prediksi Terbaru --}}
                <div class="bg-surface border border-dashed border-denim/30 rounded-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-display font-semibold text-denim">Hasil Prediksi Terbaru</h3>
                        <a href="{{ route('predictions.index') }}" class="font-sans text-sm text-thread hover:text-thread-light hover:underline focus:outline-none focus-visible:underline transition-colors">Lihat semua</a>
                    </div>
                    <ul class="divide-y divide-denim/10">
                        @forelse ($latestPredictions as $product)
                            <li class="py-2 flex items-center justify-between gap-3">
                                <span class="font-sans text-ink truncate min-w-0">{{ $product->name }}</span>
                                @if ($product->predicted_label === 'Laris')
                                    <span class="relative inline-flex items-center gap-2 pl-3.5 pr-3 py-1.5 bg-moss/10 text-moss text-xs font-semibold rounded-r-md border border-moss/20 whitespace-nowrap shrink-0 before:content-[''] before:w-2 before:h-2 before:rounded-full before:bg-moss">
                                        {{ $product->predicted_label }}
                                    </span>
                                @else
                                    <span class="relative inline-flex items-center gap-2 pl-3.5 pr-3 py-1.5 bg-brick/10 text-brick text-xs font-semibold rounded-r-md border border-brick/20 whitespace-nowrap shrink-0 before:content-[''] before:w-2 before:h-2 before:rounded-full before:bg-brick">
                                        {{ $product->predicted_label }}
                                    </span>
                                @endif
                            </li>
                        @empty
                            <li class="py-6 text-center text-ink/40 font-sans text-sm">
                                Belum ada hasil prediksi. Jalankan analisis di menu Prediksi.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
