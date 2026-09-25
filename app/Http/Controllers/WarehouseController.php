<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::withCount('items')
            ->with(['items', 'pic'])
            ->orderBy('name')
            ->get()
            ->map(function ($warehouse) {
                $warehouse->total_stock = $warehouse->items->sum('stock');
                return $warehouse;
            });

        $pics = User::whereIn('role', ['admin', 'staff'])->orderBy('name')->get();

        return view('gudang', compact('warehouses', 'pics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:warehouses,name',
            'address' => 'nullable|string',
            'pic_id' => 'nullable|exists:users,id',
            'capacity_percentage' => 'required|integer|min:0|max:100',
        ]);

        Warehouse::create($validated);

        return redirect()->route('gudang')->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:warehouses,name,' . $warehouse->id,
            'address' => 'nullable|string',
            'pic_id' => 'nullable|exists:users,id',
            'capacity_percentage' => 'required|integer|min:0|max:100',
        ]);

        $warehouse->update($validated);

        return redirect()->route('gudang')->with('success', 'Gudang berhasil diperbarui.');
    }

    public function destroy(Warehouse $warehouse)
    {
        if ($warehouse->items()->count() > 0) {
            return redirect()->route('gudang')->with('error', 'Gudang tidak bisa dihapus karena masih ada barang di dalamnya.');
        }

        $warehouse->delete();

        return redirect()->route('gudang')->with('success', 'Gudang berhasil dihapus.');
    }
}