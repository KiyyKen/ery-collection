<div class="bg-surface border border-denim/10 rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-[840px] w-full text-sm">
            <thead class="bg-denim text-white font-sans">
                <tr class="text-left">
                    <th class="py-3.5 px-5 font-medium">Tanggal</th>
                    <th class="py-3.5 px-5 font-medium">Produk</th>
                    <th class="py-3.5 px-5 font-medium">Kategori</th>
                    <th class="py-3.5 px-5 font-medium">Ukuran</th>
                    <th class="py-3.5 px-5 font-medium text-right">Jumlah</th>
                    <th class="py-3.5 px-5 font-medium">Catatan</th>
                    <th class="py-3.5 px-5 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-mono text-sm text-ink">
                @forelse ($distributions as $distribution)
                    <tr class="odd:bg-cream even:bg-surface border-b border-denim/10 hover:bg-denim/5 transition-colors">
                        <td class="py-3.5 px-5 whitespace-nowrap">{{ $distribution->distribution_date->translatedFormat('d M Y') }}</td>
                        <td class="py-3.5 px-5 font-sans">{{ $distribution->product->name }}</td>
                        <td class="py-3.5 px-5 font-sans text-ink/60 whitespace-nowrap">{{ $distribution->product->category }}</td>
                        <td class="py-3.5 px-5 font-sans text-ink/60 whitespace-nowrap">{{ $distribution->product->size }}</td>
                        <td class="py-3.5 px-5 text-right whitespace-nowrap">{{ $distribution->quantity }} {{ $distribution->product->unit }}</td>
                        <td class="py-3.5 px-5 font-sans text-ink/60">{{ $distribution->notes ?: '-' }}</td>
                        <td class="py-3.5 px-5 text-right whitespace-nowrap font-sans">
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
                        <td colspan="7" class="py-14 text-center font-sans">
                            @if ($search)
                                <div class="mx-auto w-11 h-11 rounded-full bg-denim/5 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5 text-denim/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                                <p class="text-ink/70">Tidak ada distribusi yang cocok dengan pencarian "{{ $search }}".</p>
                                <p class="text-ink/40 text-xs mt-1">Coba kata kunci lain atau reset pencarian.</p>
                            @else
                                <div class="mx-auto w-11 h-11 rounded-full bg-thread/10 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5 text-thread" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                    </svg>
                                </div>
                                <p class="text-ink/70">Belum ada data distribusi.</p>
                                <p class="text-ink/40 text-xs mt-1">Klik "Tambah Distribusi" untuk menambahkan data pertama.</p>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($distributions->hasPages())
        <div class="px-5 py-3 border-t border-denim/10">
            {{ $distributions->links() }}
        </div>
    @endif
</div>
