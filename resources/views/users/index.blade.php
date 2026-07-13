@extends('layouts.dashboard')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Pengguna</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola akun dan hak akses pengguna sistem.</p>
    </div>
    <a href="{{ route('users.create') }}"
       class="text-white bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 font-medium rounded-lg text-sm px-5 py-2.5">
        + Tambah Pengguna
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th class="px-6 py-3">Nama</th>
                <th class="px-6 py-3">Email</th>
                <th class="px-6 py-3">Role</th>
                <th class="px-6 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr class="border-b last:border-0">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->role === 'admin')
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700">Admin</span>
                        @elseif($user->role === 'manajer_gudang')
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-600">Manajer Gudang</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700">Staff Gudang</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('users.edit', $user) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-rose-600 hover:underline">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada pengguna.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection