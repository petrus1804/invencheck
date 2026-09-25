<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Item;
use App\Models\StockTransaction;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->resolvePeriod($request->get('period', '7hari'));

        $baseQuery = StockTransaction::whereBetween('created_at', [$startDate, $endDate]);

        if ($request->filled('warehouse_id')) {
            $baseQuery->whereHas('item', function ($q) use ($request) {
                $q->where('warehouse_id', $request->warehouse_id);
            });
        }

        $barangMasuk = (clone $baseQuery)->where('type', 'in')->sum('quantity');
        $barangKeluar = (clone $baseQuery)->where('type', 'out')->sum('quantity');
        $selisihStok = $barangMasuk - $barangKeluar;

        $barangKritis = Item::whereColumn('stock', '<=', 'minimum_stock')->count();

        $chartData = $this->buildChartData($startDate, $endDate, $request->warehouse_id);

        $historyQuery = StockTransaction::with(['item.warehouse', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->filled('warehouse_id')) {
            $historyQuery->whereHas('item', function ($q) use ($request) {
                $q->where('warehouse_id', $request->warehouse_id);
            });
        }

        if ($request->filled('type')) {
            $historyQuery->where('type', $request->type);
        }

        $transactions = $historyQuery->latest()->paginate(10)->withQueryString();

        $warehouses = Warehouse::orderBy('name')->get();

        return view('laporan', compact(
            'barangMasuk',
            'barangKeluar',
            'selisihStok',
            'barangKritis',
            'chartData',
            'transactions',
            'warehouses'
        ));
    }

        public function exportExcel(Request $request)
    {
        $transactions = $this->getFilteredTransactions($request);

        return Excel::download(new TransactionsExport($transactions), 'laporan-invencheck-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $transactions = $this->getFilteredTransactions($request);

        $pdf = Pdf::loadView('laporan-pdf', compact('transactions'));

        return $pdf->download('laporan-invencheck-' . now()->format('Y-m-d') . '.pdf');
    }

    private function getFilteredTransactions(Request $request)
    {
        [$startDate, $endDate] = $this->resolvePeriod($request->get('period', '7hari'));

        $query = StockTransaction::with(['item.warehouse', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->filled('warehouse_id')) {
            $query->whereHas('item', fn($q) => $q->where('warehouse_id', $request->warehouse_id));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return $query->latest()->get();
    }

    private function resolvePeriod(string $period): array
    {
        return match ($period) {
            '30hari' => [now()->subDays(30)->startOfDay(), now()->endOfDay()],
            'bulan_ini' => [now()->startOfMonth(), now()->endOfMonth()],
            'bulan_lalu' => [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()],
            default => [now()->subDays(6)->startOfDay(), now()->endOfDay()],
        };
    }

    private function buildChartData($startDate, $endDate, $warehouseId = null): array
    {
        $days = [];
        $cursor = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        while ($cursor <= $end && count($days) < 7) {
            $days[] = $cursor->copy();
            $cursor->addDay();
        }

        $data = [];
        $maxValue = 1;

        foreach ($days as $day) {
            $inQuery = StockTransaction::where('type', 'in')->whereDate('created_at', $day);
            $outQuery = StockTransaction::where('type', 'out')->whereDate('created_at', $day);

            if ($warehouseId) {
                $inQuery->whereHas('item', fn($q) => $q->where('warehouse_id', $warehouseId));
                $outQuery->whereHas('item', fn($q) => $q->where('warehouse_id', $warehouseId));
            }

            $in = $inQuery->sum('quantity');
            $out = $outQuery->sum('quantity');

            $maxValue = max($maxValue, $in, $out);

            $data[] = [
                'label' => $day->translatedFormat('D'),
                'in' => $in,
                'out' => $out,
            ];
        }

        foreach ($data as &$row) {
            $row['in_percent'] = round(($row['in'] / $maxValue) * 100);
            $row['out_percent'] = round(($row['out'] / $maxValue) * 100);
        }

        return $data;
    }
}