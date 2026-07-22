@php
    $isLeaf = $node['type'] === 'leaf';
    $depth = $depth ?? 0;
@endphp

<ul>
    <li>
        @if ($isLeaf)
            <div class="inline-flex flex-col items-center gap-1 bg-surface border {{ $node['label'] === 'Laris' ? 'border-moss/30' : 'border-brick/30' }} rounded-xl shadow-sm px-5 py-3.5 min-w-[7rem]">
                <span class="text-xl leading-none">{{ $node['label'] === 'Laris' ? '🟢' : '🔴' }}</span>
                <span class="font-sans text-sm font-semibold whitespace-nowrap {{ $node['label'] === 'Laris' ? 'text-moss' : 'text-brick' }}">
                    {{ $node['label'] }}
                </span>
                <span class="font-mono text-xs text-ink/40 whitespace-nowrap">{{ $node['count'] }} Produk</span>
            </div>
        @else
            <div class="inline-block bg-denim text-white rounded-xl text-center {{ $depth === 0 ? 'shadow-md px-6 py-4' : 'shadow-sm px-5 py-3' }}">
                <p class="font-sans font-semibold whitespace-nowrap {{ $depth === 0 ? 'text-base' : 'text-sm' }}">{{ $node['attribute'] }}</p>
                <p class="font-mono text-[11px] text-white/70 mt-1 whitespace-nowrap">
                    entropy {{ $node['entropy'] }} &middot; gain ratio {{ $node['gain_ratio'] }}
                </p>
            </div>

            <ul>
                @foreach ($node['branches'] as $value => $branch)
                    <li>
                        <span class="inline-block font-sans text-xs font-semibold text-thread bg-thread/10 border border-thread/20 px-2.5 py-1 rounded-md mb-3 whitespace-nowrap">
                            = {{ $value }}
                        </span>
                        @include('predictions._tree', ['node' => $branch, 'depth' => $depth + 1])
                    </li>
                @endforeach
            </ul>
        @endif
    </li>
</ul>
