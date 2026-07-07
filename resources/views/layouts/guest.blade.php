<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Stockify') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 via-white to-blue-100">

        <div class="mb-6 flex flex-col items-center">
            <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-700 shadow-lg mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-8.25-4.5-8.25 4.5m16.5 0l-8.25 4.5m8.25-4.5v9l-8.25 4.5m0-9L3.75 7.5m8.25 4.5v9M3.75 7.5v9l8.25 4.5" />
                </svg>
            </div>
            <span class="text-2xl font-bold text-gray-800 tracking-tight">Stockify</span>
            <span class="text-sm text-gray-500">Aplikasi Manajemen Stok Barang</span>
        </div>

        <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-xl overflow-hidden rounded-2xl border border-gray-100">
            @yield('content')
        </div>

        <p class="mt-6 text-xs text-gray-400">&copy; {{ date('Y') }} Stockify. All rights reserved.</p>
    </div>
</body>
</html>