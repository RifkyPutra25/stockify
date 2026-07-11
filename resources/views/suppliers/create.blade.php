@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6 max-w-xl">
    <h1 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Tambah Supplier</h1>

    <form action="{{ route('suppliers.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf

        <div>
            <label for="name" class="block mb-1 text-sm font-medium text-gray-900">Nama Supplier</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
            @error('name')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="address" class="block mb-1 text-sm font-medium text-gray-900">Alamat</label>
            <textarea name="address" id="address" rows="3"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">{{ old('address') }}</textarea>
            @error('address')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="block mb-1 text-sm font-medium text-gray-900">Telepon</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
            @error('phone')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit"
                    class="text-white bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 font-medium rounded-lg text-sm px-5 py-2.5">
                Simpan
            </button>
            <a href="{{ route('suppliers.index') }}"
               class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection