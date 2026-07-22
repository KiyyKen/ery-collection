<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display font-semibold text-xl text-denim leading-tight">
                {{ __('Barang Keluar') }}
            </h2>
            <a href="{{ route('distributions.create') }}">
                <x-primary-button>{{ __('Tambah Distribusi') }}</x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div
            class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"
            x-data="liveSearch(@js(route('distributions.index')), @js($search ?? ''))"
        >

            <x-flash-messages />

            <form method="GET" action="{{ route('distributions.index') }}" x-on:submit.prevent="fetchResults()" class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[240px]">
                    <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <x-text-input
                        type="text"
                        name="search"
                        x-model="search"
                        x-on:input.debounce.500ms="fetchResults()"
                        placeholder="Cari nama produk, kategori, atau tanggal (mis. 2026-02-01)..."
                        class="w-full pl-10"
                    />
                </div>
                <x-secondary-button type="submit">{{ __('Cari') }}</x-secondary-button>
                <a href="{{ route('distributions.index', ['search' => '']) }}" x-show="search" class="font-sans text-sm text-ink/50 hover:text-denim hover:underline focus:outline-none focus-visible:underline transition-colors">Reset</a>
            </form>

            <div x-ref="results">
                @include('distributions._table', ['distributions' => $distributions, 'search' => $search])
            </div>
        </div>
    </div>
</x-app-layout>
