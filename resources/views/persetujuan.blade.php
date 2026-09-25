@extends('layouts.app')

@section('title', 'Persetujuan - InvenCheck')

@section('content')

    <div class="page-heading">
        <div>
            <h1>Persetujuan Permintaan</h1>
            <p>Tinjau dan setujui permintaan pengambilan barang dari karyawan</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <div class="panel">
        <table class="stock-table">
            <thead>
                <tr>
                    <th>Pemohon</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Keperluan</th>
                    <th>Diajukan</th>
                    <th class="th-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingRequests as $req)
                    <tr>
                        <td>{{ $req->user->name ?? '-' }}</td>
                        <td>{{ $req->item->name ?? '-' }} <span class="mono" style="color: var(--muted); font-size: 11px;">({{ $req->item->sku ?? '-' }})</span></td>
                        <td class="mono">{{ $req->quantity }} {{ $req->item->unit ?? '' }}</td>
                        <td>{{ $req->purpose }}</td>
                        <td class="mono">{{ $req->created_at->translatedFormat('d M, H:i') }}</td>
                        <td class="th-center">
                            <div class="table-actions">
                                <form method="POST" action="{{ route('persetujuan.approve', $req->id) }}" onsubmit="return confirm('Setujui permintaan ini?')">
                                    @csrf
                                    <button type="submit" class="btn-approve" title="Setujui">✓</button>
                                </form>
                                <!-- <form method="POST" action="{{ route('persetujuan.reject', $req->id) }}" onsubmit="return confirm('Tolak permintaan ini?')"> -->
                                    <!-- @csrf -->
                                    <button type="button" class="btn-reject" title="Tolak" onclick='bukaModalTolak({{ $req->id }}, "{{ addslashes($req->item->name ?? "-") }}")'>✕</button>
                                <!-- </form> -->
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--muted); padding: 20px;">
                            Tidak ada permintaan yang menunggu persetujuan 🎉
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL TOLAK --}}
    <div class="modal-overlay" id="modalTolak">
        <div class="modal-box" style="max-width: 420px;">
            <div class="modal-head">
                <h3>Tolak Permintaan</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('modalTolak').classList.remove('show')">✕</button>
            </div>

            <p class="hint-text mb">Tolak permintaan <strong id="tolakItemName"></strong>? Berikan alasan supaya pemohon tahu penyebabnya.</p>

            <form method="POST" id="formTolak">
                @csrf
                <div class="form-group" style="margin-bottom: 16px;">
                    <textarea name="rejection_reason" rows="4" required maxlength="500" placeholder="Misal: Stok sedang dipakai untuk proyek lain, ajukan ulang minggu depan." style="padding: 10px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif; resize: vertical; width: 100%;"></textarea>
                </div>
                <button type="submit" class="btn-primary full" style="background: var(--red);">Tolak Permintaan</button>
            </form>
        </div>
    </div>

@section('scripts')
<script>
    function bukaModalTolak(id, itemName) {
        document.getElementById('formTolak').action = '/persetujuan/' + id + '/reject';
        document.getElementById('tolakItemName').textContent = itemName;
        document.getElementById('modalTolak').classList.add('show');
    }
</script>
@endsection

@endsection