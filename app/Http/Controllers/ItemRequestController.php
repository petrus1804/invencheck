<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class ItemRequestController extends Controller
{
    public function index()
    {
        $items = Item::orderBy('name')->get();

        $myRequests = StockTransaction::with('item')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('ambil-barang', compact('items', 'myRequests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'purpose' => 'required|string|max:255',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        if ($validated['quantity'] > $item->stock) {
            return back()
                ->withErrors(['quantity' => 'Jumlah melebihi stok tersedia (' . $item->stock . ' ' . $item->unit . ').'])
                ->withInput();
        }

        StockTransaction::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'type' => 'out',
            'quantity' => $validated['quantity'],
            'purpose' => $validated['purpose'],
            'status' => 'pending',
        ]);

        return redirect()->route('ambil-barang')->with('success', 'Permintaan berhasil diajukan, menunggu persetujuan Staff Gudang.');
    }

    public function history()
    {
        $transactions = StockTransaction::with('item')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('riwayat-saya', compact('transactions'));
    }

    public function printProof(StockTransaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Kamu tidak bisa mengakses bukti ini.');
        }

        if ($transaction->status !== 'approved') {
            return back()->with('error', 'Bukti hanya tersedia untuk permintaan yang sudah disetujui.');
        }

        $transaction->load(['item.warehouse', 'user', 'approver']);

        $pdf = Pdf::loadView('bukti-pengambilan-pdf', compact('transaction'));

        return $pdf->stream('bukti-pengambilan-' . $transaction->id . '.pdf');
    }
}