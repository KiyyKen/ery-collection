@php $isLeaf = $node['type'] === 'leaf'; @endphp

@if ($isLeaf)
    <div class="inline-flex items-center gap-2 bg-surface border {{ $node['label'] === 'Laris' ? 'border-moss/30' : 'border-brick/30' }} rounded-md px-3.5 py-2">
        <span class="w-2.5 h-2.5 rounded-full shrink-0 {{ $node['label'] === 'Laris' ? 'bg-moss' : 'bg-brick' }}"></span>
        <span class="font-sans text-sm font-semibold {{ $node['label'] === 'Laris' ? 'text-moss' : 'text-brick' }}">{{ $node['label'] }}</span>
        <span class="font-mono text-xs text-ink/40">({{ $node['count'] }} produk)</span>
    </div>
@else
    <div>
        <div class="inline-block bg-denim text-white rounded-md px-4 py-2.5">
            <p class="font-sans text-sm font-semibold">{{ $node['attribute'] }}</p>
            <p class="font-mono text-[11px] text-white/70 mt-0.5">entropy {{ $node['entropy'] }} &middot; gain ratio {{ $node['gain_ratio'] }}</p>
        </div>

        <ul class="mt-3 pl-6 border-l-2 border-dashed border-denim/25 space-y-4">
            @foreach ($node['branches'] as $value => $branch)
                <li class="relative pl-6 before:content-[''] before:absolute before:left-0 before:top-4 before:w-6 before:border-t-2 before:border-dashed before:border-denim/25">
                    <span class="inline-block font-sans text-xs font-semibold text-thread bg-thread/10 px-2 py-0.5 rounded mb-2">
                        = {{ $value }}
                    </span>
                    <div>
                        @include('predictions._tree', ['node' => $branch])
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endif
