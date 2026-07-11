@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6">
    <h1 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Transaksi Stok</h1>

    @if (session('success'))
        <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-emerald-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-rose-50" role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-3 gap-4 mb-6 items-stretch">
        <div class="bg-white p-6 rounded-lg shadow flex flex-col">
            <h2 class="text-lg font-semibold mb-4 text-emerald-600">Barang Masuk</h2>
            <form action="{{ route('stock-transactions.in') }}" method="POST" class="space-y-3 flex flex-col flex-1">
                @csrf
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Produk</label>
                    <select name="product_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Jumlah</label>
                    <input type="number" name="quantity" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Catatan</label>
                    <input type="text" name="notes" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <button type="submit" class="text-white bg-green-700 hover:bg-emerald-700 font-medium rounded-lg text-sm px-5 py-2.5 w-full mt-auto">
                    Catat Barang Masuk
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow flex flex-col">
            <h2 class="text-lg font-semibold mb-4 text-rose-600">Barang Keluar</h2>
            <form action="{{ route('stock-transactions.out') }}" method="POST" class="space-y-3 flex flex-col flex-1">
                @csrf
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Produk</label>
                    <select name="product_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Jumlah</label>
                    <input type="number" name="quantity" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Catatan</label>
                    <input type="text" name="notes" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <button type="submit" class="text-white bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 font-medium rounded-lg text-sm px-5 py-2.5 w-full mt-auto">
                    Catat Barang Keluar
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow flex flex-col">
            <h2 class="text-lg font-semibold mb-4 text-amber-600">Stock Opname</h2>
            <form action="{{ route('stock-transactions.opname') }}" method="POST" class="space-y-3 flex flex-col flex-1">
                @csrf
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Produk</label>
                    <select name="product_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Sistem: {{ $product->stock }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Stok Fisik (Hasil Hitung)</label>
                    <input type="number" name="actual_stock" min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Catatan</label>
                    <input type="text" name="notes" placeholder="Opsional" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <button type="submit" class="text-white bg-yellow-600 hover:bg-amber-600 font-medium rounded-lg text-sm px-5 py-2.5 w-full mt-auto">
                    Catat Penyesuaian
                </button>
            </form>
        </div>
    </div>

    <h2 class="text-lg font-semibold mb-3">Riwayat Transaksi</h2>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Produk</th>
                    <th class="px-6 py-3">Tipe</th>
                    <th class="px-6 py-3">Jumlah</th>
                    <th class="px-6 py-3">Dicatat Oleh</th>
                    <th class="px-6 py-3">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $trx)
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4">{{ $trx->date }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $trx->product->name }}</td>
                        <td class="px-6 py-4">
                            @if ($trx->type === 'in')
                                <span class="text-emerald-600 font-semibold">Masuk</span>
                            @elseif ($trx->type === 'out')
                                <span class="text-rose-600 font-semibold">Keluar</span>
                            @else
                                <span class="text-amber-600 font-semibold">Penyesuaian</span>
                                @if($trx->status === 'rejected')
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">Ditolak</span>
                            @if($trx->rejection_reason)
                                <p class="text-xs text-gray-400 mt-1">{{ $trx->rejection_reason }}</p>
                            @endif
                            @endif
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $trx->quantity }}</td>
                        <td class="px-6 py-4">{{ $trx->user->name }}</td>
                        <td class="px-6 py-4">{{ $trx->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr class="bg-white border-b">
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
@endsection