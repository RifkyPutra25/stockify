@extends('layouts.dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Aktivitas Pengguna</h1>
    <p class="text-sm text-gray-500 mt-1">Riwayat aksi yang dilakukan oleh pengguna sistem.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th class="px-6 py-3">Waktu</th>
                <th class="px-6 py-3">Pengguna</th>
                <th class="px-6 py-3">Aksi</th>
                <th class="px-6 py-3">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr class="border-b last:border-0">
                    <td class="px-6 py-4">{{ $log->created_at->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $log->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($log->action === 'create')
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Tambah</span>
                        @elseif($log->action === 'update')
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">Ubah</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">Hapus</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $log->description }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada aktivitas tercatat.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $logs->links() }}
</div>
@endsection