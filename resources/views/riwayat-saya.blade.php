@extends('layouts.app')

@section('title', 'Riwayat Saya - InvenCheck')

@section('content')

    <div class="page-heading">
        <div>
            <h1>Riwayat Saya</h1>
            <p>Seluruh riwayat pengambilan barang yang pernah kamu ajukan</p>
        </div>
    </div>

    <div class="panel">
        <table class="stock-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                    <tr>
                        <td class="mono">{{ $trx->created_at->translatedFormat('d M Y') }}</td>
                        <td>{{ $trx->item->name ?? '-' }}</td>
                        <td class="mono">{{ $trx->quantity }}</td>
                        <td>{{ $trx->purpose }}</td>
                        <td>
                            @if($trx->status === 'pending')
                                <span class="tag tag-warn">Menunggu</span>
                            @elseif($trx->status === 'approved')
                                <div class="request-status-area">
                                    <span class="tag tag-ok">Disetujui</span>
                                    <a href="{{ route('riwayat-saya.bukti', $trx->id) }}" target="_blank" class="btn-print" title="Cetak Bukti">🖨</a>
                                </div>
                            @else
                                <span class="tag tag-danger">Ditolak</span>
                                @if($trx->rejection_reason)l
                                    <div style="font-size: 11px; color: var(--muted); margin-top: 3px;">{{ $trx->rejection_reason }}</div>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--muted); padding: 20px;">
                            Belum ada riwayat pengambilan barang
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