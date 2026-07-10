@if (session('success'))
    <div {{ $attributes->merge(['class' => 'bg-moss/10 text-moss font-sans text-sm px-4 py-3 rounded-md border border-dashed border-moss/40']) }}>
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div {{ $attributes->merge(['class' => 'bg-brick/10 text-brick font-sans text-sm px-4 py-3 rounded-md border border-dashed border-brick/40']) }}>
        {{ session('error') }}
    </div>
@endif
