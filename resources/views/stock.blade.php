@extends('layouts.app')

@section('title', 'Stok Barang - InvenCheck')

@section('styles')
    <link rel="stylesheet" href="{{ asset('stock.css') }}">
@endsection

@section('content')

    <div class="page-heading">
        <div>
            <h1>Stok Barang</h1>
            <p>Kelola dan pantau seluruh stok barang di gudang</p>
        </div>
        <div class="stock-actions">
            @if(auth()->user()->hasPermission('manage_stock'))
            <button class="btn-primary" onclick="document.getElementById('modalTambah').classList.add('show')">+ Tambah Barang</button>
            @endif
            @if(auth()->user()->hasPermission('restock_items'))
                <button class="btn-primary" onclick="document.getElementById('modalRestock').classList.add('show')">+ Barang Masuk</button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- FILTER BAR --}}
    <form method="GET" action="{{ route('stock') }}" class="filter-bar">
        <div class="search-box wide">
            <span class="search-icon">⌕</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama barang...">
        </div>

        <select name="category_id" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="warehouse_id" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Gudang</option>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                    {{ $warehouse->name }}
                </option>
            @endforeach
        </select>

        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Aman</option>
            <option value="menipis" {{ request('status') == 'menipis' ? 'selected' : '' }}>Menipis</option>
            <option value="habis" {{ request('status') == 'habis' ? 'selected' : '' }}>Habis</option>
        </select>

        <button type="submit" class="btn-primary small">Cari</button>
    </form>

    {{-- TABLE --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Daftar Barang</h2>
            <span class="panel-count">{{ $items->total() }} barang</span>
        </div>

        <table class="stock-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Gudang</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Status</th>
                    <th class="th-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td class="mono">{{ $item->sku }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td>{{ $item->warehouse->name ?? '-' }}</td>
                        <td class="mono">{{ $item->stock }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>
                            @if($item->status === 'aman')
                                <span class="tag tag-ok">Aman</span>
                            @elseif($item->status === 'menipis')
                                <span class="tag tag-warn">Menipis</span>
                            @else
                                <span class="tag tag-danger">Habis</span>
                            @endif
                        </td>
                        <td class="th-center">
                            @if(auth()->user()->hasPermission('manage_stock'))
                            <div class="table-actions">
                                <button class="btn-icon" title="Edit" onclick='bukaModalEdit(@json($item))'>✎</button>
                                <form method="POST" action="{{ route('stock.destroy', $item->id) }}" onsubmit="return confirm('Yakin hapus {{ $item->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon danger" title="Hapus">🗑</button>
                                </form>
                            </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--muted); padding: 20px;">
                            Belum ada barang. Klik "+ Tambah Barang" untuk mulai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrapper">
            {{ $items->links() }}
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div class="modal-overlay" id="modalTambah">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Tambah Barang</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('modalTambah').classList.remove('show')">✕</button>
            </div>

            <form method="POST" action="{{ route('stock.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Kode SKU</label>
                        <input type="text" name="sku" required placeholder="SKU-0001">
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" name="name" required placeholder="Nama barang">
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_id" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Gudang</label>
                        <select name="warehouse_id" required>
                            <option value="">Pilih gudang</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Stok Awal</label>
                        <input type="number" name="stock" required min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label>Stok Minimum</label>
                        <input type="number" name="minimum_stock" required min="0" value="10">
                    </div>
                    <div class="form-group">
                        <label>Satuan</label>
                        <input type="text" name="unit" required placeholder="Pcs, Box, Kg, dll">
                    </div>
                </div>
                <button type="submit" class="btn-primary full">Simpan Barang</button>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div class="modal-overlay" id="modalEdit">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Edit Barang</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('modalEdit').classList.remove('show')">✕</button>
            </div>

            <form method="POST" id="formEdit">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group">
                        <label>Kode SKU</label>
                        <input type="text" name="sku" id="edit_sku" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" name="name" id="edit_name" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_id" id="edit_category_id" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Gudang</label>
                        <select name="warehouse_id" id="edit_warehouse_id" required>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stock" id="edit_stock" required min="0">
                    </div>
                    <div class="form-group">
                        <label>Stok Minimum</label>
                        <input type="number" name="minimum_stock" id="edit_minimum_stock" required min="0">
                    </div>
                    <div class="form-group">
                        <label>Satuan</label>
                        <input type="text" name="unit" id="edit_unit" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary full">Update Barang</button>
            </form>
        </div>
    </div>

    {{-- MODAL BARANG MASUK (RESTOCK) --}}
    <div class="modal-overlay" id="modalRestock">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Catat Barang Masuk</h3>
                <button type="button" class="modal-close" onclick="document.getElementById('modalRestock').classList.remove('show')">✕</button>
            </div>

            <form method="POST" action="{{ route('stock.restock') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Pilih Barang</label>
                        <select name="item_id" required>
                            <option value="">-- Pilih barang --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->sku }}) — Stok saat ini: {{ $item->stock }} {{ $item->unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Masuk</label>
                        <input type="number" name="quantity" required min="1" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Keterangan (opsional)</label>
                        <input type="text" name="purpose" placeholder="Misal: Restock dari Supplier X">
                    </div>
                </div>
                <button type="submit" class="btn-primary full">Simpan Barang Masuk</button>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function bukaModalEdit(item) {
        document.getElementById('formEdit').action = '/stock/' + item.id;
        document.getElementById('edit_sku').value = item.sku;
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_category_id').value = item.category_id;
        document.getElementById('edit_warehouse_id').value = item.warehouse_id;
        document.getElementById('edit_stock').value = item.stock;
        document.getElementById('edit_minimum_stock').value = item.minimum_stock;
        document.getElementById('edit_unit').value = item.unit;
        document.getElementById('modalEdit').classList.add('show');
    }
</script>
@endsection