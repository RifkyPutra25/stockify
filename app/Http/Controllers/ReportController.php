<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\StockTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $transactions = $this->filteredQuery($request)->paginate(20)->withQueryString();

        return view('reports.index', compact('transactions', 'categories'));
    }

    public function export(Request $request): StreamedResponse
    {
        $query = $this->filteredQuery($request);
        $filename = 'laporan-transaksi-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Produk', 'Kategori', 'Tipe', 'Jumlah', 'Status', 'Dicatat Oleh', 'Catatan'], ';');

            $query->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $trx) {
                    fputcsv($handle, [
                        $trx->date,
                        $trx->product->name,
                        $trx->product->category->name ?? '-',
                        $trx->type,
                        $trx->quantity,
                        $trx->status,
                        $trx->user->name,
                        $trx->notes,
                    ], ';');
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function exportPdf(Request $request)
    {
        $transactions = $this->filteredQuery($request)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact('transactions'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('laporan-transaksi-' . now()->format('Y-m-d') . '.pdf');
    }
    public function stock(Request $request)
{
    $categories = Category::all();

    $products = Product::with(['category', 'supplier'])
        ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
        ->orderBy('name')
        ->get();

    return view('reports.stock', compact('products', 'categories'));
}

     public function stockExport(Request $request): StreamedResponse
{
    $products = Product::with(['category', 'supplier'])
        ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
        ->orderBy('name')
        ->get();

    $filename = 'laporan-stok-' . now()->format('Y-m-d') . '.csv';

    return response()->streamDownload(function () use ($products) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['SKU', 'Nama Produk', 'Kategori', 'Supplier', 'Stok', 'Stok Minimum', 'Status'], ';');

        foreach ($products as $p) {
            fputcsv($handle, [
                $p->sku,
                $p->name,
                $p->category->name ?? '-',
                $p->supplier->name ?? '-',
                $p->stock,
                $p->minimum_stock,
                $p->stock <= $p->minimum_stock ? 'Menipis' : 'Aman',
            ], ';');
        }

        fclose($handle);
    }, $filename, ['Content-Type' => 'text/csv']);
}

    private function filteredQuery(Request $request)
    {
        return StockTransaction::with(['product.category', 'user'])
            ->when($request->from, fn($q) => $q->whereDate('date', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('date', '<=', $request->to))
            ->when($request->category_id, fn($q) => $q->whereHas('product', fn($p) => $p->where('category_id', $request->category_id)))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->latest('date');
    }
}