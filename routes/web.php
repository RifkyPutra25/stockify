<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect halaman utama ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard dengan pembagian data per role
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $data = [];

        if ($user->role === 'admin') {
            $data['totalProduct'] = \App\Models\Product::count();
            $data['totalCategory'] = \App\Models\Category::count();
            $data['totalSupplier'] = \App\Models\Supplier::count();
            $data['stockInThisMonth'] = \App\Models\StockTransaction::where('type', 'in')->whereMonth('date', now()->month)->sum('quantity');
            $data['stockOutThisMonth'] = \App\Models\StockTransaction::where('type', 'out')->whereMonth('date', now()->month)->sum('quantity');
            $data['recentTransactions'] = \App\Models\StockTransaction::with(['product', 'user'])->latest('date')->take(5)->get();
            $data['lowStockProducts'] = \App\Models\Product::whereColumn('stock', '<=', 'minimum_stock')->take(5)->get();
            $data['stockByCategory'] = \App\Models\Category::withSum('products', 'stock')->get();
            $data['recentActivities'] = \App\Models\ActivityLog::with('user')->latest()->take(5)->get();
            
        } elseif ($user->role === 'manajer_gudang') {
            $data['totalProduct'] = \App\Models\Product::count();
            $data['lowStockProducts'] = \App\Models\Product::whereColumn('stock', '<=', 'minimum_stock')->get();
            $data['todayIn'] = \App\Models\StockTransaction::where('type', 'in')->whereDate('date', today())->count();
            $data['todayOut'] = \App\Models\StockTransaction::where('type', 'out')->whereDate('date', today())->count();
            $data['pendingCount'] = \App\Models\StockTransaction::where('status', 'pending')->count();
            
        } elseif ($user->role === 'staff_gudang') {
            $data['pendingCount'] = \App\Models\StockTransaction::where('status', 'pending')->count();
            $data['confirmedTodayCount'] = \App\Models\StockTransaction::where('status', 'confirmed')->where('user_id', $user->id)->whereDate('updated_at', today())->count();
            $data['recentTransactions'] = \App\Models\StockTransaction::with('product')->where('user_id', $user->id)->latest('date')->take(5)->get();
        }

        return view('dashboard', $data);
    })->name('dashboard');
});

// Profile Pengguna (Semua yang login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// KHUSUS ADMIN: Kelola kategori, supplier (CRUD selain index), pengguna, log aktivitas, & pengaturan
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class)->except(['index']);
    Route::resource('users', UserController::class);
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});

// ADMIN & MANAJER GUDANG: Kelola produk, transaksi stok, laporan, dan export/import
Route::middleware(['auth', 'role:admin,manajer_gudang'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('stock-transactions', [StockTransactionController::class, 'index'])->name('stock-transactions.index');
    Route::post('stock-transactions/in', [StockTransactionController::class, 'storeIn'])->name('stock-transactions.in');
    Route::post('stock-transactions/out', [StockTransactionController::class, 'storeOut'])->name('stock-transactions.out');
    Route::post('stock-transactions/opname', [StockTransactionController::class, 'opname'])->name('stock-transactions.opname');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('reports/stock-export', [ReportController::class, 'stockExport'])->name('reports.stock-export');
    Route::get('products-export', [ProductController::class, 'export'])->name('products.export');
    Route::get('products-import', [ProductController::class, 'importForm'])->name('products.import.form');
    Route::post('products-import', [ProductController::class, 'import'])->name('products.import');
});

// ADMIN, MANAJER GUDANG, STAFF GUDANG: Konfirmasi transaksi pending
Route::middleware(['auth', 'role:admin,manajer_gudang,staff_gudang'])->group(function () {
    Route::get('stock-transactions/pending', [StockTransactionController::class, 'pending'])->name('stock-transactions.pending');
    Route::post('stock-transactions/{id}/confirm', [StockTransactionController::class, 'confirm'])->name('stock-transactions.confirm');
    Route::post('stock-transactions/{id}/reject', [StockTransactionController::class, 'reject'])->name('stock-transactions.reject');
});

require __DIR__.'/auth.php';