@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6 max-w-2xl">
    <h1 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Edit Produk</h1>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data"
          class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="category_id" class="block mb-1 text-sm font-medium text-gray-900">Kategori</label>
                <select name="category_id" id="category_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="supplier_id" class="block mb-1 text-sm font-medium text-gray-900">Supplier</label>
                <select name="supplier_id" id="supplier_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    <option value="">-- Pilih Supplier --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="name" class="block mb-1 text-sm font-medium text-gray-900">Nama Produk</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
            @error('name')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="sku" class="block mb-1 text-sm font-medium text-gray-900">SKU</label>
            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
            @error('sku')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block mb-1 text-sm font-medium text-gray-900">Deskripsi</label>
            <textarea name="description" id="description" rows="3"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label for="purchase_price" class="block mb-1 text-sm font-medium text-gray-900">Harga Beli</label>
                <input type="number" step="0.01" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                @error('purchase_price')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="selling_price" class="block mb-1 text-sm font-medium text-gray-900">Harga Jual</label>
                <input type="number" step="0.01" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price) }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                @error('selling_price')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="minimum_stock" class="block mb-1 text-sm font-medium text-gray-900">Stok Minimum</label>
                <input type="number" name="minimum_stock" id="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                @error('minimum_stock')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-900">Gambar Saat Ini</label>
            @if ($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded mb-2">
            @else
                <p class="text-sm text-gray-400 mb-2">Belum ada gambar.</p>
            @endif
            <label for="image" class="block mb-1 text-sm font-medium text-gray-900">Ganti Gambar (opsional)</label>
            <input type="file" name="image" id="image" accept="image/*"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
            @error('image')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-900">Atribut Produk (opsional)</label>
            <div id="attribute-list" class="space-y-2">
                @forelse ($product->attributes as $attr)
                    <div class="flex gap-2 attribute-row">
                        <input type="text" name="attribute_name[]" value="{{ $attr->name }}" placeholder="Nama (misal: Ukuran)"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-1/2">
                        <input type="text" name="attribute_value[]" value="{{ $attr->value }}" placeholder="Nilai (misal: XL)"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-1/2">
                    </div>
                @empty
                    <div class="flex gap-2 attribute-row">
                        <input type="text" name="attribute_name[]" placeholder="Nama (misal: Ukuran)"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-1/2">
                        <input type="text" name="attribute_value[]" placeholder="Nilai (misal: XL)"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-1/2">
                    </div>
                @endforelse
            </div>
            <button type="button" id="add-attribute" class="mt-2 text-sm text-blue-600 hover:underline">+ Tambah Atribut</button>
        </div>

        <div class="flex gap-2">
            <button type="submit"
                    class="text-white bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 font-medium rounded-lg text-sm px-5 py-2.5">
                Update
            </button>
            <a href="{{ route('products.index') }}"
               class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('add-attribute').addEventListener('click', function () {
        const list = document.getElementById('attribute-list');
        const row = document.createElement('div');
        row.className = 'flex gap-2 attribute-row';
        row.innerHTML = `
            <input type="text" name="attribute_name[]" placeholder="Nama (misal: Ukuran)" class="bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5 w-1/2">
            <input type="text" name="attribute_value[]" placeholder="Nilai (misal: XL)" class="bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5 w-1/2">
        `;
        list.appendChild(row);
    });
</script>
@endsection