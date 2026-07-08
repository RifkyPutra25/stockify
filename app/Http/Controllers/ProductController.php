<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;

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