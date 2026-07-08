<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockInRequest;
use App\Http\Requests\StockOutRequest;
use App\Http\Requests\StockOpnameRequest;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StockTransactionController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function index()
    {
        $transactions = StockTransaction::with(['product', 'user'])->latest('date')->paginate(15);
        $products = Product::all();
        return view('stock-transactions.index', compact('transactions', 'products'));
    }

    public function storeIn(StockInRequest $request)
    {
        $data = $request->validated();
        $this->stockService->stockIn($data);

        $product = Product::find($data['product_id']);
        ActivityLog::record('create', "Mencatat barang masuk: {$data['quantity']} unit {$product->name} (menunggu konfirmasi)");

        return redirect()->route('stock-transactions.index')->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function storeOut(StockOutRequest $request)
    {
        try {
            $data = $request->validated();
            $this->stockService->stockOut($data);

            $product = Product::find($data['product_id']);
            ActivityLog::record('create', "Mencatat barang keluar: {$data['quantity']} unit {$product->name} (menunggu konfirmasi)");

            return redirect()->route('stock-transactions.index')->with('success', 'Barang keluar berhasil dicatat.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function opname(StockOpnameRequest $request)
    {
        $product = Product::find($request->validated('product_id'));

        $this->stockService->opname(
            $request->validated('product_id'),
            $request->validated('actual_stock'),
            $request->validated('notes')
        );

        ActivityLog::record('update', "Stock opname produk: {$product->name} (stok disesuaikan menjadi {$request->validated('actual_stock')})");

        return redirect()->route('stock-transactions.index')->with('success', 'Stock opname berhasil dicatat.');
    }

    public function pending()
    {
        $transactions = StockTransaction::with(['product', 'user'])
            ->where('status', 'pending')
            ->whereIn('type', ['in', 'out'])
            ->latest('date')
            ->paginate(15);

        return view('stock-transactions.pending', compact('transactions'));
    }

    public function confirm(int $id)
    {
        try {
            $trx = $this->stockService->confirm($id);

            ActivityLog::record('update', "Mengonfirmasi transaksi: {$trx->quantity} unit {$trx->product->name} (" . ($trx->type === 'in' ? 'barang masuk' : 'barang keluar') . ')');

            return redirect()->route('stock-transactions.pending')->with('success', 'Transaksi berhasil dikonfirmasi.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }
}