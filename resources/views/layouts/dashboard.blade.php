<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - Stockify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="fixed top-0 z-30 w-full bg-white border-b border-gray-200 shadow-sm">
        <div class="px-4 sm:px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button id="sidebar-toggle" class="p-2 text-gray-500 rounded-lg lg:hidden hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gradient-to-br from-blue-600 to-blue-800 shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-8.25-4.5-8.25 4.5m16.5 0l-8.25 4.5m8.25-4.5v9l-8.25 4.5m0-9L3.75 7.5m8.25 4.5v9M3.75 7.5v9l8.25 4.5" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-gray-800">Stockify</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-100 text-xs font-medium text-gray-600">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>

                <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-700 text-white font-semibold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800 leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 leading-tight">
                            @if(auth()->user()->role === 'admin') Admin
                            @elseif(auth()->user()->role === 'manajer_gudang') Manajer Gudang
                            @else Staff Gudang
                            @endif
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Logout" class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 z-20 w-64 h-screen pt-16 bg-white border-r border-gray-200 -translate-x-full lg:translate-x-0 transition-transform">
        <div class="h-full px-3 py-5 overflow-y-auto flex flex-col">
            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Utama</p>
            <ul class="space-y-1 font-medium mb-6">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>

            @if(auth()->user()->role === 'admin')
            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</p>
            <ul class="space-y-1 font-medium mb-6">
                <li>
                    <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('categories.*') ? 'bg-blue-700 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span>Kategori</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('suppliers.*') ? 'bg-blue-700 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4M4 10v9a1 1 0 001 1h4v-6h6v6h4a1 1 0 001-1v-9"/></svg>
                        <span>Supplier</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('users.*') ? 'bg-blue-700 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Pengguna</span>
                    </a>
                </li>
            </ul>
            @endif

            @if(in_array(auth()->user()->role, ['admin', 'manajer_gudang']))
            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Inventaris</p>
            <ul class="space-y-1 font-medium mb-6">
                <li>
                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('products.*') ? 'bg-blue-700 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Produk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock-transactions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('stock-transactions.index') ? 'bg-blue-700 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4M16 17H4m0 0l4 4m-4-4l4-4"/></svg>
                        <span>Transaksi Stok</span>
                    </a>
                </li>
            </ul>
            @endif

            @if(auth()->user()->role === 'staff_gudang')
            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tugas</p>
            <ul class="space-y-1 font-medium mb-6">
                <li>
                    <a href="{{ route('stock-transactions.pending') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('stock-transactions.pending') ? 'bg-blue-700 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Konfirmasi Barang</span>
                    </a>
                </li>
            </ul>
            @endif

            <div class="mt-auto px-3 py-3 rounded-lg bg-gray-50 border border-gray-100">
                <p class="text-xs text-gray-400">Stockify v1.0</p>
                <p class="text-xs text-gray-400">Manajemen Stok Barang</p>
            </div>
        </div>
    </aside>

    <!-- Main content -->
    <div class="pt-16 lg:ml-64">
        <main class="p-4 sm:p-6 max-w-[1400px] mx-auto">
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-100" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-100" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="px-6 py-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Stockify. Aplikasi Manajemen Stok Barang.
        </footer>
    </div>

    <script>
        document.getElementById('sidebar-toggle')?.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>