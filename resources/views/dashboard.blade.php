@extends('layouts.dashboard')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas gudang kamu hari ini.</p>
    </div>
</div>

@if(auth()->user()->role === 'admin')

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-lg bg-teal-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Produk</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalProduct }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-lg bg-purple-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Kategori</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalCategory }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4M4 10v9a1 1 0 001 1h4v-6h6v6h4a1 1 0 001-1v-9"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Supplier</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalSupplier }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-lg bg-rose-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Stok Menipis</p>
                <p class="text-2xl font-bold text-gray-900">{{ $lowStockProducts->count() }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <!-- Perbandingan Masuk/Keluar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:col-span-1">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Barang Masuk Dan Keluar (Bulan Ini)</h2>
            @php
                $maxVal = max($stockInThisMonth, $stockOutThisMonth, 1);
            @endphp
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-500">Masuk</span>
                        <span class="font-semibold text-emerald-600">{{ $stockInThisMonth }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ ($stockInThisMonth / $maxVal) * 100 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-500">Keluar</span>
                        <span class="font-semibold text-rose-600">{{ $stockOutThisMonth }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ ($stockOutThisMonth / $maxVal) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Produk Stok Menipis</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-400 uppercase border-b">
                        <tr>
                            <th class="py-2 pr-4">Produk</th>
                            <th class="py-2 pr-4">Stok</th>
                            <th class="py-2 pr-4">Minimum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lowStockProducts as $product)
                            <tr class="border-b last:border-0">
                                <td class="py-2.5 pr-4 font-medium text-gray-800">{{ $product->name }}</td>
                                <td class="py-2.5 pr-4 text-rose-600 font-semibold">{{ $product->stock }}</td>
                                <td class="py-2.5 pr-4">{{ $product->minimum_stock }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-center text-gray-400">Tidak ada produk dengan stok menipis 🎉</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grafik Stok per Kategori & Aktivitas Pengguna Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Grafik Stok Barang per Kategori</h2>
            <canvas id="stockChart" height="200"></canvas>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Aktivitas Pengguna Terbaru</h2>
            <div class="space-y-3">
                @forelse ($recentActivities as $log)
                    <div class="flex items-start gap-3 text-sm">
                        <div class="w-2 h-2 mt-1.5 rounded-full
                            {{ $log->action === 'create' ? 'bg-emerald-500' : ($log->action === 'update' ? 'bg-teal-500' : 'bg-rose-500') }}">
                        </div>
                        <div>
                            <p class="text-gray-700">{{ $log->description }}</p>
                            <p class="text-xs text-gray-400">{{ $log->user->name ?? '-' }} &middot; {{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada aktivitas.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Aktivitas Transaksi Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-400 uppercase border-b">
                    <tr>
                        <th class="py-2 pr-4">Tanggal</th>
                        <th class="py-2 pr-4">Produk</th>
                        <th class="py-2 pr-4">Tipe</th>
                        <th class="py-2 pr-4">Jumlah</th>
                        <th class="py-2 pr-4">Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentTransactions as $trx)
                        <tr class="border-b last:border-0">
                            <td class="py-2.5 pr-4">{{ $trx->date }}</td>
                            <td class="py-2.5 pr-4 font-medium text-gray-800">{{ $trx->product->name }}</td>
                            <td class="py-2.5 pr-4">
                                @if($trx->type === 'in')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">Masuk</span>
                                @elseif($trx->type === 'out')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-600">Keluar</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-amber-600">Penyesuaian</span>
                                @endif
                            </td>
                            <td class="py-2.5 pr-4">{{ $trx->quantity }}</td>
                            <td class="py-2.5 pr-4">{{ $trx->user->name }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4 text-center text-gray-400">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
    const ctx = document.getElementById('stockChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($stockByCategory->pluck('name')) !!},
            datasets: [
                {
                    label: 'Barang Masuk',
                    data: {!! json_encode($stockByCategory->pluck('name')->map(fn($name) => $inByCategory[$name] ?? 0)) !!},
                    borderColor: '#16a34a',
                    backgroundColor: '#16a34a',
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: '#16a34a',
                    fill: false,
                },
                {
                    label: 'Barang Keluar',
                    data: {!! json_encode($stockByCategory->pluck('name')->map(fn($name) => $outByCategory[$name] ?? 0)) !!},
                    borderColor: '#dc2626',
                    backgroundColor: '#dc2626',
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: '#dc2626',
                    fill: false,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true, position: 'top' } },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Jumlah (unit)' } },
                x: { title: { display: true, text: 'Kategori' } }
            }
        }
    });
</script>

@elseif(auth()->user()->role === 'manajer_gudang')

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-lg bg-teal-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Produk</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalProduct }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Masuk Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900">{{ $todayIn }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-lg bg-rose-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 17H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Keluar Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900">{{ $todayOut }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Menunggu Konfirmasi</p>
                <p class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Produk Stok Menipis</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-400 uppercase border-b">
                    <tr>
                        <th class="py-2 pr-4">Produk</th>
                        <th class="py-2 pr-4">Stok</th>
                        <th class="py-2 pr-4">Minimum</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lowStockProducts as $product)
                        <tr class="border-b last:border-0">
                            <td class="py-2.5 pr-4 font-medium text-gray-800">{{ $product->name }}</td>
                            <td class="py-2.5 pr-4 text-rose-600 font-semibold">{{ $product->stock }}</td>
                            <td class="py-2.5 pr-4">{{ $product->minimum_stock }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-4 text-center text-gray-400">Tidak ada produk dengan stok menipis 🎉</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@else

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Menunggu Konfirmasi</p>
                <p class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Kamu Konfirmasi Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900">{{ $confirmedTodayCount }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Transaksi Terakhir yang Kamu Catat</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-400 uppercase border-b">
                    <tr>
                        <th class="py-2 pr-4">Tanggal</th>
                        <th class="py-2 pr-4">Produk</th>
                        <th class="py-2 pr-4">Tipe</th>
                        <th class="py-2 pr-4">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentTransactions as $trx)
                        <tr class="border-b last:border-0">
                            <td class="py-2.5 pr-4">{{ $trx->date }}</td>
                            <td class="py-2.5 pr-4 font-medium text-gray-800">{{ $trx->product->name }}</td>
                            <td class="py-2.5 pr-4">{{ $trx->type === 'in' ? 'Masuk' : 'Keluar' }}</td>
                            <td class="py-2.5 pr-4">{{ $trx->quantity }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-400">Belum ada transaksi yang kamu catat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endif
@endsection 