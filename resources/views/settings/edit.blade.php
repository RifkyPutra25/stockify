@extends('layouts.dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Pengaturan Umum</h1>
    <p class="text-sm text-gray-500 mt-1">Atur nama dan logo aplikasi.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-xl">
    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-900">Nama Aplikasi</label>
            <input type="text" name="app_name" value="{{ old('app_name', $appName) }}"
                   class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">
            @error('app_name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-900">Logo Saat Ini</label>
            @if ($appLogo)
                <img src="{{ Storage::url($appLogo) }}" alt="Logo" class="w-20 h-20 object-contain rounded border mb-2">
            @else
                <p class="text-sm text-gray-400 mb-2">Belum ada logo, menggunakan ikon default.</p>
            @endif
            <label class="block mb-1 text-sm font-medium text-gray-900">Ganti Logo (opsional)</label>
            <input type="file" name="app_logo" accept="image/*" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">
            @error('app_logo') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="text-white bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 font-medium rounded-lg text-sm px-5 py-2.5">
            Simpan Pengaturan
        </button>
    </form>
</div>
@endsection