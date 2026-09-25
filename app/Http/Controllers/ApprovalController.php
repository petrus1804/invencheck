<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        $pendingRequests = StockTransaction::with(['item.warehouse', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('persetujuan', compact('pendingRequests'));
    }

    public function approve(StockTransaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $item = $transaction->item;

        if ($transaction->quantity > $item->stock) {
            return back()->with('error', 'Stok tidak mencukupi, permintaan tidak bisa disetujui.');
        }

        $item->decrement('stock', $transaction->quantity);

        $transaction->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Permintaan berhasil disetujui.');
    }

    public function reject(Request $request, StockTransaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $transaction->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Permintaan berhasil ditolak.');
    }
}