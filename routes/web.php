<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ReportController;
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

Route::get('/', function () {
    return redirect()->route('login');
});

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
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Khusus Admin: kelola kategori, supplier, pengguna, dan aktivitas pengguna
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('users', UserController::class);

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});

// Admin & Manajer Gudang: kelola produk, transaksi stok, dan laporan
Route::middleware(['auth', 'role:admin,manajer_gudang'])->group(function () {
    Route::resource('products', ProductController::class);

    Route::get('stock-transactions', [StockTransactionController::class, 'index'])->name('stock-transactions.index');
    Route::post('stock-transactions/in', [StockTransactionController::class, 'storeIn'])->name('stock-transactions.in');
    Route::post('stock-transactions/out', [StockTransactionController::class, 'storeOut'])->name('stock-transactions.out');
    Route::post('stock-transactions/opname', [StockTransactionController::class, 'opname'])->name('stock-transactions.opname');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
});

// Admin, Manajer Gudang, Staff Gudang: konfirmasi transaksi
Route::middleware(['auth', 'role:admin,manajer_gudang,staff_gudang'])->group(function () {
    Route::get('stock-transactions/pending', [StockTransactionController::class, 'pending'])->name('stock-transactions.pending');
    Route::post('stock-transactions/{id}/confirm', [StockTransactionController::class, 'confirm'])->name('stock-transactions.confirm');
});

require __DIR__.'/auth.php';