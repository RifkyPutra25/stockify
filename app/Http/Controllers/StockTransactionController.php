<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockInRequest;
use App\Http\Requests\StockOutRequest;
use App\Http\Requests\StockOpnameRequest;
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
        $this->stockService->stockIn($request->validated());
        return redirect()->route('stock-transactions.index')->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function storeOut(StockOutRequest $request)
    {
        try {
            $this->stockService->stockOut($request->validated());
            return redirect()->route('stock-transactions.index')->with('success', 'Barang keluar berhasil dicatat.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function opname(StockOpnameRequest $request)
{
    $this->stockService->opname(
        $request->validated('product_id'),
        $request->validated('actual_stock'),
        $request->validated('notes')
    );

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
        $this->stockService->confirm($id);
        return redirect()->route('stock-transactions.pending')->with('success', 'Transaksi berhasil dikonfirmasi.');
    } catch (ValidationException $e) {
        return back()->withErrors($e->errors());
    }
}
}