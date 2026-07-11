@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6">
    <h1 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Konfirmasi Barang Masuk & Keluar</h1>

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
                    <th class="px-6 py-3 text-right">Aksi</th>
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
                            @else
                                <span class="text-rose-600 font-semibold">Keluar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $trx->quantity }}</td>
                        <td class="px-6 py-4">{{ $trx->user->name }}</td>
                        <td class="px-6 py-4">{{ $trx->notes ?? '-' }}</td>
                        <td class="px-6 py-4 text-right">
                        <form action="{{ route('stock-transactions.confirm', $trx->id) }}" method="POST"
                         onsubmit="return confirm('Yakin barang sudah diperiksa dan sesuai?')">
                        @csrf
                       <td class="px-6 py-4 text-right space-x-2">
                         <form action="{{ route('stock-transactions.confirm', $trx->id) }}" method="POST" class="inline"
                         onsubmit="return confirm('Yakin barang sudah diperiksa dan sesuai?')">
                         @csrf
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-xs px-4 py-2">
                          Konfirmasi
                       </button>
                      </form>

                        <button type="button" onclick="document.getElementById('reject-modal-{{ $trx->id }}').classList.remove('hidden')"
                       class="text-white bg-red-700 hover:bg-red-800 font-medium rounded-lg text-xs px-4 py-2">
                       Tolak
                    </button>

     <!-- Modal alasan penolakan -->
    <div id="reject-modal-{{ $trx->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md text-left">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Tolak Transaksi</h3>
            <p class="text-sm text-gray-500 mb-4">
                Produk: <strong>{{ $trx->product->name }}</strong> ({{ $trx->quantity }} unit)
            </p>
            <form action="{{ route('stock-transactions.reject', $trx->id) }}" method="POST">
                @csrf
                <label class="block mb-1 text-sm font-medium text-gray-900">Alasan Penolakan</label>
                <textarea name="rejection_reason" rows="3" required placeholder="Contoh: Barang cacat, kemasan rusak"
                          class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5 mb-4"></textarea>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="document.getElementById('reject-modal-{{ $trx->id }}').classList.add('hidden')"
                            class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" class="text-white bg-red-700 hover:bg-red-800 font-medium rounded-lg text-sm px-4 py-2">
                        Tolak Transaksi
                  </button>
                      </div>
                      </form>
                      </div>
                      </div>
                     </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b">
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada transaksi yang menunggu konfirmasi.</td>
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