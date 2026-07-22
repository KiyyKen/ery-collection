@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-denim/20 focus:border-denim focus:ring-2 focus:ring-denim/20 rounded-lg font-sans text-ink placeholder:text-ink/40 disabled:bg-cream disabled:text-ink/50 px-3.5 py-2.5 transition-shadow']) }}>
