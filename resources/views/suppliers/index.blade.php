@extends('layouts.dashboard')

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-2">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Supplier</h1>
        <p class="text-sm text-slate-500 mt-1">
            @if(auth()->user()->role === 'admin')
                Kelola data supplier.
            @else
                Daftar supplier yang tersedia (khusus lihat).
            @endif
        </p>
    </div>

    @if(auth()->user()->role === 'admin')
        <a href="{{ route('suppliers.create') }}"
           class="text-white bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 font-medium rounded-xl text-sm px-5 py-2.5">
            + Tambah Supplier
        </a>
    @endif
</div>

@if (session('success'))
    <div class="mb-4 p-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-100">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300 overflow-x-auto">
    <table class="w-full text-sm text-left text-slate-500">
        <thead class="text-xs text-slate-700 uppercase bg-slate-50">
            <tr>
                <th class="px-6 py-3">Nama</th>
                <th class="px-6 py-3">Alamat</th>
                <th class="px-6 py-3">Telepon</th>
                @if(auth()->user()->role === 'admin')
                    <th class="px-6 py-3 text-right">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($suppliers as $supplier)
                <tr class="border-b last:border-0 hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $supplier->name }}</td>
                    <td class="px-6 py-4">{{ $supplier->address ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $supplier->phone ?? '-' }}</td>
                    @if(auth()->user()->role === 'admin')
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-rose-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ auth()->user()->role === 'admin' ? 4 : 3 }}" class="px-6 py-4 text-center text-slate-500">
                        Belum ada supplier.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $suppliers->links() }}
</div>
@endsection