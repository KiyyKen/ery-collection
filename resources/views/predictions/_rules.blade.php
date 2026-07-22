@php
    $conditions = $conditions ?? [];
    $attributeLabels = $attributeLabels ?? [];
    $attributeLabel = $attributeLabels[$node['attribute'] ?? null] ?? ($node['attribute'] ?? null);
@endphp

@if ($node['type'] === 'leaf')
    <li class="flex flex-wrap items-center gap-x-2 gap-y-1 font-mono text-xs px-3 py-2 rounded-lg bg-cream/60 border border-denim/10">
        <span class="text-ink/40">IF</span>
        <span class="text-denim">{{ $conditions ? implode(' AND ', $conditions) : 'semua data' }}</span>
        <span class="text-ink/40">THEN Produk diprediksi</span>
        <span class="font-semibold {{ $node['label'] === 'Laris' ? 'text-moss' : 'text-brick' }}">{{ $node['label'] }}</span>
    </li>
@else
    @foreach ($node['branches'] as $value => $branch)
        @include('predictions._rules', [
            'node' => $branch,
            'conditions' => [...$conditions, $attributeLabel.' = '.$value],
            'attributeLabels' => $attributeLabels,
        ])
    @endforeach
@endif
