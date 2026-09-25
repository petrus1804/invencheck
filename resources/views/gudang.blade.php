@extends('layouts.app')

@section('title', 'Gudang - InvenCheck')

@section('styles')
    <link rel="stylesheet" href="{{ asset('gudang.css') }}">
@endsection

@section('content')

    <div class="page-heading">
        <div>
            <h1>Gudang</h1>
            <p>Lokasi penyimpanan dan kapasitas tiap gudang</p>
        </div>
        @if(auth()->user()->hasPermission('manage_categories'))
            <button class="btn-primary" onclick="document.getElementById('modalTambah').classList.add('show')">+ Tambah Gudang</button>
        @endif
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <div class="warehouse-grid">

        @forelse($warehouses as $warehouse)
            <div class="warehouse-card">
                <div class="warehouse-top">
                    <div>
                        <h3>{{ $warehouse->name }}</h3>
                        <p class="warehouse-address">{{ $warehouse->address ?? 'Alamat belum diisi' }}</p>
                    </div>
                    @if($warehouse->capacity_percentage >= 90)
                        <span class="tag tag-warn">Hampir Penuh</span>
                    @else
                        <span class="tag tag-ok">Aktif</span>
                    @endif
                </div>

                <div class="capacity-block">
                    <div class="capacity-label">
                        <span>Kapasitas Terpakai</span>
                        <span class="mono">{{ $warehouse->capacity_percentage }}%</span>
                    </div>
                    <div class="capacity-bar">
                        <div class="capacity-fill {{ $warehouse->capacity_percentage >= 90 ? 'warn' : '' }}" style="width: {{ $warehouse->capacity_percentage }}%;"></div>
                    </div>
                </div>

                <div class="warehouse-meta">
                    <div class="meta-item">
                        <span class="meta-num mono">{{ $warehouse->items_count }}</span>
                        <span class="meta-lbl">Jenis Barang</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-num mono">{{ number_format($warehouse->total_stock, 0, ',', '.') }}</span>
                        <span class="meta-lbl">Total Unit</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-num">{{ $warehouse->pic->name ?? '-' }}</span>
                        <span class="meta-lbl">Penanggung Jawab</span>
                    </div>
                </div>

                @if(auth()->user()->hasPermission('manage_categories'))
                    <div class="warehouse-actions">
                        <button class="btn-outline small" onclick='bukaModalEdit(@json($warehouse))'>Edit Gudang</button>
                        <form method="POST" action="{{ route('gudang.destroy', $warehouse->id) }}" onsubmit="return confirm('Yakin hapus {{ $warehouse->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon danger" title="Hapus">🗑</button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <p style="color: var(--muted); font-size: 13px;">Belum ada gudang terdaftar.</p>
        @endforelse

    </div>

    @if(auth()->user()->hasPermission('manage_categories'))
    {{-- MODAL TAMBAH --}}
    <div class="modal-overlay" id="modalTambah">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Tambah Gudang</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('modalTambah').classList.remove('show')">✕</button>
            </div>

            <form method="POST" action="{{ route('gudang.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Gudang</label>
                        <input type="text" name="name" required placeholder="Misal: Gudang D">
                    </div>
                    <div class="form-group">
                        <label>Kapasitas Terpakai (%)</label>
                        <input type="number" name="capacity_percentage" required min="0" max="100" value="0">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Alamat</label>
                        <input type="text" name="address" placeholder="Alamat lengkap gudang">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Penanggung Jawab</label>
                        <select name="pic_id">
                            <option value="">Belum ditentukan</option>
                            @foreach($pics as $pic)
                                <option value="{{ $pic->id }}">{{ $pic->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn-primary full">Simpan Gudang</button>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div class="modal-overlay" id="modalEdit">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Edit Gudang</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('modalEdit').classList.remove('show')">✕</button>
            </div>

            <form method="POST" id="formEdit">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Gudang</label>
                        <input type="text" name="name" id="edit_name" required>
                    </div>
                    <div class="form-group">
                        <label>Kapasitas Terpakai (%)</label>
                        <input type="number" name="capacity_percentage" id="edit_capacity_percentage" required min="0" max="100">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Alamat</label>
                        <input type="text" name="address" id="edit_address">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Penanggung Jawab</label>
                        <select name="pic_id" id="edit_pic_id">
                            <option value="">Belum ditentukan</option>
                            @foreach($pics as $pic)
                                <option value="{{ $pic->id }}">{{ $pic->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn-primary full">Update Gudang</button>
            </form>
        </div>
    </div>
    @endif

@endsection

@section('scripts')
<script>
    function bukaModalEdit(warehouse) {
        document.getElementById('formEdit').action = '/gudang/' + warehouse.id;
        document.getElementById('edit_name').value = warehouse.name;
        document.getElementById('edit_address').value = warehouse.address ?? '';
        document.getElementById('edit_capacity_percentage').value = warehouse.capacity_percentage;
        document.getElementById('edit_pic_id').value = warehouse.pic_id ?? '';
        document.getElementById('modalEdit').classList.add('show');
    }
</script>
@endsection