<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier', 'attributes'])->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        $this->syncAttributes($product, $request);

        ActivityLog::record('create', "Menambahkan produk: {$product->name}");

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        $product->load('attributes');
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        $this->syncAttributes($product, $request);

        ActivityLog::record('update', "Memperbarui produk: {$product->name}");

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        ActivityLog::record('delete', "Menghapus produk: {$product->name}");

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
    public function export(): StreamedResponse
{
    $products = Product::with(['category', 'supplier'])->get();
    $filename = 'produk-' . now()->format('Y-m-d') . '.csv';

    return response()->streamDownload(function () use ($products) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['sku', 'name', 'category', 'supplier', 'description', 'purchase_price', 'selling_price', 'minimum_stock'], ';');

        foreach ($products as $p) {
            fputcsv($handle, [
                $p->sku,
                $p->name,
                $p->category->name,
                $p->supplier->name,
                $p->description,
                $p->purchase_price,
                $p->selling_price,
                $p->minimum_stock,
            ], ';');
        }

        fclose($handle);
    }, $filename, ['Content-Type' => 'text/csv']);
}

public function importForm()
{
    return view('products.import');
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:csv,txt|max:2048',
    ]);

    $handle = fopen($request->file('file')->getRealPath(), 'r');
    $header = fgetcsv($handle, 0, ';');

    $created = 0;
    $updated = 0;
    $skipped = 0;

    while (($row = fgetcsv($handle, 0, ';')) !== false) {
        $data = array_combine($header, $row);

        $category = Category::firstOrCreate(['name' => trim($data['category'])]);
        $supplier = Supplier::firstOrCreate(['name' => trim($data['supplier'])]);

        if (empty($data['sku']) || empty($data['name'])) {
            $skipped++;
            continue;
        }

        $product = Product::where('sku', trim($data['sku']))->first();

        $payload = [
            'name' => $data['name'],
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'description' => $data['description'] ?? null,
            'purchase_price' => is_numeric($data['purchase_price'] ?? null) ? $data['purchase_price'] : 0,
            'selling_price' => is_numeric($data['selling_price'] ?? null) ? $data['selling_price'] : 0,
            'minimum_stock' => is_numeric($data['minimum_stock'] ?? null) ? $data['minimum_stock'] : 0,
        ];

        if ($product) {
            $product->update($payload);
            $updated++;
        } else {
            $payload['sku'] = trim($data['sku']);
            $payload['stock'] = 0;
            Product::create($payload);
            $created++;
        }
    }

    fclose($handle);

    ActivityLog::record('create', "Import produk: {$created} baru, {$updated} diperbarui, {$skipped} dilewati");

    return redirect()->route('products.index')->with('success', "Import selesai: {$created} produk baru, {$updated} diperbarui, {$skipped} dilewati.");
}

    /**
     * Simpan ulang atribut produk (hapus yang lama, buat yang baru)
     * berdasarkan input attribute_name[] dan attribute_value[] dari form.
     */
    private function syncAttributes(Product $product, StoreProductRequest $request): void
    {
        $product->attributes()->delete();

        $names = $request->input('attribute_name', []);
        $values = $request->input('attribute_value', []);

        foreach ($names as $i => $name) {
            if (!empty($name) && isset($values[$i]) && $values[$i] !== '') {
                $product->attributes()->create([
                    'name' => $name,
                    'value' => $values[$i],
                ]);
            }
        }
    }
}