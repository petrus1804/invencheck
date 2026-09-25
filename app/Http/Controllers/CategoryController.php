<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('items')
            ->with('items')
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                $category->total_stock = $category->items->sum('stock');
                $category->menipis_count = $category->items->filter(fn($i) => $i->status === 'menipis')->count();
                $category->habis_count = $category->items->filter(fn($i) => $i->status === 'habis')->count();
                return $category;
            });

        return view('kategori', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'icon' => 'nullable|string|max:10',
        ]);

        Category::create($validated);

        return redirect()->route('kategori')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:10',
        ]);

        $category->update($validated);

        return redirect()->route('kategori')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->items()->count() > 0) {
            return redirect()->route('kategori')->with('error', 'Kategori tidak bisa dihapus karena masih ada barang di dalamnya.');
        }

        $category->delete();

        return redirect()->route('kategori')->with('success', 'Kategori berhasil dihapus.');
    }
}