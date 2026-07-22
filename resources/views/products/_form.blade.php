<div>
    <x-input-label for="code" value="Kode Produk" />
    <x-text-input id="code" name="code" type="text" class="mt-1 block w-full font-mono"
        :value="old('code', $product->code ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('code')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="name" value="Nama Produk" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
        :value="old('name', $product->name ?? '')" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="category" value="Kategori" />
        <x-text-input id="category" name="category" type="text" class="mt-1 block w-full"
            :value="old('category', $product->category ?? '')" required placeholder="Misal: Celana Panjang" />
        <x-input-error :messages="$errors->get('category')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="size" value="Ukuran" />
        <x-text-input id="size" name="size" type="text" class="mt-1 block w-full"
            :value="old('size', $product->size ?? '')" required placeholder="Misal: 3-4 Tahun" />
        <x-input-error :messages="$errors->get('size')" class="mt-2" />
    </div>
</div>

<div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div>
        <x-input-label for="price" value="Harga (Rp)" />
        <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full font-mono"
            :value="old('price', $product->price ?? '')" required />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="stock" value="Stok" />
        <x-text-input id="stock" name="stock" type="number" min="0" class="mt-1 block w-full font-mono"
            :value="old('stock', $product->stock ?? 0)" required />
        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="unit" value="Satuan" />
        <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full"
            :value="old('unit', $product->unit ?? 'lusin')" required />
        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
    </div>
</div>
