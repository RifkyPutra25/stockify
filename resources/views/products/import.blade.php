@extends('layouts.dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Import Produk</h1>
    <p class="text-sm text-gray-500 mt-1">Upload file CSV untuk menambah/memperbarui produk secara massal.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-xl">
    <div class="mb-4 p-3 bg-teal-50 text-blue-800 text-sm rounded-lg">
        Format CSV harus memiliki kolom (dipisah titik koma <code>;</code>):
        <code>sku;name;category;supplier;description;purchase_price;selling_price;minimum_stock</code>
    </div>

    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-900">File CSV</label>
            <input type="file" name="file" accept=".csv,.txt" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">
            @error('file') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="text-white bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 font-medium rounded-lg text-sm px-5 py-2.5">
                Upload & Import
            </button>
            <a href="{{ route('products.index') }}" class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection