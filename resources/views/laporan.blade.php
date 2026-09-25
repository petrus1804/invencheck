@extends('layouts.app')

@section('title', 'Laporan - InvenCheck')

@section('styles')
    <link rel="stylesheet" href="{{ asset('laporan.css') }}">
@endsection

@section('content')

    <div class="page-heading">
        <div>
            <h1>Laporan</h1>
            <p>Ringkasan pergerakan stok dan riwayat transaksi gudang</p>
        </div>
        <div class="export-dropdown">
    <button type="button" class="btn-primary" onclick="document.getElementById('exportMenu').classList.toggle('show')">⭳ Export Laporan</button>
    <div class="export-menu" id="exportMenu">
        <a href="{{ route('laporan.export.excel', request()->query()) }}" class="dropdown-item">📊 Export sebagai Excel</a>
        <a href="{{ route('laporan.export.pdf', request()->query()) }}" class="dropdown-item">📄 Export sebagai PDF</a>
    </div>
</div>
    </div>

    {{-- FILTER PERIODE --}}
    <form method="GET" action="{{ route('laporan') }}" class="filter-bar">
        <select name="period" class="filter-select" onchange="this.form.submit()">
            <option value="7hari" {{ request('period', '7hari') == '7hari' ? 'selected' : '' }}>7 Hari Terakhir</option>
            <option value="30hari" {{ request('period') == '30hari' ? 'selected' : '' }}>30 Hari Terakhir</option>
            <option value="bulan_ini" {{ request('period') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
            <option value="bulan_lalu" {{ request('period') == 'bulan_lalu' ? 'selected' : '' }}>Bulan Lalu</option>
        </select>

        <select name="warehouse_id" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Gudang</option>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                    {{ $warehouse->name }}
                </option>
            @endforeach
        </select>

        <select name="type" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Jenis</option>
            <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Barang Masuk</option>
            <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Barang Keluar</option>
        </select>
    </form>

    {{-- SUMMARY CARDS --}}
    <section class="stat-grid">
        <div class="stat-card">
            <span class="stat-tag">Barang Masuk</span>
            <div class="stat-value">{{ number_format($barangMasuk, 0, ',', '.') }}</div>
            <div class="stat-foot muted">Pada periode ini</div>
        </div>

        <div class="stat-card">
            <span class="stat-tag">Barang Keluar</span>
            <div class="stat-value">{{ number_format($barangKeluar, 0, ',', '.') }}</div>
            <div class="stat-foot muted">Pada periode ini</div>
        </div>

        <div class="stat-card">
            <span class="stat-tag">Selisih Stok</span>
            <div class="stat-value">{{ $selisihStok >= 0 ? '+' : '' }}{{ number_format($selisihStok, 0, ',', '.') }}</div>
            <div class="stat-foot {{ $selisihStok >= 0 ? 'up' : 'down' }}">
                {{ $selisihStok >= 0 ? 'Stok bertambah' : 'Stok berkurang' }}
            </div>
        </div>

        <div class="stat-card critical">
            <span class="stat-tag">Barang Kritis</span>
            <div class="stat-value">{{ $barangKritis }}</div>
            <div class="stat-foot down">Perlu restock segera</div>
        </div>
    </section>

    {{-- CHART --}}
    <div class="panel chart-panel">
        <div class="panel-head">
            <h2>Tren Pergerakan Stok</h2>
            <div class="chart-legend">
                <span class="legend-item"><span class="dot in"></span> Masuk</span>
                <span class="legend-item"><span class="dot out"></span> Keluar</span>
            </div>
        </div>

        <div class="bar-chart">
            @foreach($chartData as $day)
                <div class="bar-group">
                    <div class="bars">
                        <div class="bar in" style="height: {{ $day['in_percent'] }}%;"><span class="bar-tip">{{ $day['in'] }}</span></div>
                        <div class="bar out" style="height: {{ $day['out_percent'] }}%;"><span class="bar-tip">{{ $day['out'] }}</span></div>
                    </div>
                    <span class="bar-label">{{ $day['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- RIWAYAT TRANSAKSI --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Riwayat Transaksi</h2>
        </div>

        <table class="stock-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Gudang</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                    <tr>
                        <td class="mono">{{ $trx->created_at->translatedFormat('d M Y') }}</td>
                        <td class="mono">{{ $trx->item->sku ?? '-' }}</td>
                        <td>{{ $trx->item->name ?? '-' }}</td>
                        <td>
                            @if($trx->type === 'in')
                                <span class="tag tag-ok">Masuk</span>
                            @else
                                <span class="tag tag-danger">Keluar</span>
                            @endif
                        </td>
                        <td class="mono">{{ $trx->type === 'in' ? '+' : '-' }}{{ $trx->quantity }}</td>
                        <td>{{ $trx->item->warehouse->name ?? '-' }}</td>
                        <td>{{ $trx->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--muted); padding: 20px;">
                            Belum ada transaksi pada periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrapper">
            {{ $transactions->links() }}
        </div>
    </div>

@endsection