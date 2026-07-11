<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - Stockify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-900">

    <!-- Navbar -->
    <nav class="fixed top-0 z-30 w-full bg-white border-b border-slate-200">
        <div class="px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button id="sidebar-toggle" class="p-2 text-slate-500 rounded-lg lg:hidden hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="flex items-center gap-2.5">
                    @php $appLogo = \App\Models\Setting::get('app_logo'); @endphp
                    @if ($appLogo)
                        <img src="{{ Storage::url($appLogo) }}" alt="Logo" class="w-9 h-9 rounded-xl object-cover">
                    @else
                        <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-slate-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-8.25-4.5-8.25 4.5m16.5 0l-8.25 4.5m8.25-4.5v9l-8.25 4.5m0-9L3.75 7.5m8.25 4.5v9M3.75 7.5v9l8.25 4.5" />
                            </svg>
                        </div>
                    @endif
                    <span class="text-lg font-bold text-slate-900 tracking-tight">{{\App\Models\Setting::get('app_name', 'Stockify') }}</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 text-xs font-medium text-slate-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>

                <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-teal-600 text-white font-semibold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 leading-tight">
                            @if(auth()->user()->role === 'admin') Admin
                            @elseif(auth()->user()->role === 'manajer_gudang') Manajer Gudang
                            @else Staff Gudang
                            @endif
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 z-20 w-64 h-screen pt-16 bg-slate-900 -translate-x-full lg:translate-x-0 transition-transform">
        <div class="h-full px-3 py-5 overflow-y-auto flex flex-col">
            
            <p class="px-3 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Menu Utama</p>
            <ul class="space-y-0.5 font-medium mb-5">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-amber-500/20 to-transparent text-amber-400 shadow-[inset_2px_0_0_0] shadow-amber-500' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>

            @if(in_array(auth()->user()->role, ['admin', 'manajer_gudang']))
            <p class="px-3 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Master Data</p>
            <ul class="space-y-0.5 font-medium mb-5">
                <li>
                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('products.*') ? 'bg-gradient-to-r from-amber-500/20 to-transparent text-amber-400 shadow-[inset_2px_0_0_0] shadow-amber-500' : 'text-slate-300  hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Produk</span>
                    </a>
                </li>
                @if(auth()->user()->role === 'admin')
                <li>
                    <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('categories.*') ? 'bg-gradient-to-r from-amber-500/20 to-transparent text-amber-400 shadow-[inset_2px_0_0_0] shadow-amber-500' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span>Kategori</span>
                    </a>
                </li>
                @endif
                <li>
                     <a href="{{ route('suppliers.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('suppliers.*') ? 'bg-gradient-to-r from-amber-500/20 to-transparent text-amber-400 shadow-[inset_2px_0_0_0] shadow-amber-500' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4M4 10v9a1 1 0 001 1h4v-6h6v6h4a1 1 0 001-1v-9"/></svg>
                        <span>Supplier</span>
                    </a>
                </li>
                @if(auth()->user()->role === 'admin')
                <li>
                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('users.*') ? 'bg-gradient-to-r from-amber-500/20 to-transparent text-amber-400 shadow-[inset_2px_0_0_0] shadow-amber-500' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Pengguna</span>
                    </a>
                </li>
                @endif
            </ul>
            @endif

            @if(in_array(auth()->user()->role, ['admin', 'manajer_gudang']))
            <p class="px-3 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Operasional</p>
            <ul class="space-y-0.5 font-medium mb-5">
                <li>
                    <a href="{{ route('stock-transactions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('stock-transactions.index') ? 'bg-gradient-to-r from-amber-500/20 to-transparent text-amber-400 shadow-[inset_2px_0_0_0] shadow-amber-500' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4M16 17H4m0 0l4 4m-4-4l4-4"/></svg>
                        <span>Transaksi Stok</span>
                    </a>
                </li>
            </ul>
            @endif

            @if(auth()->user()->role === 'staff_gudang')
            <p class="px-3 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Tugas</p>
            <ul class="space-y-0.5 font-medium mb-5">
                <li>
                    <a href="{{ route('stock-transactions.pending') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('stock-transactions.pending') ? 'bg-gradient-to-r from-amber-500/20 to-transparent text-amber-400 shadow-[inset_2px_0_0_0] shadow-amber-500' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Konfirmasi Barang</span>
                    </a>
                </li>
            </ul>
            @endif

            @if(in_array(auth()->user()->role, ['admin', 'manajer_gudang']))
            <p class="px-3 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Laporan</p>
            <ul class="space-y-0.5 font-medium mb-5">
                <li>
                    <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('reports.index') || request()->routeIs('reports.export') ? 'bg-gradient-to-r from-amber-500/20 to-transparent text-amber-400 shadow-[inset_2px_0_0_0] shadow-amber-500' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m6 10v-4m-3 4V3M4 21h16"/></svg>
                        <span>Laporan Transaksi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.stock') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('reports.stock') ? 'bg-gradient-to-r from-amber-500/20 to-transparent text-amber-400 shadow-[inset_2px_0_0_0] shadow-amber-500' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Laporan Stok</span>
                    </a>
                </li>
            </ul>
            @endif

            @if(auth()->user()->role === 'admin')
            <p class="px-3 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Sistem</p>
            <ul class="space-y-0.5 font-medium mb-6">
                <li>
                    <a href="{{ route('activity-logs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('activity-logs.*') ? 'bg-gradient-to-r from-amber-500/20 to-transparent text-amber-400 shadow-[inset_2px_0_0_0] shadow-amber-500' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Aktivitas Pengguna</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('settings.*') ? 'bg-gradient-to-r from-amber-500/20 to-transparent text-amber-400 shadow-[inset_2px_0_0_0] shadow-amber-500' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                        <span>Pengaturan</span>
                    </a>
                </li>
            </ul>
            @endif

            <div class="mt-auto px-3 py-3 rounded-xl bg-slate-800/60 border border-slate-700">
                <p class="text-xs text-slate-400">Stockify v1.0</p>
                <p class="text-xs text-slate-500">Manajemen Stok Barang</p>
            </div>
        </div>
    </aside>

    <!-- Main content -->
    <div class="pt-16 lg:ml-64">
        <main class="p-4 sm:p-6 max-w-[1400px] mx-auto">
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-100" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-100" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="px-6 py-4 text-center text-xs text-slate-400">
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