@extends('layouts.app')

@section('title', 'Ambil Barang - InvenCheck')

@section('styles')
    <link rel="stylesheet" href="{{ asset('ambil-barang.css') }}">
@endsection

@section('content')

    <div class="page-heading">
        <div>
            <h1>Ambil Barang</h1>
            <p>Isi form untuk mengajukan pengambilan barang dari gudang</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="request-layout">

        <div class="panel">
            <div class="panel-head"><h2>Form Permintaan</h2></div>

            <form method="POST" action="{{ route('ambil-barang.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Pilih Barang</label>
                        <select name="item_id" required>
                            <option value="">-- Pilih barang --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} ({{ $item->sku }}) — Stok: {{ $item->stock }} {{ $item->unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" name="quantity" required min="1" value="{{ old('quantity') }}" placeholder="0">
                        @error('quantity')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Keperluan</label>
                        <input type="text" name="purpose" required value="{{ old('purpose') }}" placeholder="Misal: Perbaikan mesin produksi">
                    </div>
                </div>

                <button type="submit" class="btn-primary">Ajukan Permintaan</button>
                <p class="hint-text mb">Permintaan akan diperiksa oleh Staff Gudang sebelum barang diserahkan.</p>
            </form>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Status Permintaan Terbaru</h2></div>

                @forelse($myRequests as $req)
                <div class="request-item">
                    <div>
                        <div class="request-title">{{ $req->item->name ?? '-' }} × {{ $req->quantity }}</div>
                        <div class="request-desc">Diajukan {{ $req->created_at->translatedFormat('d M Y, H:i') }}</div>
                    </div>
                     <div class="request-status-area">
                        @if($req->status === 'pending')
                            <span class="tag tag-warn">Menunggu</span>
                        @elseif($req->status === 'approved')
                            <span class="tag tag-ok">Disetujui</span>
                            <a href="{{ route('riwayat-saya.bukti', $req->id) }}" target="_blank" class="btn-print" title="Cetak Bukti">🖨</a>
                        @else
                            <span class="tag tag-danger">Ditolak</span>
                        @endif
                    </div>
                </div>
            @empty
                <p style="font-size: 13px; color: var(--muted);">Belum ada permintaan yang diajukan.</p>
            @endforelse
        </div>

    </div>

@endsection