@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-denim/30 focus:border-denim focus:ring-denim rounded-md font-sans text-ink disabled:bg-cream']) }}>
