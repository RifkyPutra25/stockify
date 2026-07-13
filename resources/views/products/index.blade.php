@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-2">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Produk</h1>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('products.export') }}" class="text-white bg-green-700 hover:bg-emerald-700 font-medium rounded-lg text-sm px-4 py-2.5">
            Export
        </a>
        <a href="{{ route('products.import.form') }}" class="text-white bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 font-medium rounded-lg text-sm px-4 py-2.5">
            Import
        </a>
        <a href="{{ route('products.create') }}" class="text-white bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 font-medium rounded-lg text-sm px-4 py-2.5">
            + Tambah Produk
        </a>
    </div>
</div>

    @if (session('success'))
        <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-emerald-50" role="alert">
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
                            <span class="{{ $product->stock <= $product->minimum_stock ? 'text-rose-600 font-semibold' : '' }}">
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
                                <button type="submit" class="font-medium text-rose-600 hover:underline">Hapus</button>
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