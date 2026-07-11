@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6 max-w-xl">
    <h1 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Edit Kategori</h1>

    <form action="{{ route('categories.update', $category) }}" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block mb-1 text-sm font-medium text-gray-900">Nama Kategori</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
            @error('name')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block mb-1 text-sm font-medium text-gray-900">Deskripsi</label>
            <textarea name="description" id="description" rows="3"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit"
                    class="text-white bg-teal-600 hover:bg-primary-800 font-medium rounded-lg text-sm px-5 py-2.5">
                Update
            </button>
            <a href="{{ route('categories.index') }}"
               class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection