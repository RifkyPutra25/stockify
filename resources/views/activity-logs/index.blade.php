@extends('layouts.dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Aktivitas Pengguna</h1>
    <p class="text-sm text-gray-500 mt-1">Riwayat aksi yang dilakukan oleh pengguna sistem.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('activity-logs.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block mb-1 text-xs font-medium text-gray-700">Dari Tanggal</label>
            <input type="date" name="from" value="{{ request('from') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
        </div>
        <div>
            <label class="block mb-1 text-xs font-medium text-gray-700">Sampai Tanggal</label>
            <input type="date" name="to" value="{{ request('to') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
        </div>
        <div>
            <label class="block mb-1 text-xs font-medium text-gray-700">Pengguna</label>
            <select name="user_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                <option value="">Semua</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="text-white bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 font-medium rounded-lg text-sm px-5 py-2.5">
            Filter
        </button>
        <a href="{{ route('activity-logs.export', request()->query()) }}" class="text-white bg-green-700 hover:bg-emerald-700 font-medium rounded-lg text-sm px-5 py-2.5">
            Export CSV
        </a>
    </form>
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
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">Tambah</span>
                        @elseif($log->action === 'update')
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-600">Ubah</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-600">Hapus</span>
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