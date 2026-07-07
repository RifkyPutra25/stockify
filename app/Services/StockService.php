<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    public function stockIn(array $data)
    {
        return StockTransaction::create([
            'product_id' => $data['product_id'],
            'user_id' => auth()->id(),
            'type' => 'in',
            'quantity' => $data['quantity'],
            'date' => $data['date'],
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function stockOut(array $data)
    {
        $product = Product::findOrFail($data['product_id']);

        if ($product->stock < $data['quantity']) {
            throw ValidationException::withMessages([
                'quantity' => 'Stok tidak mencukupi. Sisa stok: ' . $product->stock,
            ]);
        }

        return StockTransaction::create([
            'product_id' => $data['product_id'],
            'user_id' => auth()->id(),
            'type' => 'out',
            'quantity' => $data['quantity'],
            'date' => $data['date'],
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function confirm(int $transactionId)
    {
        return DB::transaction(function () use ($transactionId) {
            $trx = StockTransaction::lockForUpdate()->findOrFail($transactionId);

            if ($trx->status === 'confirmed') {
                throw ValidationException::withMessages([
                    'status' => 'Transaksi ini sudah dikonfirmasi sebelumnya.',
                ]);
            }

            $product = Product::lockForUpdate()->findOrFail($trx->product_id);

            if ($trx->type === 'in') {
                $product->increment('stock', $trx->quantity);
            } elseif ($trx->type === 'out') {
                if ($product->stock < $trx->quantity) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Stok tidak lagi mencukupi untuk konfirmasi ini. Sisa stok: ' . $product->stock,
                    ]);
                }
                $product->decrement('stock', $trx->quantity);
            }

            $trx->update(['status' => 'confirmed']);

            return $trx;
        });
    }

    public function opname(int $productId, int $actualStock, ?string $notes = null)
    {
        return DB::transaction(function () use ($productId, $actualStock, $notes) {
            $product = Product::lockForUpdate()->findOrFail($productId);
            $diff = $actualStock - $product->stock;

            $product->update(['stock' => $actualStock]);

            return StockTransaction::create([
                'product_id' => $productId,
                'user_id' => auth()->id(),
                'type' => 'adjustment',
                'quantity' => abs($diff),
                'date' => now()->toDateString(),
                'status' => 'confirmed',
                'notes' => $notes ?: "Penyesuaian stock opname (selisih: {$diff})",
            ]);
        });
    }
}