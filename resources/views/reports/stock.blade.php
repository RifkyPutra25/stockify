@extends('layouts.dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Laporan Stok Barang</h1>
    <p class="text-sm text-gray-500 mt-1">Kondisi stok terkini seluruh produk.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('reports.stock') }}" method="GET" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block mb-1 text-xs font-medium text-gray-700">Kategori</label>
            <select name="category_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                <option value="">Semua</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="text-white bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 font-medium rounded-lg text-sm px-5 py-2.5">
            Filter
        </button>
        <a href="{{ route('reports.stock-export', request()->query()) }}" class="text-white bg-green-700 hover:bg-emerald-700 font-medium rounded-lg text-sm px-5 py-2.5">
            Export CSV
        </a>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th class="px-6 py-3">SKU</th>
                <th class="px-6 py-3">Produk</th>
                <th class="px-6 py-3">Kategori</th>
                <th class="px-6 py-3">Supplier</th>
                <th class="px-6 py-3">Stok</th>
                <th class="px-6 py-3">Minimum</th>
                <th class="px-6 py-3">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $p)
                <tr class="border-b last:border-0">
                    <td class="px-6 py-4">{{ $p->sku }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $p->name }}</td>
                    <td class="px-6 py-4">{{ $p->category->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $p->supplier->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $p->stock }}</td>
                    <td class="px-6 py-4">{{ $p->minimum_stock }}</td>
                    <td class="px-6 py-4">
                        @if($p->stock <= $p->minimum_stock)
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-600">Menipis</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">Aman</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection