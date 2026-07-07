<x-sidebar-dashboard>
    <x-sidebar-menu-dashboard routeName="dashboard" title="Dashboard"/>

    @if(auth()->user()->role === 'admin')
        <x-sidebar-menu-dashboard routeName="categories.index" title="Kategori"/>
        <x-sidebar-menu-dashboard routeName="suppliers.index" title="Supplier"/>
    @endif

    @if(in_array(auth()->user()->role, ['admin', 'manajer_gudang']))
        <x-sidebar-menu-dashboard routeName="products.index" title="Produk"/>
        <x-sidebar-menu-dashboard routeName="stock-transactions.index" title="Transaksi Stok"/>
    @endif

    @if(auth()->user()->role === 'staff_gudang')
        <x-sidebar-menu-dashboard routeName="stock-transactions.pending" title="Konfirmasi Barang"/>
    @endif

    <li class="mt-4 pt-4 border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full p-2 text-base text-red-600 rounded-lg hover:bg-gray-100 group">
                <span class="ml-3">Logout</span>
            </button>
        </form>
    </li>
</x-sidebar-dashboard>