<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\StockTransaction;
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