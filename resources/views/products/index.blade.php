@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6">
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Produk</h1>
        <a href="{{ route('products.create') }}"
   class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
    + Tambah Produk
</a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Gambar</th>
                    <th class="px-6 py-3">Nama</th>
                    <th class="px-6 py-3">SKU</th>
                    <th class="px-6 py-3">Kategori</th>
                    <th class="px-6 py-3">Supplier</th>
                    <th class="px-6 py-3">Harga Jual</th>
                    <th class="px-6 py-3">Stok</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4">
                            @if ($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-xs text-gray-400">-</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">
    {{ $product->name }}
    @if ($product->attributes->isNotEmpty())
        <div class="mt-1 flex flex-wrap gap-1">
            @foreach ($product->attributes as $attr)
                <span class="px-2 py-0.5 rounded-full text-xs font-normal bg-gray-100 text-gray-600">
                    {{ $attr->name }}: {{ $attr->value }}
                </span>
            @endforeach
        </div>
    @endif
</td>
                        <td class="px-6 py-4">{{ $product->sku }}</td>
                        <td class="px-6 py-4">{{ $product->category->name }}</td>
                        <td class="px-6 py-4">{{ $product->supplier->name }}</td>
                        <td class="px-6 py-4">Rp{{ number_format($product->selling_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="{{ $product->stock <= $product->minimum_stock ? 'text-red-600 font-semibold' : '' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('products.edit', $product) }}"
                               class="font-medium text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b">
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection