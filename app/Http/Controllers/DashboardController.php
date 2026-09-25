<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSku = Item::count();

        $barangMasukHariIni = StockTransaction::where('type', 'in')
            ->whereDate('created_at', today())
            ->sum('quantity');

        $barangKeluarHariIni = StockTransaction::where('type', 'out')
            ->whereDate('created_at', today())
            ->sum('quantity');

        $stokMenipis = Item::whereColumn('stock', '<=', 'minimum_stock')
            ->where('stock', '>', 0)
            ->count();

        $stokTerbaru = Item::with(['warehouse'])
            ->latest()
            ->take(5)
            ->get();

        $peringatanStok = Item::with(['warehouse'])
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalSku',
            'barangMasukHariIni',
            'barangKeluarHariIni',
            'stokMenipis',
            'stokTerbaru',
            'peringatanStok'
        ));
    }
}