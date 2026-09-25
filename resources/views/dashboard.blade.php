@extends('layouts.app')

@section('title', 'Dashboard - InvenCheck')

@section('styles')
    <link rel="stylesheet" href="{{ asset('dashboard.css') }}">
@endsection

@section('content')

    <div class="page-heading">
        <div>
            <h1>Dashboard</h1>
            <p>Ringkasan stok gudang hari ini, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        </div>
        <button class="btn-primary">+ Tambah Barang</button>
    </div>

    <section class="stat-grid">
        <div class="stat-card">
            <span class="stat-tag">Total SKU</span>
            <div class="stat-value">{{ number_format($totalSku, 0, ',', '.') }}</div>
            <div class="stat-foot muted">Jenis barang terdaftar</div>
        </div>

        <div class="stat-card">
            <span class="stat-tag">Barang Masuk</span>
            <div class="stat-value">{{ number_format($barangMasukHariIni, 0, ',', '.') }}</div>
            <div class="stat-foot muted">Hari ini</div>
        </div>

        <div class="stat-card">
            <span class="stat-tag">Barang Keluar</span>
            <div class="stat-value">{{ number_format($barangKeluarHariIni, 0, ',', '.') }}</div>
            <div class="stat-foot muted">Hari ini</div>
        </div>

        <div class="stat-card critical">
            <span class="stat-tag">Stok Menipis</span>
            <div class="stat-value">{{ $stokMenipis }}</div>
            <div class="stat-foot down">▼ Perlu restock</div>
        </div>
    </section>

    <section class="content-grid">
        <div class="panel">
            <div class="panel-head">
                <h2>Stok Terbaru</h2>
                <a href="{{ route('stock') }}" class="panel-link">Lihat semua →</a>
            </div>

            <table class="stock-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Gudang</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stokTerbaru as $item)
                        <tr>
                            <td class="mono">{{ $item->sku }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->warehouse->name ?? '-' }}</td>
                            <td class="mono">{{ $item->stock }}</td>
                            <td>
                                @if($item->status === 'aman')
                                    <span class="tag tag-ok">Aman</span>
                                @elseif($item->status === 'menipis')
                                    <span class="tag tag-warn">Menipis</span>
                                @else
                                    <span class="tag tag-danger">Habis</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--muted); padding: 20px;">
                                Belum ada data barang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="panel alert-panel">
            <div class="panel-head">
                <h2>Peringatan Stok</h2>
            </div>

            @forelse ($peringatanStok as $item)
                <div class="alert-item">
                    <span class="alert-dot {{ $item->stock <= 0 ? 'danger' : 'warn' }}"></span>
                    <div>
                        <div class="alert-title">{{ $item->name }}</div>
                        <div class="alert-desc">
                            {{ $item->stock <= 0 ? 'Stok habis' : 'Sisa ' . $item->stock . ' ' . $item->unit }}
                            di {{ $item->warehouse->name ?? '-' }}
                        </div>
                    </div>
                </div>
            @empty
                <p style="font-size: 13px; color: var(--muted);">Tidak ada peringatan stok saat ini 🎉</p>
            @endforelse

            <button class="btn-outline">Lihat semua peringatan</button>
        </div>
    </section>

@endsection