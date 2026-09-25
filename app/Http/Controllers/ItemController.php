<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['category', 'warehouse']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'aman') {
                $query->whereColumn('stock', '>', 'minimum_stock');
            } elseif ($request->status === 'menipis') {
                $query->whereColumn('stock', '<=', 'minimum_stock')->where('stock', '>', 0);
            } elseif ($request->status === 'habis') {
                $query->where('stock', '<=', 0);
            }
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        $categories = Category::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        return view('stock', compact('items', 'categories', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:items,sku',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
        ]);

        Item::create($validated);

        return redirect()->route('stock')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:items,sku,' . $item->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
        ]);

        $item->update($validated);

        return redirect()->route('stock')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('stock')->with('success', 'Barang berhasil dihapus.');
    }

    public function restock(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'purpose' => 'nullable|string|max:255',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        \App\Models\StockTransaction::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'type' => 'in',
            'quantity' => $validated['quantity'],
            'purpose' => $validated['purpose'] ?? 'Restock barang',
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        $item->increment('stock', $validated['quantity']);

        return redirect()->route('stock')->with('success', 'Stok barang berhasil ditambahkan.');
    }
}