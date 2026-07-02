@if ($node['type'] === 'leaf')
    <span class="relative inline-flex items-center gap-1.5 pl-3 pr-2.5 py-1 text-xs font-mono rounded-r-md {{ $node['label'] === 'Laris' ? 'bg-moss/10 text-moss before:bg-moss' : 'bg-brick/10 text-brick before:bg-brick' }} before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full">
        {{ $node['label'] }} ({{ $node['count'] }})
    </span>
@else
    <div>
        <p class="font-sans text-sm text-ink">
            <span class="font-medium text-denim">{{ $node['attribute'] }}</span>
            <span class="text-ink/40 font-mono text-xs">entropy {{ $node['entropy'] }} &middot; gain ratio {{ $node['gain_ratio'] }}</span>
        </p>
        <div class="ml-4 mt-2 space-y-2 border-l border-dashed border-denim/20 pl-4">
            @foreach ($node['branches'] as $value => $branch)
                <div>
                    <p class="font-sans text-xs text-ink/60 mb-1">= {{ $value }}</p>
                    @include('predictions._tree', ['node' => $branch])
                </div>
            @endforeach
        </div>
    </div>
@endif
