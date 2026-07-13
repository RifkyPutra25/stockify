<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Stockify') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes floatSlow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .anim-fade-up { animation: fadeSlideUp 0.5s ease-out both; }
    .anim-fade-up-1 { animation: fadeSlideUp 0.55s ease-out 0.1s both; }
    .anim-fade-up-2 { animation: fadeSlideUp 0.6s ease-out 0.2s both; }
    .anim-fade { animation: fadeIn 0.9s ease-out both; }
    .anim-float { animation: floatSlow 6s ease-in-out infinite; }
    .anim-float-slow { animation: floatSlow 8s ease-in-out infinite; }
</style>
</head>
<body class="bg-white">

    <div class="min-h-screen flex">

        <!-- Panel Branding (Kiri) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-slate-900 via-blue-900 to-emerald-950 overflow-hidden">
            <!-- Background dekoratif -->
            <div class="anim-float-slow absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white/5"></div>
            <div class="anim-float absolute bottom-0 right-0 w-72 h-72 rounded-full bg-white/5 translate-x-1/3 translate-y-1/3"></div>

            <div class="relative z-10 flex flex-col justify-between p-12 text-white w-full">
                
                <!-- Logo & Title Rapat (gap-2) -->
                <div class="anim-fade flex items-center gap-2">
                    <img src="{{ asset('images/logo-stockify.png') }}" alt="Logo" class="w-16 h-16 object-contain">
                    <span class="text-2xl font-bold tracking-wide">Stockify</span>
                </div> 

                <div class="max-w-md">
                    <h1 class="anim-fade-up text-3xl font-bold leading-tight mb-4">
                        Kelola stok gudang kamu dengan lebih mudah dan akurat.
                    </h1>
                    <p class="anim-fade-up-1 text-white/70 text-sm leading-relaxed">
                        Pantau barang masuk, keluar, dan stok minimum secara real-time. Satu platform untuk seluruh tim gudang kamu.
                    </p>

                    <div class="anim-fade-up-2 flex items-center gap-6 mt-8">
                        <div>
                            <p class="text-2xl font-bold text-emerald-400">100%</p>
                            <p class="text-xs text-white/60">Akurasi Stok</p>
                        </div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div>
                            <p class="text-2xl font-bold text-blue-400">3</p>
                            <p class="text-xs text-white/60">Level Akses Peran</p>
                        </div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div>
                            <p class="text-2xl font-bold text-cyan-400">24/7</p>
                            <p class="text-xs text-white/60">Pemantauan</p>
                        </div>
                    </div>
                </div>

                <p class="anim-fade text-xs text-white/40">&copy; {{ date('Y') }} Stockify. Aplikasi Manajemen Stok Barang.</p>
            </div>
        </div>

        <!-- Panel Form (Kanan) -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 bg-white">
            <div class="w-full max-w-sm anim-fade-up">
                <!-- Logo Mobile -->
                <div class="flex lg:hidden items-center gap-2 mb-8">
                    <img src="{{ asset('images/logo-stockify.png') }}" alt="Logo" class="w-14 h-14 object-contain">
                    <span class="text-xl font-bold text-slate-900">Stockify</span>
                </div>

                @yield('content')
            </div>
        </div>
    </div>

</body>
</html>