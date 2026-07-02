<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-denim leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface border border-dashed border-denim/30 rounded-md p-6">
                <form method="POST" action="{{ route('products.update', $product) }}">
                    @csrf
                    @method('PUT')
                    @include('products._form')

                    <div class="flex items-center justify-end gap-4 mt-6">
                        <a href="{{ route('products.index') }}" class="font-sans text-sm text-ink/60 hover:text-denim">Batal</a>
                        <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
