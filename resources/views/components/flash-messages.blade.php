@if (session('success') || session('error'))
    <div class="fixed top-4 right-4 z-50 w-full max-w-sm space-y-3 print:hidden">
        @if (session('success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                {{ $attributes->merge(['class' => 'flex items-start gap-3 bg-surface border-l-4 border-moss rounded-xl shadow-md px-4 py-3.5']) }}
            >
                <svg class="w-5 h-5 text-moss shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l1.5 1.5L15 9.75m5.25 2.25a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-sans text-sm text-ink flex-1">{{ session('success') }}</p>
                <button type="button" x-on:click="show = false" class="text-ink/30 hover:text-ink transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                {{ $attributes->merge(['class' => 'flex items-start gap-3 bg-surface border-l-4 border-brick rounded-xl shadow-md px-4 py-3.5']) }}
            >
                <svg class="w-5 h-5 text-brick shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12V16.5zm9-4.5a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-sans text-sm text-ink flex-1">{{ session('error') }}</p>
                <button type="button" x-on:click="show = false" class="text-ink/30 hover:text-ink transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
    </div>
@endif
