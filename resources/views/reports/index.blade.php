@extends('layouts.dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Laporan Stok & Transaksi</h1>
    <p class="text-sm text-gray-500 mt-1">Filter dan export data transaksi barang masuk/keluar.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('reports.index') }}" method="GET" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block mb-1 text-xs font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5 w-full">
            </div>
            <div>
                <label class="block mb-1 text-xs font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5 w-full">
            </div>
            <div>
                <label class="block mb-1 text-xs font-medium text-gray-700">Kategori</label>
                <select name="category_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5 w-full">
                    <option value="">Semua</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1 text-xs font-medium text-gray-700">Tipe</label>
                <select name="type" class="bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5 w-full">
                    <option value="">Semua</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Masuk</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Keluar</option>
                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                </select>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 pt-2 border-t border-gray-100">
            <button type="submit" class="text-white bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 font-medium rounded-lg text-sm px-5 py-2.5">
                Filter
            </button>
            <a href="{{ route('reports.export', request()->query()) }}" class="text-white bg-green-700 hover:bg-emerald-700 font-medium rounded-lg text-sm px-5 py-2.5">
                Export CSV
            </a>
            <a href="{{ route('reports.export-pdf', request()->query()) }}" class="text-white bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 font-medium rounded-lg text-sm px-5 py-2.5">
                Export PDF
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Produk</th>
                <th class="px-6 py-3">Kategori</th>
                <th class="px-6 py-3">Tipe</th>
                <th class="px-6 py-3">Jumlah</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Dicatat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $trx)
                <tr class="border-b last:border-0">
                    <td class="px-6 py-4">{{ $trx->date }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $trx->product->name }}</td>
                    <td class="px-6 py-4">{{ $trx->product->category->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($trx->type === 'in')
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">Masuk</span>
                        @elseif($trx->type === 'out')
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-600">Keluar</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-amber-600">Penyesuaian</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $trx->quantity }}</td>
                    <td class="px-6 py-4">{{ ucfirst($trx->status) }}</td>
                    <td class="px-6 py-4">{{ $trx->user->name }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $transactions->links() }}
</div>
@endsection